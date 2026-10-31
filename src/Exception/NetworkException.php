<?php

declare(strict_types=1);

namespace Iplicit\Exception;

/**
 * Exception thrown when network connectivity or transport fails (e.g. cURL error, timeout).
 */
class NetworkException extends \RuntimeException implements IplicitException
{
}
