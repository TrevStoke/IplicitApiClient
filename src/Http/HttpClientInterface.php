<?php

declare(strict_types=1);

namespace Iplicit\Http;

use Iplicit\Exception\NetworkException;

interface HttpClientInterface
{
    /**
     * Sends an HTTP request and returns the response.
     *
     * @throws NetworkException on network or connection errors.
     */
    public function send(Request $request): Response;
}
