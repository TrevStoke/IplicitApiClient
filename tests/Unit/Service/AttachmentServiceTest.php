<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit\Service;

use Iplicit\Http\MockHttpClient;
use Iplicit\IplicitClient;
use Iplicit\Service\AttachmentService;
use PHPUnit\Framework\TestCase;

class AttachmentServiceTest extends TestCase
{
    private MockHttpClient $mockHttp;
    private AttachmentService $service;

    protected function setUp(): void
    {
        $this->mockHttp = new MockHttpClient();
        $client = new IplicitClient(sessionToken: 'test-token', httpClient: $this->mockHttp);
        $this->service = $client->attachment();
    }

    public function testGetAttachmentGroups(): void
    {
        $this->mockHttp->addJsonResponse([['id' => 'grp-1', 'description' => 'Invoices']]);

        $res = $this->service->getAttachmentGroups('INV_ATTRIB');
        $this->assertCount(1, $res);

        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('GET', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/Attribute/INV_ATTRIB/AttachmentGroup', $req->getUrl());
    }

    public function testGetAttachmentFile(): void
    {
        $this->mockHttp->addJsonResponse(['fileContentBase64' => 'aGVsbG8=']);

        $res = $this->service->getAttachmentFile('att-123');
        $this->assertArrayHasKey('fileContentBase64', $res);

        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('GET', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/Attachment/att-123/File', $req->getUrl());
    }
}
