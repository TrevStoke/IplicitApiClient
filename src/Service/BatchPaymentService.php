<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit BatchPayment API operations.
 */
class BatchPaymentService extends AbstractService
{
    /**
     * Get batch payments with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getBatchPayments(array $query = []): mixed
    {
        $path = '/api/BatchPayment';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get batch payments with a search filter.
     */
    public function paginateBatchPayments(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/BatchPayment';
        return $this->paginate($path, $query, 'skip', 'take', $pageSize);
    }

    /**
     * Yields all items for Get batch payments with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllBatchPayments(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateBatchPayments($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a batch payment
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createBatchPayment(mixed $data = []): mixed
    {
        $path = '/api/BatchPayment';
        return $this->post($path, $data, []);
    }

    /**
     * Get a batch payment by id or by batch number.
     *
     * @param string|int $idOrBatchNo
     * @param array $query Query parameters
     * @return mixed
     */
    public function getBatchPayment(string|int $idOrBatchNo, array $query = []): mixed
    {
        $path = '/api/BatchPayment/' . rawurlencode((string)$idOrBatchNo);
        return $this->get($path, $query);
    }

    /**
     * Update a batch payment
     *
     * @param string|int $batchPaymentId
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateBatchPayment(string|int $batchPaymentId, mixed $data = []): mixed
    {
        $path = '/api/BatchPayment/' . rawurlencode((string)$batchPaymentId);
        return $this->patch($path, $data, []);
    }

    /**
     * Add allocations to the batch payment
     *
     * @param string|int $idOrBatchNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function addBatchPaymentAllocations(string|int $idOrBatchNo, mixed $data = []): mixed
    {
        $path = '/api/BatchPayment/' . rawurlencode((string)$idOrBatchNo) . '/allocations';
        return $this->post($path, $data, []);
    }

    /**
     * Replaces all allocations in the batch payment
     *
     * @param string|int $idOrBatchNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function replaceBatchPaymentAllocations(string|int $idOrBatchNo, mixed $data = []): mixed
    {
        $path = '/api/BatchPayment/' . rawurlencode((string)$idOrBatchNo) . '/allocations';
        return $this->put($path, $data, []);
    }

    /**
     * Delete an allocation in the batch payment
     *
     * @param string|int $idOrBatchNo
     * @param string|int $allocationId
     * @return mixed
     */
    public function deleteBatchPaymentAllocation(string|int $idOrBatchNo, string|int $allocationId): mixed
    {
        $path = '/api/BatchPayment/' . rawurlencode((string)$idOrBatchNo) . '/allocations/' . rawurlencode((string)$allocationId);
        return $this->delete($path, null, []);
    }

    /**
     * Abandon a batch payment
     *
     * @param string|int $idOrBatchNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function abandonBatchPayment(string|int $idOrBatchNo, mixed $data = []): mixed
    {
        $path = '/api/BatchPayment/' . rawurlencode((string)$idOrBatchNo) . '/abandon';
        return $this->post($path, $data, []);
    }

    /**
     * Submit a batch payment
     *
     * @param string|int $idOrBatchNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function submitBatchPayment(string|int $idOrBatchNo, mixed $data = []): mixed
    {
        $path = '/api/BatchPayment/' . rawurlencode((string)$idOrBatchNo) . '/submit';
        return $this->post($path, $data, []);
    }

    /**
     * Post a batch payment
     *
     * @param string|int $idOrBatchNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function postBatchPayment(string|int $idOrBatchNo, mixed $data = []): mixed
    {
        $path = '/api/BatchPayment/' . rawurlencode((string)$idOrBatchNo) . '/post';
        return $this->post($path, $data, []);
    }

    /**
     * Unpost a batch payment
     *
     * @param string|int $idOrBatchNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unpostBatchPayment(string|int $idOrBatchNo, mixed $data = []): mixed
    {
        $path = '/api/BatchPayment/' . rawurlencode((string)$idOrBatchNo) . '/unpost';
        return $this->post($path, $data, []);
    }

    /**
     * Set an approved batch payment back to draft for amending
     *
     * @param string|int $idOrBatchNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function amendBatchPayment(string|int $idOrBatchNo, mixed $data = []): mixed
    {
        $path = '/api/BatchPayment/' . rawurlencode((string)$idOrBatchNo) . '/amend';
        return $this->post($path, $data, []);
    }

    /**
     * Process a batch payment
     *
     * @param string|int $idOrBatchNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function processBatchPayment(string|int $idOrBatchNo, mixed $data = []): mixed
    {
        $path = '/api/BatchPayment/' . rawurlencode((string)$idOrBatchNo) . '/process';
        return $this->post($path, $data, []);
    }

}
