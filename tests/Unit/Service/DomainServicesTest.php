<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit\Service;

use Iplicit\Http\MockHttpClient;
use Iplicit\IplicitClient;
use Iplicit\Service\BankTransactionService;
use Iplicit\Service\BatchPaymentService;
use Iplicit\Service\BudgetingService;
use Iplicit\Service\ContactGroupService;
use Iplicit\Service\ContactService;
use Iplicit\Service\CostCentreService;
use Iplicit\Service\DepartmentService;
use Iplicit\Service\LegalEntityService;
use Iplicit\Service\PaymentService;
use Iplicit\Service\ProductPurchasePriceService;
use Iplicit\Service\ProductSalePriceService;
use Iplicit\Service\ProductService;
use Iplicit\Service\ProfileService;
use Iplicit\Service\ProjectService;
use Iplicit\Service\PurchaseInvoiceService;
use Iplicit\Service\PurchaseOrderService;
use Iplicit\Service\ReceiptService;
use Iplicit\Service\ResourceService;
use Iplicit\Service\SaleOrderService;
use Iplicit\Service\SaleQuoteService;
use Iplicit\Service\SessionService;
use PHPUnit\Framework\TestCase;

class DomainServicesTest extends TestCase
{
    private MockHttpClient $mockHttp;
    private IplicitClient $client;

    protected function setUp(): void
    {
        $this->mockHttp = new MockHttpClient();
        $this->client = new IplicitClient(sessionToken: 'test-token', httpClient: $this->mockHttp);
    }

    public function testBankTransactionService(): void
    {
        $service = $this->client->bankTransaction();
        $this->mockHttp->addJsonResponse(['id' => 'bt-1']);

        $res = $service->getBankTransaction('bt-1');
        $this->assertSame(['id' => 'bt-1'], $res);
        $this->assertSame('https://api.iplicit.com/api/BankTransaction/bt-1', $this->mockHttp->getLastRequest()->getUrl());

        $this->mockHttp->addJsonResponse(['status' => 'locked']);
        $service->lockBankTransaction('bt-1');
        $this->assertSame('POST', $this->mockHttp->getLastRequest()->getMethod());
    }

    public function testBatchPaymentService(): void
    {
        $service = $this->client->batchPayment();
        $this->mockHttp->addJsonResponse([['id' => 'bp-1']]);

        $res = $service->getBatchPayments(['take' => 10, 'skip' => 0]);
        $this->assertCount(1, $res);

        $this->mockHttp->addJsonResponse(['status' => 'processed']);
        $service->processBatchPayment('bp-1');
        $this->assertSame('https://api.iplicit.com/api/BatchPayment/bp-1/process', $this->mockHttp->getLastRequest()->getUrl());
    }

    public function testBudgetingService(): void
    {
        $service = $this->client->budgeting();
        $this->mockHttp->addJsonResponse([['id' => 'bg-1', 'currency' => 'GBP']]);

        $res = $service->getBudgetings(['currency' => 'GBP']);
        $this->assertCount(1, $res);
        $this->assertSame('https://api.iplicit.com/api/Budgeting?currency=GBP', $this->mockHttp->getLastRequest()->getFullUrl());
    }

    public function testContactService(): void
    {
        $service = $this->client->contact();
        $this->mockHttp->addJsonResponse(['id' => 'c-1', 'name' => 'John']);

        $res = $service->getContact('c-1');
        $this->assertSame('John', $res['name']);
        $this->assertSame('https://api.iplicit.com/api/Contact/c-1', $this->mockHttp->getLastRequest()->getUrl());
    }

    public function testContactGroupService(): void
    {
        $service = $this->client->contactGroup();
        $this->mockHttp->addJsonResponse(['id' => 'cg-1']);

        $res = $service->getContactGroup('cg-1');
        $this->assertSame(['id' => 'cg-1'], $res);
        $this->assertSame('https://api.iplicit.com/api/ContactGroup/cg-1', $this->mockHttp->getLastRequest()->getUrl());
    }

    public function testCostCentreService(): void
    {
        $service = $this->client->costCentre();
        $this->mockHttp->addJsonResponse([['code' => 'CC1']]);

        $res = $service->getCostCentres(['take' => 50, 'skip' => 0]);
        $this->assertCount(1, $res);
        $this->assertSame('https://api.iplicit.com/api/CostCentre?take=50&skip=0', $this->mockHttp->getLastRequest()->getFullUrl());
    }

