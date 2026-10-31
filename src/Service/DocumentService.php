<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Document API operations.
 */
class DocumentService extends AbstractService
{
    /**
     * Get a list of documents with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getDocuments(array $query = []): mixed
    {
        $path = '/api/Document';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of documents with a search filter.
     */
    public function paginateDocuments(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/Document';
        return $this->paginate($path, $query, 'Skip', 'Take', $pageSize);
    }

    /**
     * Yields all items for Get a list of documents with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllDocuments(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateDocuments($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a document
     *
     * Note: docClass parameter is no longer used
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createDocument(mixed $data = []): mixed
    {
        $path = '/api/Document';
        return $this->post($path, $data, []);
    }

    /**
     * Get a document by id or document number
     *
     * @param string|int $idOrDocNo
     * @param array $query Query parameters
     * @return mixed
     */
    public function getDocument(string|int $idOrDocNo, array $query = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$idOrDocNo);
        return $this->get($path, $query);
    }

    /**
     * Update a document
     *
     * @param string|int $idOrDocNo
     * @param mixed $data Request payload or body data
     * @param array $query Query parameters
     * @return mixed
     */
    public function updateDocument(string|int $idOrDocNo, mixed $data = [], array $query = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$idOrDocNo);
        return $this->patch($path, $data, $query);
    }

    /**
     * Get a document by the <a href="https://docs.iplicit.com/dev/guide/identifiers/index.html">internal reference</a>
     *
     * @param string|int $intRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getDocumentByIntRef(string|int $intRef, array $query = []): mixed
    {
        $path = '/api/Document/intref/' . rawurlencode((string)$intRef);
        return $this->get($path, $query);
    }

    /**
     * Get document match summary (amount only).
     *
     * @param string|int $idOrDocNo
     * @param array $query Query parameters
     * @return mixed
     */
    public function getDocumentMatchSummary(string|int $idOrDocNo, array $query = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$idOrDocNo) . '/match/outstanding/amount';
        return $this->get($path, $query);
    }

    /**
     * Get document match details.
     *
     * @param string|int $idOrDocNo
     * @param array $query Query parameters
     * @return mixed
     */
    public function getDocumentMatchDetails(string|int $idOrDocNo, array $query = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$idOrDocNo) . '/match/details';
        return $this->get($path, $query);
    }

    /**
     * Get document match reverse details.
     *
     * @param string|int $idOrDocNo
     * @param array $query Query parameters
     * @return mixed
     */
    public function getDocumentMatchReverse(string|int $idOrDocNo, array $query = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$idOrDocNo) . '/match/reverse';
        return $this->get($path, $query);
    }

    /**
     * Submit the document for authorisation and posting.
     *
     * @param string|int $docId
     * @param mixed $data Request payload or body data
     * @param array $query Query parameters
     * @return mixed
     */
    public function submitDocument(string|int $docId, mixed $data = [], array $query = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$docId) . '/submit';
        return $this->post($path, $data, $query);
    }

    /**
     * Abandon a draft document.
     *
     * @param string|int $idOrDocNo
     * @param mixed $data Request payload or body data
     * @param array $query Query parameters
     * @return mixed
     */
    public function abandonDocument(string|int $idOrDocNo, mixed $data = [], array $query = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$idOrDocNo) . '/abandon';
        return $this->post($path, $data, $query);
    }

    /**
     * Unauthorise an approved document (non GL documents only).
     *
     * @param string|int $idOrDocNo
     * @param mixed $data Request payload or body data
     * @param array $query Query parameters
     * @return mixed
     */
    public function unauthoriseDocument(string|int $idOrDocNo, mixed $data = [], array $query = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$idOrDocNo) . '/unauthorise';
        return $this->post($path, $data, $query);
    }

    /**
     * Unpost a document
     *
     * @param string|int $idOrDocNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unpostDocument(string|int $idOrDocNo, mixed $data = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$idOrDocNo) . '/unpost';
        return $this->post($path, $data, []);
    }

    /**
     * Update a document detail line
     *
     * Use PATCH /doc/{docRef}/detail/{docDetailRef} instead
     *
     * @param string|int $docDetailId
     * @param mixed $data Request payload or body data
     * @param array $query Query parameters
     * @return mixed
     */
    public function updateDocumentDetailLineObsolete(string|int $docDetailId, mixed $data = [], array $query = []): mixed
    {
        $path = '/api/Document/detail/' . rawurlencode((string)$docDetailId);
        return $this->patch($path, $data, $query);
    }

    /**
     * Add a document detail line
     *
     * @param string|int $docRef
     * @param mixed $data Request payload or body data
     * @param array $query Query parameters
     * @return mixed
     */
    public function addDocumentDetailLine(string|int $docRef, mixed $data = [], array $query = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$docRef) . '/detail';
        return $this->post($path, $data, $query);
    }

    /**
     * Update a document detail line
     *
     * @param string|int $docRef
     * @param string|int $docDetailRef
     * @param mixed $data Request payload or body data
     * @param array $query Query parameters
     * @return mixed
     */
    public function updateDocumentDetailLine(string|int $docRef, string|int $docDetailRef, mixed $data = [], array $query = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$docRef) . '/detail/' . rawurlencode((string)$docDetailRef);
        return $this->patch($path, $data, $query);
    }

    /**
     * Delete a document detail line
     *
     * @param string|int $docRef
     * @param string|int $docDetailRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function deleteDocumentDetailLine(string|int $docRef, string|int $docDetailRef, array $query = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$docRef) . '/detail/' . rawurlencode((string)$docDetailRef);
        return $this->delete($path, null, $query);
    }

    /**
     * Convert a document to another type
     *
     * The document must be authorised / posted. The created document will fully match the source document.
     *
     * @param string|int $fromDocId
     * @param string|int $toDocType
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function convertDocumentType(string|int $fromDocId, string|int $toDocType, mixed $data = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$fromDocId) . '/convert/' . rawurlencode((string)$toDocType);
        return $this->post($path, $data, []);
    }

    /**
     * Add an allocation to the document
     *
     * @param string|int $docIdOrCode
     * @param mixed $data Request payload or body data
     * @param array $query Query parameters
     * @return mixed
     */
    public function addAllocationToDocument(string|int $docIdOrCode, mixed $data = [], array $query = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$docIdOrCode) . '/allocation';
        return $this->post($path, $data, $query);
    }

    /**
     * Delete a document allocation
     *
     * @param string|int $idOrDocNo
     * @param string|int $allocationId
     * @return mixed
     */
    public function deleteDocumentAllocation(string|int $idOrDocNo, string|int $allocationId): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$idOrDocNo) . '/allocations/' . rawurlencode((string)$allocationId);
        return $this->delete($path, null, []);
    }

    /**
     * Dispute a document
     *
     * @param string|int $idOrDocNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function disputeDocument(string|int $idOrDocNo, mixed $data = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$idOrDocNo) . '/dispute';
        return $this->post($path, $data, []);
    }

    /**
     * Amend a document
     *
     * @param string|int $idOrDocNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function amendDocument(string|int $idOrDocNo, mixed $data = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$idOrDocNo) . '/amend';
        return $this->patch($path, $data, []);
    }

    /**
     * Reverse a document
     *
     * @param string|int $idOrDocNo
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function reverseDocument(string|int $idOrDocNo, mixed $data = []): mixed
    {
        $path = '/api/Document/' . rawurlencode((string)$idOrDocNo) . '/reverse';
        return $this->post($path, $data, []);
    }

}
