<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit LegalEntity API operations.
 */
class LegalEntityService extends AbstractService
{
    /**
     * Get all legal entities
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getAllLegalEntities(array $query = []): mixed
    {
        $path = '/api/LegalEntity';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get all legal entities.
     */
    public function paginateAllLegalEntities(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/LegalEntity';
        return $this->paginate($path, $query, 'skip', 'take', $pageSize);
    }

    /**
     * Yields all items for Get all legal entities across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllAllLegalEntities(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateAllLegalEntities($query, $pageSize);
        yield from $paginator;
    }

}
