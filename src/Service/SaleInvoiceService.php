<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit SaleInvoice API operations.
 */
class SaleInvoiceService extends AbstractService
{
    /**
     * Get a list of sale invoices with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getSaleInvoices(array $query = []): mixed
    {
        $path = '/api/SaleInvoice';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of sale invoices with a search filter.
     */
    public function paginateSaleInvoices(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/SaleInvoice';
        return $this->paginate($path, $query, 'Skip', 'Take', $pageSize);
    }

    /**
     * Yields all items for Get a list of sale invoices with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllSaleInvoices(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateSaleInvoices($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a sale invoice.
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createSaleInvoice(mixed $data = []): mixed
    {
        $path = '/api/SaleInvoice';
        return $this->post($path, $data, []);
    }

    /**
     * Get a sale invoice
     *
     * @param string|int $id
     * @param array $query Query parameters
     * @return mixed
     */
    public function getSaleInvoice(string|int $id, array $query = []): mixed
    {
        $path = '/api/SaleInvoice/' . rawurlencode((string)$id);
        return $this->get($path, $query);
    }

    /**
     * Submit a sale invoice for authorisation and posting.
     *
     * @param string|int $id
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function submitSaleInvoice(string|int $id, mixed $data = []): mixed
    {
        $path = '/api/SaleInvoice/' . rawurlencode((string)$id) . '/submit';
        return $this->post($path, $data, []);
    }

    /**
     * Update a sale invoice
     *
     * @param string|int $idOrDocNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateSaleInvoice(string|int $idOrDocNo, mixed $data = []): mixed
    {
        $path = '/api/SaleInvoice/' . rawurlencode((string)$idOrDocNo);
        return $this->patch($path, $data, []);
    }

}
