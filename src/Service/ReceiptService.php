<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Receipt API operations.
 */
class ReceiptService extends AbstractService
{
    /**
     * Get a list of receipts with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getReceipts(array $query = []): mixed
    {
        $path = '/api/Receipt';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of receipts with a search filter.
     */
    public function paginateReceipts(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/Receipt';
        return $this->paginate($path, $query, 'Skip', 'Take', $pageSize);
    }

    /**
     * Yields all items for Get a list of receipts with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllReceipts(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateReceipts($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a receipt.
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createReceipt(mixed $data = []): mixed
    {
        $path = '/api/Receipt';
        return $this->post($path, $data, []);
    }

    /**
     * Get a receipt
     *
     * @param string|int $id
     * @param array $query Query parameters
     * @return mixed
     */
    public function getReceipt(string|int $id, array $query = []): mixed
    {
        $path = '/api/Receipt/' . rawurlencode((string)$id);
        return $this->get($path, $query);
    }

    /**
     * Submit a receipt for authorisation and posting.
     *
     * @param string|int $id
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function submitReceipt(string|int $id, mixed $data = []): mixed
    {
        $path = '/api/Receipt/' . rawurlencode((string)$id) . '/submit';
        return $this->post($path, $data, []);
    }

    /**
     * Add a receipt allocation.
     *
     * @param string|int $docIdOrCode
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function addReceiptAllocation(string|int $docIdOrCode, mixed $data = []): mixed
    {
        $path = '/api/Receipt/' . rawurlencode((string)$docIdOrCode) . '/allocation';
        return $this->post($path, $data, []);
    }

}
