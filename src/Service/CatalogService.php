<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Catalog API operations.
 */
class CatalogService extends AbstractService
{
    /**
     * Get a list of all catalogs
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getAllCatalogs(array $query = []): mixed
    {
        $path = '/api/Catalog';
        return $this->get($path, $query);
    }

    /**
     * Get all items in a catalog
     *
     * @param string|int $catalogName
     * @param array $query Query parameters
     * @return mixed
     */
    public function getCatalog(string|int $catalogName, array $query = []): mixed
    {
        $path = '/api/Catalog/' . rawurlencode((string)$catalogName);
        return $this->get($path, $query);
    }

    /**
     * Replace all items in a catalog
     *
     * @param string|int $catalogName
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function replaceCatalogItems(string|int $catalogName, mixed $data = []): mixed
    {
        $path = '/api/Catalog/' . rawurlencode((string)$catalogName);
        return $this->post($path, $data, []);
    }

    /**
     * Add an item to a catalog
     *
     * @param string|int $catalogName
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function addCatalogItem(string|int $catalogName, mixed $data = []): mixed
    {
        $path = '/api/Catalog/' . rawurlencode((string)$catalogName);
        return $this->put($path, $data, []);
    }

    /**
     * Update an item in a catalog
     *
     * @param string|int $catalogName
     * @param string|int $idOrCode
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateCatalogItem(string|int $catalogName, string|int $idOrCode, mixed $data = []): mixed
    {
        $path = '/api/Catalog/' . rawurlencode((string)$catalogName) . '/' . rawurlencode((string)$idOrCode);
        return $this->patch($path, $data, []);
    }

    /**
     * Delete an item in a catalog
     *
     * @param string|int $catalogName
     * @param string|int $idOrCode
     * @return mixed
     */
    public function deleteCatalogItem(string|int $catalogName, string|int $idOrCode): mixed
    {
        $path = '/api/Catalog/' . rawurlencode((string)$catalogName) . '/' . rawurlencode((string)$idOrCode);
        return $this->delete($path, null, []);
    }

    /**
     * Lock a catalog item
     *
     * @param string|int $catalogName
     * @param string|int $idOrCode
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function lockCatalogItem(string|int $catalogName, string|int $idOrCode, mixed $data = []): mixed
    {
        $path = '/api/Catalog/' . rawurlencode((string)$catalogName) . '/' . rawurlencode((string)$idOrCode) . '/lock';
        return $this->post($path, $data, []);
    }

    /**
     * Unlock a catalog item
     *
     * @param string|int $catalogName
     * @param string|int $idOrCode
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unlockCatalogItem(string|int $catalogName, string|int $idOrCode, mixed $data = []): mixed
    {
        $path = '/api/Catalog/' . rawurlencode((string)$catalogName) . '/' . rawurlencode((string)$idOrCode) . '/unlock';
        return $this->post($path, $data, []);
    }

}
