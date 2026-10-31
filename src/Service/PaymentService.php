<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Payment API operations.
 */
class PaymentService extends AbstractService
{
    /**
     * Get a list of payments with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getPayments(array $query = []): mixed
    {
        $path = '/api/Payment';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of payments with a search filter.
     */
    public function paginatePayments(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/Payment';
        return $this->paginate($path, $query, 'Skip', 'Take', $pageSize);
    }

    /**
     * Yields all items for Get a list of payments with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllPayments(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginatePayments($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a payment.
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createPayment(mixed $data = []): mixed
    {
        $path = '/api/Payment';
        return $this->post($path, $data, []);
    }

    /**
     * Get a payment
     *
     * @param string|int $id
     * @param array $query Query parameters
     * @return mixed
     */
    public function getPayment(string|int $id, array $query = []): mixed
    {
        $path = '/api/Payment/' . rawurlencode((string)$id);
        return $this->get($path, $query);
    }

    /**
     * Submit the payment for authorisation and posting.
     *
     * @param string|int $id
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function submitPayment(string|int $id, mixed $data = []): mixed
    {
        $path = '/api/Payment/' . rawurlencode((string)$id) . '/submit';
        return $this->post($path, $data, []);
    }

    /**
     * Add a payment allocation.
     *
     * @param string|int $docIdOrCode
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function addPaymentAllocation(string|int $docIdOrCode, mixed $data = []): mixed
    {
        $path = '/api/Payment/' . rawurlencode((string)$docIdOrCode) . '/allocation';
        return $this->post($path, $data, []);
    }

}
