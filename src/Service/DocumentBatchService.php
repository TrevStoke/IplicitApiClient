<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit DocumentBatch API operations.
 */
class DocumentBatchService extends AbstractService
{
    /**
     * Unpost a document
     *
     * @param string|int $idOrBatchNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unpostDocumentBatch(string|int $idOrBatchNo, mixed $data = []): mixed
    {
        $path = '/api/DocumentBatch/' . rawurlencode((string)$idOrBatchNo) . '/unpost';
        return $this->post($path, $data, []);
    }

}
