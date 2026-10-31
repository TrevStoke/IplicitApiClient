<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Profile API operations.
 */
class ProfileService extends AbstractService
{
    /**
     * Get profile of current session
     *
     * Can be used to test authentication
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getProfile(array $query = []): mixed
    {
        $path = '/api/Profile';
        return $this->get($path, $query);
    }

}
