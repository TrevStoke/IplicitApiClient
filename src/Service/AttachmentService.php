<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Attachment API operations.
 */
class AttachmentService extends AbstractService
{
    /**
     * Get a list of attachment groups defined on a specified attribute.
     *
     * @param string|int $attributeRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getAttachmentGroups(string|int $attributeRef, array $query = []): mixed
    {
        $path = '/api/Attribute/' . rawurlencode((string)$attributeRef) . '/AttachmentGroup';
        return $this->get($path, $query);
    }

    /**
     * Get a list of attachments in an attachment group on a specified source item with a search filter.
     *
     * @param string|int $attributeRef
     * @param string|int $sourceId
     * @param string|int $attachmentGroupId
     * @param array $query Query parameters
     * @return mixed
     */
    public function getAttachmentsInGroup(string|int $attributeRef, string|int $sourceId, string|int $attachmentGroupId, array $query = []): mixed
    {
        $path = '/api/Attachment/search/' . rawurlencode((string)$attributeRef) . '/' . rawurlencode((string)$sourceId) . '/AttachmentGroup/' . rawurlencode((string)$attachmentGroupId);
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of attachments in an attachment group on a specified source item with a search filter..
     */
    public function paginateAttachmentsInGroup(string|int $attributeRef, string|int $sourceId, string|int $attachmentGroupId, array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/Attachment/search/' . rawurlencode((string)$attributeRef) . '/' . rawurlencode((string)$sourceId) . '/AttachmentGroup/' . rawurlencode((string)$attachmentGroupId);
        return $this->paginate($path, $query, 'skip', 'take', $pageSize);
    }

    /**
     * Yields all items for Get a list of attachments in an attachment group on a specified source item with a search filter. across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllAttachmentsInGroup(string|int $attributeRef, string|int $sourceId, string|int $attachmentGroupId, array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateAttachmentsInGroup($attributeRef, $sourceId, $attachmentGroupId, $query, $pageSize);
        yield from $paginator;
    }

    /**
     * Get attachment details
     *
     * @param string|int $attachmentId
     * @param array $query Query parameters
     * @return mixed
     */
    public function getAttachmentDetails(string|int $attachmentId, array $query = []): mixed
    {
        $path = '/api/Attachment/' . rawurlencode((string)$attachmentId) . '/Details';
        return $this->get($path, $query);
    }

    /**
     * Get attachment file
     *
     * @param string|int $attachmentId
     * @param array $query Query parameters
     * @return mixed
     */
    public function getAttachmentFile(string|int $attachmentId, array $query = []): mixed
    {
        $path = '/api/Attachment/' . rawurlencode((string)$attachmentId) . '/File';
        return $this->get($path, $query);
    }

    /**
     * Create a new attachment on a specified source item
     *
     * @param string|int $attributeRef
     * @param string|int $sourceId
     * @param string|int $attachmentGroupId
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createAttachment(string|int $attributeRef, string|int $sourceId, string|int $attachmentGroupId, mixed $data = []): mixed
    {
        $path = '/api/Attachment/create/' . rawurlencode((string)$attributeRef) . '/' . rawurlencode((string)$sourceId) . '/AttachmentGroup/' . rawurlencode((string)$attachmentGroupId);
        return $this->post($path, $data, []);
    }

    /**
     * Update attachment details
     *
     * @param string|int $attachmentId
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateAttachmentDetails(string|int $attachmentId, mixed $data = []): mixed
    {
        $path = '/api/Attachment/' . rawurlencode((string)$attachmentId);
        return $this->patch($path, $data, []);
    }

    /**
     * Delete an attachment
     *
     * @param string|int $attachmentId
     * @return mixed
     */
    public function deleteAttachment(string|int $attachmentId): mixed
    {
        $path = '/api/Attachment/' . rawurlencode((string)$attachmentId);
        return $this->delete($path, null, []);
    }

}