    public function testDepartmentService(): void
    {
        $service = $this->client->department();
        $this->mockHttp->addJsonResponse([['code' => 'DEP1']]);

        $res = $service->getDepartments(['take' => 20]);
        $this->assertCount(1, $res);
    }

    public function testLegalEntityService(): void
    {
        $service = $this->client->legalEntity();
        $this->mockHttp->addJsonResponse([['id' => 'le-1', 'code' => 'CORP']]);

        $res = $service->getAllLegalEntities(['take' => 10]);
        $this->assertCount(1, $res);

        $this->mockHttp->addJsonResponse([['id' => 'le-1']]);
        $this->mockHttp->addJsonResponse([]);
        $all = [];
        foreach ($service->fetchAllAllLegalEntities([], 5) as $le) {
            $all[] = $le;
        }
        $this->assertCount(1, $all);
    }

    public function testPaymentService(): void
    {
        $service = $this->client->payment();
        $this->mockHttp->addJsonResponse([['docNo' => 'PAY001']]);

        $res = $service->getPayments(['DocNo' => 'PAY001']);
        $this->assertCount(1, $res);
    }

    public function testProductService(): void
    {
        $service = $this->client->product();
        $this->mockHttp->addJsonResponse([['code' => 'PROD01']]);

        $res = $service->getProducts(['isSale' => true]);
        $this->assertCount(1, $res);
    }

    public function testProductPrices(): void
    {
        $purchaseService = $this->client->productPurchasePrice();
        $saleService = $this->client->productSalePrice();

        $this->mockHttp->addJsonResponse([['price' => 10.0]]);
        $purchasePrices = $purchaseService->getProductPurchasePrices('PROD01');
        $this->assertCount(1, $purchasePrices);

        $this->mockHttp->addJsonResponse([['price' => 20.0]]);
        $salePrices = $saleService->getProductSalePrices('PROD01');
        $this->assertCount(1, $salePrices);
    }

    public function testProfileService(): void
    {
        $service = $this->client->profile();
        $this->mockHttp->addJsonResponse(['userId' => 'user-123', 'username' => 'admin']);

        $res = $service->getProfile();
        $this->assertSame('user-123', $res['userId']);
    }

    public function testProjectService(): void
    {
        $service = $this->client->project();
        $this->mockHttp->addJsonResponse([['code' => 'PRJ01']]);

        $res = $service->getProjects(['IsActive' => true]);
        $this->assertCount(1, $res);
    }

    public function testPurchaseInvoiceAndOrder(): void
    {
        $piService = $this->client->purchaseInvoice();
        $poService = $this->client->purchaseOrder();

        $this->mockHttp->addJsonResponse(['id' => 'pi-1']);
        $pi = $piService->getPurchaseInvoice('pi-1');
        $this->assertSame(['id' => 'pi-1'], $pi);

        $this->mockHttp->addJsonResponse(['id' => 'po-1']);
        $po = $poService->getPurchaseOrder('po-1');
        $this->assertSame(['id' => 'po-1'], $po);
    }

    public function testReceiptService(): void
    {
        $service = $this->client->receipt();
        $this->mockHttp->addJsonResponse(['id' => 'rec-1']);

        $res = $service->getReceipt('rec-1');
        $this->assertSame(['id' => 'rec-1'], $res);
    }

    public function testResourceService(): void
    {
        $service = $this->client->resource();
        $this->mockHttp->addJsonResponse(['id' => 'res-1', 'name' => 'Resource A']);

        $res = $service->getResource('res-1');
        $this->assertSame('Resource A', $res['name']);
    }

    public function testSaleOrderAndQuote(): void
    {
        $soService = $this->client->saleOrder();
        $sqService = $this->client->saleQuote();

        $this->mockHttp->addJsonResponse(['id' => 'so-1']);
        $so = $soService->getSaleOrder('so-1');
        $this->assertSame(['id' => 'so-1'], $so);

        $this->mockHttp->addJsonResponse(['id' => 'sq-1']);
        $sq = $sqService->getSaleQuote('sq-1');
        $this->assertSame(['id' => 'sq-1'], $sq);
    }

    public function testSessionService(): void
    {
        $service = $this->client->session();
        $this->mockHttp->addJsonResponse('session-xyz');

        $res = $service->createSessionAPIKey(['apiKey' => 'test-key']);
        $this->assertSame('session-xyz', $res);

        $this->mockHttp->addJsonResponse(['status' => 'closed']);
        $closeRes = $service->closeSession();
        $this->assertSame(['status' => 'closed'], $closeRes);
    }
}
