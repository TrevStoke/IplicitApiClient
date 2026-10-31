<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Project API operations.
 */
class ProjectService extends AbstractService
{
    /**
     * Get a list of projects with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getProjects(array $query = []): mixed
    {
        $path = '/api/Project';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of projects with a search filter.
     */
    public function paginateProjects(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/Project';
        return $this->paginate($path, $query, 'skip', 'take', $pageSize);
    }

    /**
     * Yields all items for Get a list of projects with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllProjects(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateProjects($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a project
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createProject(mixed $data = []): mixed
    {
        $path = '/api/Project';
        return $this->post($path, $data, []);
    }

    /**
     * Get a project
     *
     * @param string|int $projectRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getProject(string|int $projectRef, array $query = []): mixed
    {
        $path = '/api/Project/' . rawurlencode((string)$projectRef);
        return $this->get($path, $query);
    }

    /**
     * Update a project
     *
     * @param string|int $projectRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateProject(string|int $projectRef, mixed $data = []): mixed
    {
        $path = '/api/Project/' . rawurlencode((string)$projectRef);
        return $this->patch($path, $data, []);
    }

    /**
     * Get a project resource availability
     *
     * @param string|int $projectRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getProjectResourceAvailability(string|int $projectRef, array $query = []): mixed
    {
        $path = '/api/Project/' . rawurlencode((string)$projectRef) . '/availability';
        return $this->get($path, $query);
    }

    /**
     * Add a project resource availability
     *
     * @param string|int $projectRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function addProjectResourceAvailability(string|int $projectRef, mixed $data = []): mixed
    {
        $path = '/api/Project/' . rawurlencode((string)$projectRef) . '/availability';
        return $this->post($path, $data, []);
    }

    /**
     * Replace a project resource availability
     *
     * @param string|int $projectRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function replaceProjectResourceAvailability(string|int $projectRef, mixed $data = []): mixed
    {
        $path = '/api/Project/' . rawurlencode((string)$projectRef) . '/availability';
        return $this->put($path, $data, []);
    }

    /**
     * Delete a project resource availability
     *
     * @param string|int $projectRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function deleteProjectResourceAvailability(string|int $projectRef, mixed $data = []): mixed
    {
        $path = '/api/Project/' . rawurlencode((string)$projectRef) . '/availability';
        return $this->delete($path, $data, []);
    }

    /**
     * Get a project milestones
     *
     * @param string|int $projectRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function projectMilstones(string|int $projectRef, array $query = []): mixed
    {
        $path = '/api/Project/' . rawurlencode((string)$projectRef) . '/milestone';
        return $this->get($path, $query);
    }

    /**
     * Add a project milestone
     *
     * @param string|int $projectRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function addProjectMilstones(string|int $projectRef, mixed $data = []): mixed
    {
        $path = '/api/Project/' . rawurlencode((string)$projectRef) . '/milestone';
        return $this->put($path, $data, []);
    }

    /**
     * Replace all project milestones
     *
     * @param string|int $projectRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function replaceProjectMilstones(string|int $projectRef, mixed $data = []): mixed
    {
        $path = '/api/Project/' . rawurlencode((string)$projectRef) . '/milestone';
        return $this->post($path, $data, []);
    }

    /**
     * Delete a project milestone
     *
     * @param string|int $projectRef
     * @param string|int $milestoneIdOrCode
     * @return mixed
     */
    public function deleteProjectMilstones(string|int $projectRef, string|int $milestoneIdOrCode): mixed
    {
        $path = '/api/Project/' . rawurlencode((string)$projectRef) . '/milestone/' . rawurlencode((string)$milestoneIdOrCode);
        return $this->delete($path, null, []);
    }

    /**
     * Lock a project
     *
     * @param string|int $projectRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function lockProject(string|int $projectRef, mixed $data = []): mixed
    {
        $path = '/api/Project/' . rawurlencode((string)$projectRef) . '/lock';
        return $this->post($path, $data, []);
    }

    /**
     * Unlock a project
     *
     * @param string|int $projectRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unlockProject(string|int $projectRef, mixed $data = []): mixed
    {
        $path = '/api/Project/' . rawurlencode((string)$projectRef) . '/unlock';
        return $this->post($path, $data, []);
    }

}
