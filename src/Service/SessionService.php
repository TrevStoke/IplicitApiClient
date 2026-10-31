<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Session API operations.
 */
class SessionService extends AbstractService
{
    /**
     * Create a session with an API Key.
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createSessionAPIKey(mixed $data = []): mixed
    {
        $path = '/api/Session/create/api';
        return $this->post($path, $data, []);
    }

    /**
     * Close the session
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function closeSession(mixed $data = []): mixed
    {
        $path = '/api/Session/close';
        return $this->post($path, $data, []);
    }

}
