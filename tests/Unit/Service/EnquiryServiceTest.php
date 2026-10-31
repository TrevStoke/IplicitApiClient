<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit\Service;

use Iplicit\Http\MockHttpClient;
use Iplicit\IplicitClient;
use Iplicit\Service\EnquiryService;
use PHPUnit\Framework\TestCase;

class EnquiryServiceTest extends TestCase
{
    private MockHttpClient $mockHttp;
    private EnquiryService $service;

    protected function setUp(): void
    {
        $this->mockHttp = new MockHttpClient();
        $client = new IplicitClient(sessionToken: 'test-token', httpClient: $this->mockHttp);
        $this->service = $client->enquiry();
    }

    public function testRunEnquiry(): void
    {
        $this->mockHttp->addJsonResponse(['results' => [['col1' => 'val1']]]);

        $params = ['criteria' => ['period' => '2026-10']];
        $res = $this->service->runEnquiry('enq-id-123', $params);

        $this->assertSame(['results' => [['col1' => 'val1']]], $res);
        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('POST', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/Enquiry/run/enq-id-123', $req->getUrl());
        $this->assertSame($params, $req->getBody());
    }

    public function testRunEnquiryToList(): void
    {
        $this->mockHttp->addJsonResponse([['id' => 1, 'name' => 'Row 1']]);

        $res = $this->service->runEnquiryToList('enq-id-123', ['filter' => 'test']);
        $this->assertCount(1, $res);

        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('POST', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/Enquiry/run/enq-id-123/list', $req->getUrl());
    }

    public function testRunEnquiryToTable(): void
    {
        $this->mockHttp->addJsonResponse(['columns' => ['ColA', 'ColB'], 'rows' => [['1', '2']]]);

        $res = $this->service->runEnquiryToTable('enq-id-123', []);
        $this->assertArrayHasKey('columns', $res);

        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('POST', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/Enquiry/run/enq-id-123/table', $req->getUrl());
    }
}
