<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Contact API operations.
 */
class ContactService extends AbstractService
{
    /**
     * Get a contact
     *
     * @param string|int $idOrIntRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getContact(string|int $idOrIntRef, array $query = []): mixed
    {
        $path = '/api/Contact/' . rawurlencode((string)$idOrIntRef);
        return $this->get($path, $query);
    }

    /**
     * Update a contact
     *
     * PATCH overwrites Address/Phone/Email
     *
     * @param string|int $idOrIntRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateContact(string|int $idOrIntRef, mixed $data = []): mixed
    {
        $path = '/api/Contact/' . rawurlencode((string)$idOrIntRef);
        return $this->patch($path, $data, []);
    }

    /**
     * Delete a contact
     *
     * @param string|int $idOrIntRef
     * @return mixed
     */
    public function deleteContact(string|int $idOrIntRef): mixed
    {
        $path = '/api/Contact/' . rawurlencode((string)$idOrIntRef);
        return $this->delete($path, null, []);
    }

    /**
     * Create a contact
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createContact(mixed $data = []): mixed
    {
        $path = '/api/Contact';
        return $this->post($path, $data, []);
    }

    /**
     * Lock the contact
     *
     * @param string|int $idOrIntRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function lockContact(string|int $idOrIntRef, mixed $data = []): mixed
    {
        $path = '/api/Contact/' . rawurlencode((string)$idOrIntRef) . '/lock';
        return $this->post($path, $data, []);
    }

    /**
     * Unlock the contact
     *
     * @param string|int $idOrIntRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unlockContact(string|int $idOrIntRef, mixed $data = []): mixed
    {
        $path = '/api/Contact/' . rawurlencode((string)$idOrIntRef) . '/unlock';
        return $this->post($path, $data, []);
    }

}
