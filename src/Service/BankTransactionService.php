<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit BankTransaction API operations.
 */
class BankTransactionService extends AbstractService
{
    /**
     * Get a bank transaction
     *
     * @param string|int $bankTransactionId
     * @param array $query Query parameters
     * @return mixed
     */
    public function getBankTransaction(string|int $bankTransactionId, array $query = []): mixed
    {
        $path = '/api/BankTransaction/' . rawurlencode((string)$bankTransactionId);
        return $this->get($path, $query);
    }

    /**
     * Update a bank transaction
     *
     * @param string|int $bankTransactionId
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateBankTransaction(string|int $bankTransactionId, mixed $data = []): mixed
    {
        $path = '/api/BankTransaction/' . rawurlencode((string)$bankTransactionId);
        return $this->patch($path, $data, []);
    }

    /**
     * Delete a bank transaction
     *
     * @param string|int $bankTransactionId
     * @return mixed
     */
    public function deleteBankTransaction(string|int $bankTransactionId): mixed
    {
        $path = '/api/BankTransaction/' . rawurlencode((string)$bankTransactionId);
        return $this->delete($path, null, []);
    }

    /**
     * Create a bank transaction
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createBankTransaction(mixed $data = []): mixed
    {
        $path = '/api/BankTransaction';
        return $this->post($path, $data, []);
    }

    /**
     * Lock a bank transaction
     *
     * @param string|int $bankTransactionId
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function lockBankTransaction(string|int $bankTransactionId, mixed $data = []): mixed
    {
        $path = '/api/BankTransaction/' . rawurlencode((string)$bankTransactionId) . '/lock';
        return $this->post($path, $data, []);
    }

    /**
     * Unlock a bank transaction
     *
     * @param string|int $bankTransactionId
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unlockBankTransaction(string|int $bankTransactionId, mixed $data = []): mixed
    {
        $path = '/api/BankTransaction/' . rawurlencode((string)$bankTransactionId) . '/unlock';
        return $this->post($path, $data, []);
    }

}
