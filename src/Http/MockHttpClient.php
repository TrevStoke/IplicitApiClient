<?php

declare(strict_types=1);

namespace Iplicit\Http;

use Iplicit\Exception\NetworkException;

/**
 * Mock HTTP client for unit tests. Allows queueing responses and inspecting requests.
 */
class MockHttpClient implements HttpClientInterface
{
    /** @var array<Response|\Throwable|callable> */
    private array $responseQueue = [];

    /** @var Request[] */
    private array $recordedRequests = [];

    /** @var callable|null */
    private $requestHandler = null;

    public function __construct(array $responses = [])
    {
        $this->responseQueue = $responses;
    }

    /**
     * Add a response or exception to the queue.
     *
     * @param Response|\Throwable|callable $response
     */
    public function addResponse(mixed $response): self
    {
        $this->responseQueue[] = $response;
        return $this;
    }

    /**
     * Add a JSON response helper.
     */
    public function addJsonResponse(mixed $data, int $statusCode = 200, array $headers = []): self
    {
        $headers['Content-Type'] = 'application/json';
        $json = json_encode($data, JSON_THROW_ON_ERROR);
        $this->responseQueue[] = new Response($statusCode, $json, $headers);
        return $this;
    }

    public function setHandler(?callable $handler): self
    {
        $this->requestHandler = $handler;
        return $this;
    }

    public function send(Request $request): Response
    {
        $this->recordedRequests[] = $request;

        if ($this->requestHandler !== null) {
            $handler = $this->requestHandler;
            $res = $handler($request);
            if ($res instanceof Response) {
                return $res;
            }
            if ($res instanceof \Throwable) {
                throw $res;
            }
        }

        if (empty($this->responseQueue)) {
            throw new NetworkException('MockHttpClient response queue is empty; no response queued for ' . $request->getMethod() . ' ' . $request->getFullUrl());
        }

        $next = array_shift($this->responseQueue);

        if ($next instanceof \Throwable) {
            throw $next;
        }

        if (is_callable($next)) {
            $res = $next($request);
            if ($res instanceof Response) {
                return $res;
            }
            if ($res instanceof \Throwable) {
                throw $res;
            }
        }

        if ($next instanceof Response) {
            return $next;
        }

        throw new \RuntimeException('Invalid mock response item in queue');
    }

    /**
     * @return Request[]
     */
    public function getRecordedRequests(): array
    {
        return $this->recordedRequests;
    }

    public function getLastRequest(): ?Request
    {
        if (empty($this->recordedRequests)) {
            return null;
        }
        return $this->recordedRequests[count($this->recordedRequests) - 1];
    }

    public function countRequests(): int
    {
        return count($this->recordedRequests);
    }

    public function reset(): void
    {
        $this->responseQueue = [];
        $this->recordedRequests = [];
        $this->requestHandler = null;
    }
}
