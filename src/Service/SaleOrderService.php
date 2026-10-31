<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit SaleOrder API operations.
 */
class SaleOrderService extends AbstractService
{
    /**
     * Get sale orders with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getSaleOrders(array $query = []): mixed
    {
        $path = '/api/SaleOrder';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get sale orders with a search filter.
     */
    public function paginateSaleOrders(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/SaleOrder';
        return $this->paginate($path, $query, 'Skip', 'Take', $pageSize);
    }

    /**
     * Yields all items for Get sale orders with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllSaleOrders(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateSaleOrders($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a sale order.
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createSaleOrder(mixed $data = []): mixed
    {
        $path = '/api/SaleOrder';
        return $this->post($path, $data, []);
    }

    /**
     * Get a sale order
     *
     * @param string|int $id
     * @param array $query Query parameters
     * @return mixed
     */
    public function getSaleOrder(string|int $id, array $query = []): mixed
    {
        $path = '/api/SaleOrder/' . rawurlencode((string)$id);
        return $this->get($path, $query);
    }

    /**
     * Submit a sale order for authorisation.
     *
     * @param string|int $id
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function submitSaleOrder(string|int $id, mixed $data = []): mixed
    {
        $path = '/api/SaleOrder/' . rawurlencode((string)$id) . '/submit';
        return $this->post($path, $data, []);
    }

    /**
     * Update a sale order
     *
     * @param string|int $idOrDocNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateSaleOrder(string|int $idOrDocNo, mixed $data = []): mixed
    {
        $path = '/api/SaleOrder/' . rawurlencode((string)$idOrDocNo);
        return $this->patch($path, $data, []);
    }

}
