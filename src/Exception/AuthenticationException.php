<?php

declare(strict_types=1);

namespace Iplicit\Exception;

/**
 * Exception thrown when authentication fails or session token is missing/expired.
 */
class AuthenticationException extends ApiException
{
}
