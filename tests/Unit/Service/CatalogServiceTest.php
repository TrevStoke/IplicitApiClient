<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit\Service;

use Iplicit\Http\MockHttpClient;
use Iplicit\IplicitClient;
use Iplicit\Service\CatalogService;
use PHPUnit\Framework\TestCase;

class CatalogServiceTest extends TestCase
{
    private MockHttpClient $mockHttp;
    private CatalogService $service;

    protected function setUp(): void
    {
        $this->mockHttp = new MockHttpClient();
        $client = new IplicitClient(sessionToken: 'test-token', httpClient: $this->mockHttp);
        $this->service = $client->catalog();
    }

    public function testGetAllCatalogs(): void
    {
        $this->mockHttp->addJsonResponse([['name' => 'PaymentTerms'], ['name' => 'TaxCodes']]);

        $res = $this->service->getAllCatalogs();
        $this->assertCount(2, $res);
        $this->assertSame('https://api.iplicit.com/api/Catalog', $this->mockHttp->getLastRequest()->getUrl());
    }

    public function testAddCatalogItem(): void
    {
        $this->mockHttp->addJsonResponse(['status' => 'Created'], 201);

        $itemData = ['code' => 'NET30', 'description' => 'Payment within 30 days'];
        $res = $this->service->addCatalogItem('PaymentTerms', $itemData);

        $this->assertSame(['status' => 'Created'], $res);
        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('PUT', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/Catalog/PaymentTerms', $req->getUrl());
        $this->assertSame($itemData, $req->getBody());
    }

    public function testDeleteCatalogItem(): void
    {
        $this->mockHttp->addJsonResponse(['status' => 'Deleted']);

        $res = $this->service->deleteCatalogItem('PaymentTerms', 'NET30');
        $this->assertSame(['status' => 'Deleted'], $res);

        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('DELETE', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/Catalog/PaymentTerms/NET30', $req->getUrl());
    }
}
