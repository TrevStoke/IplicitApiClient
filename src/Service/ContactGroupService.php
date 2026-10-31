<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit ContactGroup API operations.
 */
class ContactGroupService extends AbstractService
{
    /**
     * Get a contact group
     *
     * @param string|int $contactGroupRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getContactGroup(string|int $contactGroupRef, array $query = []): mixed
    {
        $path = '/api/ContactGroup/' . rawurlencode((string)$contactGroupRef);
        return $this->get($path, $query);
    }

}
