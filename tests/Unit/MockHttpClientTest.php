<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit;

use Iplicit\Exception\NetworkException;
use Iplicit\Http\MockHttpClient;
use Iplicit\Http\Request;
use Iplicit\Http\Response;
use PHPUnit\Framework\TestCase;

class MockHttpClientTest extends TestCase
{
    public function testQueueAndRecordRequests(): void
    {
        $mock = new MockHttpClient();
        $mock->addJsonResponse(['status' => 'ok'], 200);
        $mock->addResponse(new Response(201, 'Created'));

        $this->assertSame(0, $mock->countRequests());
        $this->assertNull($mock->getLastRequest());

        $req1 = new Request('GET', 'https://api.iplicit.com/api/Product');
        $res1 = $mock->send($req1);

        $this->assertSame(200, $res1->getStatusCode());
        $this->assertSame(['status' => 'ok'], $res1->json());
        $this->assertSame(1, $mock->countRequests());
        $this->assertSame($req1, $mock->getLastRequest());

        $req2 = new Request('POST', 'https://api.iplicit.com/api/Product', [], ['code' => 'P1']);
        $res2 = $mock->send($req2);

        $this->assertSame(201, $res2->getStatusCode());
        $this->assertSame(2, $mock->countRequests());
    }

    public function testEmptyQueueThrowsNetworkException(): void
    {
        $mock = new MockHttpClient();
        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('MockHttpClient response queue is empty');

        $mock->send(new Request('GET', 'https://api.iplicit.com/api/Product'));
    }

    public function testQueuedException(): void
    {
        $mock = new MockHttpClient();
        $mock->addResponse(new NetworkException('Connection timed out'));

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Connection timed out');

        $mock->send(new Request('GET', 'https://api.iplicit.com/api/Product'));
    }

    public function testCustomHandler(): void
    {
        $mock = new MockHttpClient();
        $mock->setHandler(function (Request $request) {
            if ($request->getMethod() === 'GET') {
                return new Response(200, json_encode(['method' => 'GET']));
            }
            return new Response(200, json_encode(['method' => 'OTHER']));
        });

        $res1 = $mock->send(new Request('GET', 'https://api.iplicit.com/test'));
        $this->assertSame(['method' => 'GET'], $res1->json());

        $res2 = $mock->send(new Request('POST', 'https://api.iplicit.com/test'));
        $this->assertSame(['method' => 'OTHER'], $res2->json());
    }
}
