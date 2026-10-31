<?php

declare(strict_types=1);

namespace Iplicit\Http;

/**
 * Immutable value object representing an HTTP request to the Iplicit API.
 */
class Request
{
    private string $method;
    private string $url;
    private array $queryParams;
    private array $headers;
    private mixed $body;
    private int $timeout;

    public function __construct(
        string $method,
        string $url,
        array $queryParams = [],
        mixed $body = null,
        array $headers = [],
        int $timeout = 30
    ) {
        $this->method = strtoupper($method);
        $this->url = $url;
        $this->queryParams = $queryParams;
        $this->body = $body;
        $this->headers = $headers;
        $this->timeout = $timeout;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getFullUrl(): string
    {
        if (empty($this->queryParams)) {
            return $this->url;
        }

        $filteredParams = [];
        foreach ($this->queryParams as $key => $value) {
            if ($value === null) {
                continue;
            }
            if (is_bool($value)) {
                $filteredParams[$key] = $value ? 'true' : 'false';
            } else {
                $filteredParams[$key] = (string) $value;
            }
        }

        if (empty($filteredParams)) {
            return $this->url;
        }

        $query = http_build_query($filteredParams, '', '&', PHP_QUERY_RFC3986);
        $separator = str_contains($this->url, '?') ? '&' : '?';

        return $this->url . $separator . $query;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getBody(): mixed
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function withHeader(string $name, string $value): self
    {
        $headers = $this->headers;
        $headers[$name] = $value;

        return new self(
            $this->method,
            $this->url,
            $this->queryParams,
            $this->body,
            $headers,
            $this->timeout
        );
    }
}
