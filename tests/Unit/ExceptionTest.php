<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit;

use Iplicit\Exception\ApiException;
use Iplicit\Exception\AuthenticationException;
use Iplicit\Exception\IplicitException;
use Iplicit\Exception\NetworkException;
use Iplicit\Exception\NotFoundException;
use Iplicit\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    public function testApiExceptionDetails(): void
    {
        $headers = ['X-Error' => 'test'];
        $body = ['error' => 'Bad Request', 'code' => 400];
        $e = new ApiException('Custom error message', 400, $body, $headers);

        $this->assertInstanceOf(IplicitException::class, $e);
        $this->assertSame('Custom error message', $e->getMessage());
        $this->assertSame(400, $e->getStatusCode());
        $this->assertSame($body, $e->getResponseBody());
        $this->assertSame($headers, $e->getResponseHeaders());
    }

    public function testAuthenticationExceptionInheritance(): void
    {
        $e = new AuthenticationException('Unauthorized', 401);
        $this->assertInstanceOf(ApiException::class, $e);
        $this->assertInstanceOf(IplicitException::class, $e);
    }

    public function testValidationExceptionInheritance(): void
    {
        $e = new ValidationException('Validation failed', 422);
        $this->assertInstanceOf(ApiException::class, $e);
        $this->assertInstanceOf(IplicitException::class, $e);
    }

    public function testNotFoundExceptionInheritance(): void
    {
        $e = new NotFoundException('Not found', 404);
        $this->assertInstanceOf(ApiException::class, $e);
        $this->assertInstanceOf(IplicitException::class, $e);
    }

    public function testNetworkExceptionInheritance(): void
    {
        $e = new NetworkException('DNS failure');
        $this->assertInstanceOf(\RuntimeException::class, $e);
        $this->assertInstanceOf(IplicitException::class, $e);
    }
}
