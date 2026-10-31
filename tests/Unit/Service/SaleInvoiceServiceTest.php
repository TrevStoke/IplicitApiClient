<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit\Service;

use Iplicit\Http\MockHttpClient;
use Iplicit\IplicitClient;
use Iplicit\Pagination\Paginator;
use Iplicit\Service\SaleInvoiceService;
use PHPUnit\Framework\TestCase;

class SaleInvoiceServiceTest extends TestCase
{
    private MockHttpClient $mockHttp;
    private IplicitClient $client;
    private SaleInvoiceService $service;

    protected function setUp(): void
    {
        $this->mockHttp = new MockHttpClient();
        $this->client = new IplicitClient(sessionToken: 'test-token', httpClient: $this->mockHttp);
        $this->service = $this->client->saleInvoice();
    }

    public function testGetSaleInvoicesWithQueryFilters(): void
    {
        $mockInvoices = [
            ['id' => 'inv-1', 'docNo' => 'SINV001', 'amount' => 1500.5],
            ['id' => 'inv-2', 'docNo' => 'SINV002', 'amount' => 2400.75],
        ];
        $this->mockHttp->addJsonResponse($mockInvoices);

        $result = $this->service->getSaleInvoices(['Outstanding' => true, 'Take' => 10]);

        $this->assertSame($mockInvoices, $result);
        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('GET', $req->getMethod());
        $this->assertStringContainsString('/api/SaleInvoice', $req->getUrl());
        $this->assertStringContainsString('Outstanding=true', $req->getFullUrl());
        $this->assertStringContainsString('Take=10', $req->getFullUrl());
    }

    public function testPaginateSaleInvoices(): void
    {
        $paginator = $this->service->paginateSaleInvoices(['Outstanding' => true], pageSize: 25);
        $this->assertInstanceOf(Paginator::class, $paginator);
    }

    public function testFetchAllSaleInvoicesGenerator(): void
    {
        // Setup 2 pages of responses
        $page1 = [['id' => '1'], ['id' => '2']];
        $page2 = [['id' => '3']]; // last page (count < pageSize 2)

        $this->mockHttp->addJsonResponse($page1);
        $this->mockHttp->addJsonResponse($page2);

        $generator = $this->service->fetchAllSaleInvoices([], pageSize: 2);
        $this->assertInstanceOf(\Generator::class, $generator);

        $items = [];
        foreach ($generator as $item) {
            $items[] = $item;
        }

        $this->assertCount(3, $items);
        $this->assertSame([['id' => '1'], ['id' => '2'], ['id' => '3']], $items);

        $recorded = $this->mockHttp->getRecordedRequests();
        $this->assertCount(2, $recorded);
        $this->assertStringContainsString('Skip=0', $recorded[0]->getFullUrl());
        $this->assertStringContainsString('Take=2', $recorded[0]->getFullUrl());
        $this->assertStringContainsString('Skip=2', $recorded[1]->getFullUrl());
        $this->assertStringContainsString('Take=2', $recorded[1]->getFullUrl());
    }

    public function testCreateSaleInvoice(): void
    {
        $this->mockHttp->addJsonResponse(['id' => 'inv-new-id'], 200);

        $payload = [
            'contactAccount' => 'CUST001',
            'docDate' => '2026-10-31',
            'lines' => [
                ['description' => 'Consulting Services', 'unitPrice' => 500.0, 'quantity' => 2],
            ],
        ];

        $res = $this->service->createSaleInvoice($payload);

        $this->assertSame(['id' => 'inv-new-id'], $res);
        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('POST', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/SaleInvoice', $req->getUrl());
        $this->assertSame($payload, $req->getBody());
    }

    public function testGetSaleInvoice(): void
    {
        $this->mockHttp->addJsonResponse(['id' => 'inv-123', 'docNo' => 'SINV123'], 200);

        $res = $this->service->getSaleInvoice('inv-123', ['include' => 'lines']);

        $this->assertSame(['id' => 'inv-123', 'docNo' => 'SINV123'], $res);
        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('GET', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/SaleInvoice/inv-123', $req->getUrl());
        $this->assertStringContainsString('include=lines', $req->getFullUrl());
    }

    public function testSubmitSaleInvoice(): void
    {
        $this->mockHttp->addJsonResponse(['status' => 'Submitted'], 200);

        $res = $this->service->submitSaleInvoice('inv-123');

        $this->assertSame(['status' => 'Submitted'], $res);
        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('POST', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/SaleInvoice/inv-123/submit', $req->getUrl());
    }

    public function testUpdateSaleInvoice(): void
    {
        $this->mockHttp->addJsonResponse(['id' => 'inv-123', 'status' => 'Updated'], 200);

        $patchData = ['description' => 'Updated invoice description'];
        $res = $this->service->updateSaleInvoice('inv-123', $patchData);

        $this->assertSame(['id' => 'inv-123', 'status' => 'Updated'], $res);
        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('PATCH', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/SaleInvoice/inv-123', $req->getUrl());
        $this->assertSame($patchData, $req->getBody());
    }
}
