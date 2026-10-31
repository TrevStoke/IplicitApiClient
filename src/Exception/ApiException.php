<?php

declare(strict_types=1);

namespace Iplicit\Exception;

/**
 * Exception thrown when the Iplicit API returns an error HTTP response.
 */
class ApiException extends \RuntimeException implements IplicitException
{
    private int $statusCode;
    private mixed $responseBody;
    private array $responseHeaders;

    public function __construct(
        string $message,
        int $statusCode = 0,
        mixed $responseBody = null,
        array $responseHeaders = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
        $this->responseHeaders = $responseHeaders;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponseBody(): mixed
    {
        return $this->responseBody;
    }

    public function getResponseHeaders(): array
    {
        return $this->responseHeaders;
    }
}
