<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Department API operations.
 */
class DepartmentService extends AbstractService
{
    /**
     * Get a list of departments with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getDepartments(array $query = []): mixed
    {
        $path = '/api/Department';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of departments with a search filter.
     */
    public function paginateDepartments(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/Department';
        return $this->paginate($path, $query, 'skip', 'take', $pageSize);
    }

    /**
     * Yields all items for Get a list of departments with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllDepartments(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateDepartments($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a department
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createDepartment(mixed $data = []): mixed
    {
        $path = '/api/Department';
        return $this->post($path, $data, []);
    }

    /**
     * Get a department
     *
     * @param string|int $departmentRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getDepartment(string|int $departmentRef, array $query = []): mixed
    {
        $path = '/api/Department/' . rawurlencode((string)$departmentRef);
        return $this->get($path, $query);
    }

    /**
     * Update a department
     *
     * @param string|int $departmentRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function upateDepartment(string|int $departmentRef, mixed $data = []): mixed
    {
        $path = '/api/Department/' . rawurlencode((string)$departmentRef);
        return $this->patch($path, $data, []);
    }

    /**
     * Lock a department
     *
     * @param string|int $departmentRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function lockDepartment(string|int $departmentRef, mixed $data = []): mixed
    {
        $path = '/api/Department/' . rawurlencode((string)$departmentRef) . '/lock';
        return $this->post($path, $data, []);
    }

    /**
     * Unlock a department
     *
     * @param string|int $departmentRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unlockDepartment(string|int $departmentRef, mixed $data = []): mixed
    {
        $path = '/api/Department/' . rawurlencode((string)$departmentRef) . '/unlock';
        return $this->post($path, $data, []);
    }

}
