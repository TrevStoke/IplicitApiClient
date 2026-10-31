<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Product API operations.
 */
class ProductService extends AbstractService
{
    /**
     * Get a list of products with a search filter
     *
     * The results are paginated using the !:skip and !:take parameters.
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getProducts(array $query = []): mixed
    {
        $path = '/api/Product';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of products with a search filter.
     */
    public function paginateProducts(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/Product';
        return $this->paginate($path, $query, 'skip', 'take', $pageSize);
    }

    /**
     * Yields all items for Get a list of products with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllProducts(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateProducts($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a product
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createProduct(mixed $data = []): mixed
    {
        $path = '/api/Product';
        return $this->post($path, $data, []);
    }

    /**
     * Get a product
     *
     * @param string|int $idOrCode
     * @param array $query Query parameters
     * @return mixed
     */
    public function getProduct(string|int $idOrCode, array $query = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$idOrCode);
        return $this->get($path, $query);
    }

    /**
     * Update a product
     *
     * @param string|int $idOrCode
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateProduct(string|int $idOrCode, mixed $data = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$idOrCode);
        return $this->patch($path, $data, []);
    }

    /**
     * Lock a product
     *
     * A locked product will mark all aspects (sale, purchase, etc) as inactive.
     *
     * @param string|int $id
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function lockProduct(string|int $id, mixed $data = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$id) . '/lock';
        return $this->post($path, $data, []);
    }

    /**
     * Permanently delete a product
     *
     * @param string|int $id
     * @return mixed
     */
    public function deleteProduct(string|int $id): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$id);
        return $this->delete($path, null, []);
    }

}
