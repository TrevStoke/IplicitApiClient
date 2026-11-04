<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit;

use Iplicit\Exception\NetworkException;
use Iplicit\Http\CurlHttpClient;
use Iplicit\Http\Request;
use PHPUnit\Framework\TestCase;

class CurlHttpClientTest extends TestCase
{
    public function testInvalidUrlThrowsNetworkException(): void
    {
        $client = new CurlHttpClient([], 1, false);
        $request = new Request('GET', 'http://127.0.0.1:1/api/test');

        $this->expectException(NetworkException::class);
        $client->send($request);
    }

    public function testInstantiationWithCustomOptions(): void
    {
        $client = new CurlHttpClient(
            defaultHeaders: ['X-Custom' => 'HeaderVal'],
            defaultTimeout: 10,
            verifySsl: false,
            followLocation: true,
            maxRedirects: 10
        );

        $this->assertInstanceOf(CurlHttpClient::class, $client);
        $this->assertTrue($client->isFollowLocation());
        $this->assertSame(10, $client->getMaxRedirects());

        $client->setFollowLocation(false);
        $this->assertFalse($client->isFollowLocation());

        $client->setMaxRedirects(3);
        $this->assertSame(3, $client->getMaxRedirects());
    }
}
