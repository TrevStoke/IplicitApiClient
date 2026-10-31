<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\IplicitClient;
use Iplicit\Pagination\Paginator;

/**
 * Base service class providing common HTTP and pagination utilities.
 */
abstract class AbstractService
{
    protected IplicitClient $client;

    public function __construct(IplicitClient $client)
    {
        $this->client = $client;
    }

    public function getClient(): IplicitClient
    {
        return $this->client;
    }

    /**
     * Executes an HTTP GET request and returns decoded response data.
     */
    protected function get(string $path, array $queryParams = [], array $headers = []): mixed
    {
        $response = $this->client->request('GET', $path, $queryParams, null, $headers);
        return $response->json();
    }

    /**
     * Executes an HTTP POST request and returns decoded response data.
     */
    protected function post(string $path, mixed $body = null, array $queryParams = [], array $headers = []): mixed
    {
        $response = $this->client->request('POST', $path, $queryParams, $body, $headers);
        return $response->json();
    }

    /**
     * Executes an HTTP PUT request and returns decoded response data.
     */
    protected function put(string $path, mixed $body = null, array $queryParams = [], array $headers = []): mixed
    {
        $response = $this->client->request('PUT', $path, $queryParams, $body, $headers);
        return $response->json();
    }

    /**
     * Executes an HTTP PATCH request and returns decoded response data.
     */
    protected function patch(string $path, mixed $body = null, array $queryParams = [], array $headers = []): mixed
    {
        $response = $this->client->request('PATCH', $path, $queryParams, $body, $headers);
        return $response->json();
    }

    /**
     * Executes an HTTP DELETE request and returns decoded response data.
     */
    protected function delete(string $path, mixed $body = null, array $queryParams = [], array $headers = []): mixed
    {
        $response = $this->client->request('DELETE', $path, $queryParams, $body, $headers);
        return $response->json();
    }

    /**
     * Creates a Paginator instance for a paginated GET endpoint.
     *
     * @param string $path URL path
     * @param array $queryParams Base query parameters
     * @param string $skipParam Name of the skip/offset query parameter (e.g. 'skip' or 'Skip')
     * @param string $takeParam Name of the take/limit query parameter (e.g. 'take' or 'Take')
     * @param int $pageSize Default page size
     */
    protected function paginate(
        string $path,
        array $queryParams = [],
        string $skipParam = 'skip',
        string $takeParam = 'take',
        int $pageSize = 50
    ): Paginator {
        return new Paginator(
            function (int $skip, int $take) use ($path, $queryParams, $skipParam, $takeParam) {
                $params = array_merge($queryParams, [
                    $skipParam => $skip,
                    $takeParam => $take,
                ]);
                $result = $this->get($path, $params);
                return is_array($result) ? $result : [];
            },
            $pageSize
        );
    }
}
