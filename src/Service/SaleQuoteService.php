<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit SaleQuote API operations.
 */
class SaleQuoteService extends AbstractService
{
    /**
     * Get a list of sale quotes with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getSaleQuotes(array $query = []): mixed
    {
        $path = '/api/SaleQuote';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of sale quotes with a search filter.
     */
    public function paginateSaleQuotes(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/SaleQuote';
        return $this->paginate($path, $query, 'Skip', 'Take', $pageSize);
    }

    /**
     * Yields all items for Get a list of sale quotes with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllSaleQuotes(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateSaleQuotes($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a sale quote
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createSaleQuote(mixed $data = []): mixed
    {
        $path = '/api/SaleQuote';
        return $this->post($path, $data, []);
    }

    /**
     * Get a sale quote
     *
     * @param string|int $id
     * @param array $query Query parameters
     * @return mixed
     */
    public function getSaleQuote(string|int $id, array $query = []): mixed
    {
        $path = '/api/SaleQuote/' . rawurlencode((string)$id);
        return $this->get($path, $query);
    }

    /**
     * Submit a sale quote for authorisation.
     *
     * @param string|int $id
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function submitSaleQuote(string|int $id, mixed $data = []): mixed
    {
        $path = '/api/SaleQuote/' . rawurlencode((string)$id) . '/submit';
        return $this->post($path, $data, []);
    }

    /**
     * Update a sale quote
     *
     * @param string|int $idOrDocNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateSaleQuote(string|int $idOrDocNo, mixed $data = []): mixed
    {
        $path = '/api/SaleQuote/' . rawurlencode((string)$idOrDocNo);
        return $this->patch($path, $data, []);
    }

}
