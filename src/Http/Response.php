<?php

declare(strict_types=1);

namespace Iplicit\Http;

/**
 * Represents an HTTP response returned from the Iplicit API.
 */
class Response
{
    private int $statusCode;
    private string $rawBody;
    private array $headers;
    private mixed $decodedJson = null;
    private bool $jsonParsed = false;

    public function __construct(int $statusCode, string $rawBody = '', array $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->rawBody = $rawBody;
        $this->headers = $headers;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function isSuccess(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    public function getRawBody(): string
    {
        return $this->rawBody;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader(string $name): ?string
    {
        $target = strtolower($name);
        foreach ($this->headers as $key => $val) {
            if (strtolower($key) === $target) {
                return is_array($val) ? implode(', ', $val) : (string) $val;
            }
        }
        return null;
    }

    public function json(bool $assoc = true): mixed
    {
        if (!$this->jsonParsed) {
            $trimmed = trim($this->rawBody);
            if ($trimmed === '' || $trimmed === 'null') {
                $this->decodedJson = null;
            } else {
                try {
                    $this->decodedJson = json_decode($this->rawBody, $assoc, 512, JSON_THROW_ON_ERROR);
                } catch (\JsonException) {
                    $this->decodedJson = $this->rawBody;
                }
            }
            $this->jsonParsed = true;
        }

        return $this->decodedJson;
    }
}
