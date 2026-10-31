<?php

declare(strict_types=1);

namespace Iplicit\Exception;

/**
 * Exception thrown when a 400 Bad Request or 422 Unprocessable Entity error is returned.
 */
class ValidationException extends ApiException
{
}
