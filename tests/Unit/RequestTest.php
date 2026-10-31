<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit;

use Iplicit\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testGetFullUrlWithoutParams(): void
    {
        $request = new Request('GET', 'https://api.iplicit.com/api/Contact');
        $this->assertSame('https://api.iplicit.com/api/Contact', $request->getFullUrl());
        $this->assertSame('GET', $request->getMethod());
    }

    public function testGetFullUrlWithQueryParams(): void
    {
        $request = new Request('GET', 'https://api.iplicit.com/api/Document', [
            'take' => 50,
            'skip' => 100,
            'isActive' => true,
            'optionalNull' => null,
            'search' => 'ACME & Co',
        ]);

        $url = $request->getFullUrl();
        $this->assertStringContainsString('take=50', $url);
        $this->assertStringContainsString('skip=100', $url);
        $this->assertStringContainsString('isActive=true', $url);
        $this->assertStringNotContainsString('optionalNull', $url);
        $this->assertStringContainsString('search=ACME%20%26%20Co', $url);
    }

    public function testGetFullUrlAppendsToExistingQueryString(): void
    {
        $request = new Request('GET', 'https://api.iplicit.com/api/Document?existing=1', [
            'take' => 10,
        ]);
        $this->assertSame('https://api.iplicit.com/api/Document?existing=1&take=10', $request->getFullUrl());
    }

    public function testWithHeaderImmutability(): void
    {
        $request = new Request('POST', 'https://api.iplicit.com/api/Document', [], ['name' => 'Invoice'], ['X-Custom' => '123']);
        $newRequest = $request->withHeader('X-Another', '456');

        $this->assertNotSame($request, $newRequest);
        $this->assertArrayNotHasKey('X-Another', $request->getHeaders());
        $this->assertSame('456', $newRequest->getHeaders()['X-Another']);
        $this->assertSame('123', $newRequest->getHeaders()['X-Custom']);
    }
}
