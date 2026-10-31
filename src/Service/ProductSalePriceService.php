<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit ProductSalePrice API operations.
 */
class ProductSalePriceService extends AbstractService
{
    /**
     * Get a product sale price
     *
     * @param string|int $productRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getProductSalePrices(string|int $productRef, array $query = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/SalePrice';
        return $this->get($path, $query);
    }

    /**
     * Create a product sale price
     *
     * @param string|int $productRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createProductSalePrice(string|int $productRef, mixed $data = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/SalePrice';
        return $this->post($path, $data, []);
    }

    /**
     * Get a product sale price
     *
     * @param string|int $productRef
     * @param string|int $priceId
     * @param array $query Query parameters
     * @return mixed
     */
    public function getProductSalePrice(string|int $productRef, string|int $priceId, array $query = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/SalePrice/' . rawurlencode((string)$priceId);
        return $this->get($path, $query);
    }

    /**
     * Update a product sale price
     *
     * @param string|int $productRef
     * @param string|int $priceId
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateProductSalePrice(string|int $productRef, string|int $priceId, mixed $data = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/SalePrice/' . rawurlencode((string)$priceId);
        return $this->patch($path, $data, []);
    }

    /**
     * Delete a product sale price
     *
     * @param string|int $productRef
     * @param string|int $priceId
     * @return mixed
     */
    public function deleteProductSalePrice(string|int $productRef, string|int $priceId): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/SalePrice/' . rawurlencode((string)$priceId);
        return $this->delete($path, null, []);
    }

    /**
     * Lock a product sale price
     *
     * A locked product sale price will be marked as inactive.
     *
     * @param string|int $productRef
     * @param string|int $priceId
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function lockProductSalePrice(string|int $productRef, string|int $priceId, mixed $data = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/SalePrice/' . rawurlencode((string)$priceId) . '/lock';
        return $this->post($path, $data, []);
    }

    /**
     * Unlock a product sale price
     *
     * A locked product sale price will be marked as inactive.
     *
     * @param string|int $productRef
     * @param string|int $priceId
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unlockProductSalePrice(string|int $productRef, string|int $priceId, mixed $data = []): mixed
    {
        $path = '/api/Product/' . rawurlencode((string)$productRef) . '/SalePrice/' . rawurlencode((string)$priceId) . '/unlock';
        return $this->post($path, $data, []);
    }

}
