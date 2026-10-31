<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit CostCentre API operations.
 */
class CostCentreService extends AbstractService
{
    /**
     * Get a list of cost centres with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getCostCentres(array $query = []): mixed
    {
        $path = '/api/CostCentre';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of cost centres with a search filter.
     */
    public function paginateCostCentres(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/CostCentre';
        return $this->paginate($path, $query, 'skip', 'take', $pageSize);
    }

    /**
     * Yields all items for Get a list of cost centres with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllCostCentres(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateCostCentres($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a cost centre
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createCostCentre(mixed $data = []): mixed
    {
        $path = '/api/CostCentre';
        return $this->post($path, $data, []);
    }

    /**
     * Get a cost centre
     *
     * @param string|int $costCentreRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getCostCentre(string|int $costCentreRef, array $query = []): mixed
    {
        $path = '/api/CostCentre/' . rawurlencode((string)$costCentreRef);
        return $this->get($path, $query);
    }

    /**
     * Update a cost centre
     *
     * @param string|int $costCentreRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateCostCentre(string|int $costCentreRef, mixed $data = []): mixed
    {
        $path = '/api/CostCentre/' . rawurlencode((string)$costCentreRef);
        return $this->patch($path, $data, []);
    }

    /**
     * Lock the cost centre
     *
     * @param string|int $costCentreRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function lockCostCentre(string|int $costCentreRef, mixed $data = []): mixed
    {
        $path = '/api/CostCentre/' . rawurlencode((string)$costCentreRef) . '/lock';
        return $this->post($path, $data, []);
    }

    /**
     * Unlock the cost centre
     *
     * @param string|int $costCentreRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unlockCostCentre(string|int $costCentreRef, mixed $data = []): mixed
    {
        $path = '/api/CostCentre/' . rawurlencode((string)$costCentreRef) . '/unlock';
        return $this->post($path, $data, []);
    }

}
