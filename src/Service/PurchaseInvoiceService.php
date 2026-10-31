<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit PurchaseInvoice API operations.
 */
class PurchaseInvoiceService extends AbstractService
{
    /**
     * Get a list of purchase invoices with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getPurchaseInvoices(array $query = []): mixed
    {
        $path = '/api/PurchaseInvoice';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of purchase invoices with a search filter.
     */
    public function paginatePurchaseInvoices(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/PurchaseInvoice';
        return $this->paginate($path, $query, 'Skip', 'Take', $pageSize);
    }

    /**
     * Yields all items for Get a list of purchase invoices with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllPurchaseInvoices(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginatePurchaseInvoices($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a purchase invoice.
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createPurchaseInvoice(mixed $data = []): mixed
    {
        $path = '/api/PurchaseInvoice';
        return $this->post($path, $data, []);
    }

    /**
     * Get a purchase invoice
     *
     * @param string|int $id
     * @param array $query Query parameters
     * @return mixed
     */
    public function getPurchaseInvoice(string|int $id, array $query = []): mixed
    {
        $path = '/api/PurchaseInvoice/' . rawurlencode((string)$id);
        return $this->get($path, $query);
    }

    /**
     * Submit a purchase invoice for authorisation and posting.
     *
     * @param string|int $id
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function submitPurchaseInvoice(string|int $id, mixed $data = []): mixed
    {
        $path = '/api/PurchaseInvoice/' . rawurlencode((string)$id) . '/submit';
        return $this->post($path, $data, []);
    }

    /**
     * Update a purchase invoice.
     *
     * @param string|int $idOrDocNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updatePurchaseInvoice(string|int $idOrDocNo, mixed $data = []): mixed
    {
        $path = '/api/PurchaseInvoice/' . rawurlencode((string)$idOrDocNo);
        return $this->patch($path, $data, []);
    }

}
