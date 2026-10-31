<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit DocumentAttachment API operations.
 */
class DocumentAttachmentService extends AbstractService
{
    /**
     * Get a list of attachment groups defined on a specified document type.
     *
     * @param string|int $docTypeRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getDocumentAttachmentGroups(string|int $docTypeRef, array $query = []): mixed
    {
        $path = '/api/DocumentType/' . rawurlencode((string)$docTypeRef) . '/AttachmentGroup';
        return $this->get($path, $query);
    }

    /**
     * Get a list of attachments in an attachment group on a specified document with a search filter.
     *
     * @param string|int $docRef
     * @param string|int $attachmentGroupId
     * @param array $query Query parameters
     * @return mixed
     */
    public function getDocumentAttachmentsInGroup(string|int $docRef, string|int $attachmentGroupId, array $query = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$docRef) . '/AttachmentGroup/' . rawurlencode((string)$attachmentGroupId) . '/Attachment';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of attachments in an attachment group on a specified document with a search filter..
     */
    public function paginateDocumentAttachmentsInGroup(string|int $docRef, string|int $attachmentGroupId, array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/Document/' . rawurlencode((string)$docRef) . '/AttachmentGroup/' . rawurlencode((string)$attachmentGroupId) . '/Attachment';
        return $this->paginate($path, $query, 'skip', 'take', $pageSize);
    }

    /**
     * Yields all items for Get a list of attachments in an attachment group on a specified document with a search filter. across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllDocumentAttachmentsInGroup(string|int $docRef, string|int $attachmentGroupId, array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateDocumentAttachmentsInGroup($docRef, $attachmentGroupId, $query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a new attachment on a sepcified document.
     *
     * @param string|int $docRef
     * @param string|int $attachmentGroupId
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createDocumentAttachment(string|int $docRef, string|int $attachmentGroupId, mixed $data = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$docRef) . '/AttachmentGroup/' . rawurlencode((string)$attachmentGroupId) . '/Attachment';
        return $this->post($path, $data, []);
    }

}
