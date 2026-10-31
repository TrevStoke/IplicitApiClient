<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit;

use Iplicit\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testSuccessAndJsonParsing(): void
    {
        $json = json_encode(['id' => '123', 'status' => 'active']);
        $response = new Response(200, $json, ['Content-Type' => 'application/json', 'X-RateLimit' => '100']);

        $this->assertTrue($response->isSuccess());
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($json, $response->getRawBody());
        $this->assertSame(['id' => '123', 'status' => 'active'], $response->json());
        $this->assertSame('application/json', $response->getHeader('content-type'));
        $this->assertSame('100', $response->getHeader('x-ratelimit'));
        $this->assertNull($response->getHeader('Non-Existent'));
    }

    public function testEmptyOrNullResponse(): void
    {
        $response = new Response(204, '');
        $this->assertTrue($response->isSuccess());
        $this->assertNull($response->json());

        $nullResponse = new Response(200, 'null');
        $this->assertNull($nullResponse->json());
    }

    public function testNonJsonResponseReturnsRawString(): void
    {
        $response = new Response(200, 'Plain text content');
        $this->assertSame('Plain text content', $response->json());
    }
}
