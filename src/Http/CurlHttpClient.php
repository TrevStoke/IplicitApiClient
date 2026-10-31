<?php

declare(strict_types=1);

namespace Iplicit\Http;

use Iplicit\Exception\NetworkException;

/**
 * cURL-based HTTP client implementation.
 */
class CurlHttpClient implements HttpClientInterface
{
    private array $defaultHeaders;
    private int $defaultTimeout;
    private bool $verifySsl;

    public function __construct(
        array $defaultHeaders = [],
        int $defaultTimeout = 30,
        bool $verifySsl = true
    ) {
        $this->defaultHeaders = $defaultHeaders;
        $this->defaultTimeout = $defaultTimeout;
        $this->verifySsl = $verifySsl;
    }

    public function send(Request $request): Response
    {
        $ch = curl_init();
        if ($ch === false) {
            throw new NetworkException('Failed to initialize cURL handle');
        }

        $url = $request->getFullUrl();
        $method = $request->getMethod();
        $timeout = $request->getTimeout() > 0 ? $request->getTimeout() : $this->defaultTimeout;

        // Build headers
        $headersMap = array_merge(
            [
                'Accept' => 'application/json',
                'User-Agent' => 'IplicitApiClient/1.0',
            ],
            $this->defaultHeaders,
            $request->getHeaders()
        );

        $body = $request->getBody();
        $payload = null;

        if ($body !== null) {
            if (is_string($body)) {
                $payload = $body;
                if (!isset($headersMap['Content-Type'])) {
                    $headersMap['Content-Type'] = 'application/json';
                }
            } else {
                try {
                    $payload = json_encode($body, JSON_THROW_ON_ERROR);
                } catch (\JsonException $e) {
                    throw new NetworkException('Failed to JSON-encode request body: ' . $e->getMessage(), 0, $e);
                }
                if (!isset($headersMap['Content-Type'])) {
                    $headersMap['Content-Type'] = 'application/json';
                }
            }
        }

        $formattedHeaders = [];
        foreach ($headersMap as $name => $value) {
            $formattedHeaders[] = "{$name}: {$value}";
        }

        $responseHeaders = [];

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => min(10, $timeout),
            CURLOPT_SSL_VERIFYPEER => $this->verifySsl,
            CURLOPT_SSL_VERIFYHOST => $this->verifySsl ? 2 : 0,
            CURLOPT_HTTPHEADER => $formattedHeaders,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HEADERFUNCTION => function ($ch, string $headerLine) use (&$responseHeaders): int {
                $len = strlen($headerLine);
                $parts = explode(':', $headerLine, 2);
                if (count($parts) === 2) {
                    $key = trim($parts[0]);
                    $val = trim($parts[1]);
                    if (isset($responseHeaders[$key])) {
                        if (is_array($responseHeaders[$key])) {
                            $responseHeaders[$key][] = $val;
                        } else {
                            $responseHeaders[$key] = [$responseHeaders[$key], $val];
                        }
                    } else {
                        $responseHeaders[$key] = $val;
                    }
                }
                return $len;
            },
        ];

        if ($payload !== null) {
            $options[CURLOPT_POSTFIELDS] = $payload;
        }

        curl_setopt_array($ch, $options);

        $responseBody = curl_exec($ch);

        if ($responseBody === false) {
            $errno = curl_errno($ch);
            $error = curl_error($ch);
            throw new NetworkException("cURL error ({$errno}): {$error}", $errno);
        }

        $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return new Response($statusCode, (string) $responseBody, $responseHeaders);
    }
}
