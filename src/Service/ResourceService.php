<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Resource API operations.
 */
class ResourceService extends AbstractService
{
    /**
     * Get a resource
     *
     * @param string|int $id
     * @param array $query Query parameters
     * @return mixed
     */
    public function getResource(string|int $id, array $query = []): mixed
    {
        $path = '/api/Resource/' . rawurlencode((string)$id);
        return $this->get($path, $query);
    }

}
