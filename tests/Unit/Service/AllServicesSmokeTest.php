<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit\Service;

use Iplicit\Http\MockHttpClient;
use Iplicit\Http\Request;
use Iplicit\Http\Response;
use Iplicit\IplicitClient;
use Iplicit\Service\AbstractService;
use PHPUnit\Framework\TestCase;

class AllServicesSmokeTest extends TestCase
{
    private MockHttpClient $mockHttp;
    private IplicitClient $client;

    protected function setUp(): void
    {
        $this->mockHttp = new MockHttpClient();
        $this->mockHttp->setHandler(function (Request $request) {
            return new Response(200, json_encode(['success' => true, 'url' => $request->getFullUrl()]));
        });
        $this->client = new IplicitClient(sessionToken: 'test-token', httpClient: $this->mockHttp);
    }

    public function testAll30ServicesCanBeAccessedAndInvoked(): void
    {
        $services = [
            'attachment' => \Iplicit\Service\AttachmentService::class,
            'bankTransaction' => \Iplicit\Service\BankTransactionService::class,
            'batchPayment' => \Iplicit\Service\BatchPaymentService::class,
            'budgeting' => \Iplicit\Service\BudgetingService::class,
            'catalog' => \Iplicit\Service\CatalogService::class,
            'contact' => \Iplicit\Service\ContactService::class,
            'contactAccount' => \Iplicit\Service\ContactAccountService::class,
            'contactGroup' => \Iplicit\Service\ContactGroupService::class,
            'costCentre' => \Iplicit\Service\CostCentreService::class,
            'department' => \Iplicit\Service\DepartmentService::class,
            'document' => \Iplicit\Service\DocumentService::class,
            'documentAttachment' => \Iplicit\Service\DocumentAttachmentService::class,
            'documentBatch' => \Iplicit\Service\DocumentBatchService::class,
            'documentType' => \Iplicit\Service\DocumentTypeService::class,
            'enquiry' => \Iplicit\Service\EnquiryService::class,
            'legalEntity' => \Iplicit\Service\LegalEntityService::class,
            'payment' => \Iplicit\Service\PaymentService::class,
            'product' => \Iplicit\Service\ProductService::class,
            'productPurchasePrice' => \Iplicit\Service\ProductPurchasePriceService::class,
            'productSalePrice' => \Iplicit\Service\ProductSalePriceService::class,
            'profile' => \Iplicit\Service\ProfileService::class,
            'project' => \Iplicit\Service\ProjectService::class,
            'purchaseInvoice' => \Iplicit\Service\PurchaseInvoiceService::class,
            'purchaseOrder' => \Iplicit\Service\PurchaseOrderService::class,
            'receipt' => \Iplicit\Service\ReceiptService::class,
            'resource' => \Iplicit\Service\ResourceService::class,
            'saleInvoice' => \Iplicit\Service\SaleInvoiceService::class,
            'saleOrder' => \Iplicit\Service\SaleOrderService::class,
            'saleQuote' => \Iplicit\Service\SaleQuoteService::class,
            'session' => \Iplicit\Service\SessionService::class,
        ];

        $this->assertCount(30, $services);

        foreach ($services as $propertyName => $expectedClass) {
            // Test method access: $client->{$propertyName}()
            $serviceMethod = $this->client->{$propertyName}();
            $this->assertInstanceOf($expectedClass, $serviceMethod);
            $this->assertInstanceOf(AbstractService::class, $serviceMethod);

            // Test magic property access: $client->{$propertyName}
            $serviceProp = $this->client->{$propertyName};
            $this->assertSame($serviceMethod, $serviceProp);
        }
    }
}
