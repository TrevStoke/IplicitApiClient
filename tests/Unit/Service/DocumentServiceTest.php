<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit\Service;

use Iplicit\Http\MockHttpClient;
use Iplicit\IplicitClient;
use Iplicit\Service\DocumentService;
use PHPUnit\Framework\TestCase;

class DocumentServiceTest extends TestCase
{
    private MockHttpClient $mockHttp;
    private DocumentService $service;

    protected function setUp(): void
    {
        $this->mockHttp = new MockHttpClient();
        $client = new IplicitClient(sessionToken: 'test-token', httpClient: $this->mockHttp);
        $this->service = $client->document();
    }

    public function testGetDocumentsAndPagination(): void
    {
        $this->mockHttp->addJsonResponse([['id' => 'doc-1', 'docNo' => 'DOC001']]);

        $res = $this->service->getDocuments(['DocType' => 'PI', 'Take' => 10]);

        $this->assertCount(1, $res);
        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('GET', $req->getMethod());
        $this->assertStringContainsString('/api/Document', $req->getUrl());
        $this->assertStringContainsString('DocType=PI', $req->getFullUrl());
    }

    public function testFetchAllDocumentsGenerator(): void
    {
        $this->mockHttp->addJsonResponse([['id' => '1'], ['id' => '2']]);
        $this->mockHttp->addJsonResponse([]);

        $items = [];
        foreach ($this->service->fetchAllDocuments([], 2) as $doc) {
            $items[] = $doc;
        }

        $this->assertCount(2, $items);
    }

    public function testAddDocumentDetailLine(): void
    {
        $this->mockHttp->addJsonResponse(['status' => 'Created'], 201);

        $lineData = ['account' => 'EXP001', 'net' => 150.0];
        $res = $this->service->addDocumentDetailLine('DOC001', $lineData, ['docClass' => 'PI']);

        $this->assertSame(['status' => 'Created'], $res);
        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('POST', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/Document/DOC001/detail', $req->getUrl());
        $this->assertStringContainsString('docClass=PI', $req->getFullUrl());
        $this->assertSame($lineData, $req->getBody());
    }

    public function testDisputeDocument(): void
    {
        $this->mockHttp->addJsonResponse(['status' => 'Disputed'], 200);

        $disputeData = ['reason' => 'Incorrect quantity billed'];
        $res = $this->service->disputeDocument('DOC001', $disputeData);

        $this->assertSame(['status' => 'Disputed'], $res);
        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('POST', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/Document/DOC001/dispute', $req->getUrl());
    }
}
