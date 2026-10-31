<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit PurchaseOrder API operations.
 */
class PurchaseOrderService extends AbstractService
{
    /**
     * Get a list of purchase orders with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getPurchaseOrders(array $query = []): mixed
    {
        $path = '/api/PurchaseOrder';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of purchase orders with a search filter.
     */
    public function paginatePurchaseOrders(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/PurchaseOrder';
        return $this->paginate($path, $query, 'Skip', 'Take', $pageSize);
    }

    /**
     * Yields all items for Get a list of purchase orders with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllPurchaseOrders(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginatePurchaseOrders($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a purchase order.
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createPurchaseOrder(mixed $data = []): mixed
    {
        $path = '/api/PurchaseOrder';
        return $this->post($path, $data, []);
    }

    /**
     * Get a purchase order.
     *
     * @param string|int $id
     * @param array $query Query parameters
     * @return mixed
     */
    public function getPurchaseOrder(string|int $id, array $query = []): mixed
    {
        $path = '/api/PurchaseOrder/' . rawurlencode((string)$id);
        return $this->get($path, $query);
    }

    /**
     * Submit a purchase order for authorisation.
     *
     * @param string|int $id
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function submitPurchaseOrder(string|int $id, mixed $data = []): mixed
    {
        $path = '/api/PurchaseOrder/' . rawurlencode((string)$id) . '/submit';
        return $this->post($path, $data, []);
    }

    /**
     * Update a purchase order.
     *
     * @param string|int $idOrDocNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updatePurchaseOrder(string|int $idOrDocNo, mixed $data = []): mixed
    {
        $path = '/api/PurchaseOrder/' . rawurlencode((string)$idOrDocNo);
        return $this->patch($path, $data, []);
    }

}
