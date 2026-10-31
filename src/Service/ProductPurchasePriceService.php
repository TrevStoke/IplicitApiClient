<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit ProductPurchasePrice API operations.
 */
class ProductPurchasePriceService extends AbstractService
{
    /**
     * Get all product purchase prices for productRef
     *
     * @param string|int $productRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getProductPurchasePrices(string|int $productRef, array $query = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/PurchasePrice';
        return $this->get($path, $query);
    }

    /**
     * Create a product purchase price
     *
     * @param string|int $productRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createProductPurchasePrice(string|int $productRef, mixed $data = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/PurchasePrice';
        return $this->post($path, $data, []);
    }

    /**
     * Get a product purchase price from priceId
     *
     * @param string|int $productRef
     * @param string|int $priceId
     * @param array $query Query parameters
     * @return mixed
     */
    public function getProductPurchasePrice(string|int $productRef, string|int $priceId, array $query = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/PurchasePrice/' . rawurlencode((string)$priceId);
        return $this->get($path, $query);
    }

    /**
     * Update a product purchase price
     *
     * @param string|int $productRef
     * @param string|int $priceId
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateProductPurchasePrice(string|int $productRef, string|int $priceId, mixed $data = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/PurchasePrice/' . rawurlencode((string)$priceId);
        return $this->patch($path, $data, []);
    }

    /**
     * Delete a product purchase price
     *
     * @param string|int $productRef
     * @param string|int $priceId
     * @return mixed
     */
    public function deleteProductPurchasePrice(string|int $productRef, string|int $priceId): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/PurchasePrice/' . rawurlencode((string)$priceId);
        return $this->delete($path, null, []);
    }

    /**
     * Lock a product purchase price
     *
     * A locked product purchase price will be marked as inactive.
     *
     * @param string|int $productRef
     * @param string|int $priceId
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function lockProductPurchasePrice(string|int $productRef, string|int $priceId, mixed $data = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/PurchasePrice/' . rawurlencode((string)$priceId) . '/lock';
        return $this->post($path, $data, []);
    }

    /**
     * Unlock a product purchase price
     *
     * A locked product purcgase price will be marked as inactive.
     *
     * @param string|int $productRef
     * @param string|int $priceId
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unlockProductPurchasePrice(string|int $productRef, string|int $priceId, mixed $data = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/PurchasePrice/' . rawurlencode((string)$priceId) . '/unlock';
        return $this->post($path, $data, []);
    }

}
