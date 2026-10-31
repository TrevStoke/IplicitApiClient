<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Enquiry API operations.
 */
class EnquiryService extends AbstractService
{
    /**
     * Run an enquiry that returns a list
     *
     * @param string|int $id
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function runEnquiry(string|int $id, mixed $data = []): mixed
    {
        $path = '/api/Enquiry/run/' . rawurlencode((string)$id);
        return $this->post($path, $data, []);
    }

    /**
     * Run an enquiry that returns a list
     *
     * @param string|int $id
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function runEnquiryToList(string|int $id, mixed $data = []): mixed
    {
        $path = '/api/Enquiry/run/' . rawurlencode((string)$id) . '/list';
        return $this->post($path, $data, []);
    }

    /**
     * Run an enquiry that returns a table
     *
     * @param string|int $id
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function runEnquiryToTable(string|int $id, mixed $data = []): mixed
    {
        $path = '/api/Enquiry/run/' . rawurlencode((string)$id) . '/table';
        return $this->post($path, $data, []);
    }

}
