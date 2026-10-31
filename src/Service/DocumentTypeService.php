<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit DocumentType API operations.
 */
class DocumentTypeService extends AbstractService
{
    /**
     * Get a document type
     *
     * @param string|int $documentTypeRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getDocumentType(string|int $documentTypeRef, array $query = []): mixed
    {
        $path = '/api/DocumentType/' . rawurlencode((string)$documentTypeRef);
        return $this->get($path, $query);
    }

}
