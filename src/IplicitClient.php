<?php

declare(strict_types=1);

namespace Iplicit;

use Iplicit\Exception\ApiException;
use Iplicit\Exception\AuthenticationException;
use Iplicit\Exception\NotFoundException;
use Iplicit\Exception\ValidationException;
use Iplicit\Http\CurlHttpClient;
use Iplicit\Http\HttpClientInterface;
use Iplicit\Http\Request;
use Iplicit\Http\Response;
use Iplicit\Service\AbstractService;
use Iplicit\Service\AttachmentService;
use Iplicit\Service\BankTransactionService;
use Iplicit\Service\BatchPaymentService;
use Iplicit\Service\BudgetingService;
use Iplicit\Service\CatalogService;
use Iplicit\Service\ContactAccountService;
use Iplicit\Service\ContactGroupService;
use Iplicit\Service\ContactService;
use Iplicit\Service\CostCentreService;
use Iplicit\Service\DepartmentService;
use Iplicit\Service\DocumentAttachmentService;
use Iplicit\Service\DocumentBatchService;
use Iplicit\Service\DocumentService;
use Iplicit\Service\DocumentTypeService;
use Iplicit\Service\EnquiryService;
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
use Iplicit\Service\SaleInvoiceService;
use Iplicit\Service\SaleOrderService;
use Iplicit\Service\SaleQuoteService;
use Iplicit\Service\SessionService;

/**
 * Main Iplicit API Client.
 *
 * @property-read AttachmentService $attachment
 * @property-read BankTransactionService $bankTransaction
 * @property-read BatchPaymentService $batchPayment
 * @property-read BudgetingService $budgeting
 * @property-read CatalogService $catalog
 * @property-read ContactService $contact
 * @property-read ContactAccountService $contactAccount
 * @property-read ContactGroupService $contactGroup
 * @property-read CostCentreService $costCentre
 * @property-read DepartmentService $department
 * @property-read DocumentService $document
 * @property-read DocumentAttachmentService $documentAttachment
 * @property-read DocumentBatchService $documentBatch
 * @property-read DocumentTypeService $documentType
 * @property-read EnquiryService $enquiry
 * @property-read LegalEntityService $legalEntity
 * @property-read PaymentService $payment
 * @property-read ProductService $product
 * @property-read ProductPurchasePriceService $productPurchasePrice
 * @property-read ProductSalePriceService $productSalePrice
 * @property-read ProfileService $profile
 * @property-read ProjectService $project
 * @property-read PurchaseInvoiceService $purchaseInvoice
 * @property-read PurchaseOrderService $purchaseOrder
 * @property-read ReceiptService $receipt
 * @property-read ResourceService $resource
 * @property-read SaleInvoiceService $saleInvoice
 * @property-read SaleOrderService $saleOrder
 * @property-read SaleQuoteService $saleQuote
 * @property-read SessionService $session
 */
class IplicitClient
{
    private Config $config;
    private HttpClientInterface $httpClient;

    /** @var array<string, AbstractService> */
    private array $services = [];

    /**
     * @param Config|string|null $configOrApiKey Config object or API Key string
     * @param string|null $sessionToken Pre-existing user session token
     * @param string|null $domain Iplicit domain code
     * @param string $baseUrl Base URL of the API
     * @param HttpClientInterface|null $httpClient Custom HTTP client implementation
     */
    public function __construct(
        Config|string|null $configOrApiKey = null,
        ?string $sessionToken = null,
        ?string $domain = null,
        string $baseUrl = Config::DEFAULT_BASE_URL,
        ?HttpClientInterface $httpClient = null
    ) {
        if ($configOrApiKey instanceof Config) {
            $this->config = $configOrApiKey;
        } else {
            $this->config = new Config(
                apiKey: $configOrApiKey,
                sessionToken: $sessionToken,
                domain: $domain,
                baseUrl: $baseUrl,
                httpClient: $httpClient
            );
        }

        $this->httpClient = $this->config->getHttpClient() ?? $httpClient ?? new CurlHttpClient(
            defaultTimeout: $this->config->getTimeout(),
            verifySsl: $this->config->isVerifySsl(),
            followLocation: $this->config->isFollowLocation(),
            maxRedirects: $this->config->getMaxRedirects(),
            postRedir: $this->config->getPostRedir()
        );
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    public function setHttpClient(HttpClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    public function getSessionToken(): ?string
    {
        return $this->config->getSessionToken();
    }

    public function setSessionToken(?string $sessionToken): self
    {
        $this->config->setSessionToken($sessionToken);
        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->config->getApiKey();
    }

    public function setApiKey(?string $apiKey): self
    {
        $this->config->setApiKey($apiKey);
        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->config->getDomain();
    }

    public function setDomain(?string $domain): self
    {
        $this->config->setDomain($domain);
        return $this;
    }

    /**
     * Authenticates with the API using an API key and stores the resulting session token.
     *
     * @param string|null $apiKey If null, uses the configured API key.
     * @return string The authenticated user session token.
     * @throws AuthenticationException If authentication fails.
     */
    public function authenticate(?string $apiKey = null): string
    {
        $key = $apiKey ?? $this->config->getApiKey();
        if ($key === null || $key === '') {
            throw new AuthenticationException('API key is required to authenticate with Iplicit');
        }

        $response = $this->request('POST', '/api/Session/create/api', [], ['apiKey' => $key]);
        $data = $response->json();

        $token = null;
        if (is_string($data)) {
            $token = $data;
        } elseif (is_array($data)) {
            $token = $data['token'] ?? $data['userSession'] ?? $data['sessionToken'] ?? $data['id'] ?? null;
        }

        if ($token === null || $token === '') {
            // Check raw body in case token was returned without quotes
            $raw = trim($response->getRawBody(), " \t\n\r\0\x0B\"");
            if ($raw !== '') {
                $token = $raw;
            } else {
                throw new AuthenticationException('Invalid session response: session token missing in response', $response->getStatusCode(), $data);
            }
        }

        $this->config->setSessionToken($token);
        return $token;
    }

    /**
     * Closes the active session on the Iplicit server.
     */
    public function closeSession(): bool
    {
        try {
            $this->request('POST', '/api/Session/close');
        } finally {
            $this->config->setSessionToken(null);
        }

        return true;
    }

    /**
     * Executes an HTTP request to the Iplicit API with configured headers and authentication.
     *
     * @throws AuthenticationException on 401
     * @throws NotFoundException on 404
     * @throws ValidationException on 400 or 422
     * @throws ApiException on other HTTP errors
     */
    public function request(
        string $method,
        string $path,
        array $queryParams = [],
        mixed $body = null,
        array $headers = []
    ): Response {
        $baseUrl = $this->config->getBaseUrl();
        $url = str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
            ? $path
            : $baseUrl . '/' . ltrim($path, '/');

        $requestHeaders = [
            'Accept' => 'application/json',
        ];

        if ($this->config->getSessionToken() !== null && $this->config->getSessionToken() !== '') {
            $requestHeaders['Authorization'] = 'Bearer ' . $this->config->getSessionToken();
        }

        if ($this->config->getDomain() !== null && $this->config->getDomain() !== '') {
            $requestHeaders['domain'] = $this->config->getDomain();
            $requestHeaders['Domain'] = $this->config->getDomain();
        }

        foreach ($headers as $k => $v) {
            $requestHeaders[$k] = $v;
        }

        $request = new Request(
            method: $method,
            url: $url,
            queryParams: $queryParams,
            body: $body,
            headers: $requestHeaders,
            timeout: $this->config->getTimeout()
        );

        $response = $this->httpClient->send($request);

        if ($response->isSuccess()) {
            return $response;
        }

        $statusCode = $response->getStatusCode();
        $responseBody = $response->json();
        $errorMessage = $this->extractErrorMessage($response, $responseBody);

        if ($statusCode === 401) {
            throw new AuthenticationException($errorMessage, $statusCode, $responseBody, $response->getHeaders());
        }

        if ($statusCode === 404) {
            throw new NotFoundException($errorMessage, $statusCode, $responseBody, $response->getHeaders());
        }

        if ($statusCode === 400 || $statusCode === 422) {
            throw new ValidationException($errorMessage, $statusCode, $responseBody, $response->getHeaders());
        }

        throw new ApiException($errorMessage, $statusCode, $responseBody, $response->getHeaders());
    }

    private function extractErrorMessage(Response $response, mixed $responseBody): string
    {
        if (is_array($responseBody)) {
            if (isset($responseBody['message']) && is_string($responseBody['message'])) {
                return $responseBody['message'];
            }
            if (isset($responseBody['error']) && is_string($responseBody['error'])) {
                return $responseBody['error'];
            }
            if (isset($responseBody['title']) && is_string($responseBody['title'])) {
                $detail = $responseBody['detail'] ?? '';
                return $responseBody['title'] . ($detail ? ": {$detail}" : '');
            }
            if (isset($responseBody['errors']) && is_array($responseBody['errors'])) {
                return 'Validation error: ' . json_encode($responseBody['errors']);
            }
        }

        $raw = trim($response->getRawBody());
        if ($raw !== '') {
            return 'API error (' . $response->getStatusCode() . '): ' . substr($raw, 0, 200);
        }

        return 'HTTP request failed with status code ' . $response->getStatusCode();
    }

    /**
     * Magic getter for accessing services as properties.
     */
    public function __get(string $name): AbstractService
    {
        if (method_exists($this, $name)) {
            return $this->{$name}();
        }

        throw new \InvalidArgumentException("Undefined service or property: {$name}");
    }

    public function attachment(): AttachmentService
    {
        return $this->getService('attachment', AttachmentService::class);
    }

    public function bankTransaction(): BankTransactionService
    {
        return $this->getService('bankTransaction', BankTransactionService::class);
    }

    public function batchPayment(): BatchPaymentService
    {
        return $this->getService('batchPayment', BatchPaymentService::class);
    }

    public function budgeting(): BudgetingService
    {
        return $this->getService('budgeting', BudgetingService::class);
    }

    public function catalog(): CatalogService
    {
        return $this->getService('catalog', CatalogService::class);
    }

    public function contact(): ContactService
    {
        return $this->getService('contact', ContactService::class);
    }

    public function contactAccount(): ContactAccountService
    {
        return $this->getService('contactAccount', ContactAccountService::class);
    }

    public function contactGroup(): ContactGroupService
    {
        return $this->getService('contactGroup', ContactGroupService::class);
    }

    public function costCentre(): CostCentreService
    {
        return $this->getService('costCentre', CostCentreService::class);
    }

    public function department(): DepartmentService
    {
        return $this->getService('department', DepartmentService::class);
    }

    public function document(): DocumentService
    {
        return $this->getService('document', DocumentService::class);
    }

    public function documentAttachment(): DocumentAttachmentService
    {
        return $this->getService('documentAttachment', DocumentAttachmentService::class);
    }

    public function documentBatch(): DocumentBatchService
    {
        return $this->getService('documentBatch', DocumentBatchService::class);
    }

    public function documentType(): DocumentTypeService
    {
        return $this->getService('documentType', DocumentTypeService::class);
    }

    public function enquiry(): EnquiryService
    {
        return $this->getService('enquiry', EnquiryService::class);
    }

    public function legalEntity(): LegalEntityService
    {
        return $this->getService('legalEntity', LegalEntityService::class);
    }

    public function payment(): PaymentService
    {
        return $this->getService('payment', PaymentService::class);
    }

    public function product(): ProductService
    {
        return $this->getService('product', ProductService::class);
    }

    public function productPurchasePrice(): ProductPurchasePriceService
    {
        return $this->getService('productPurchasePrice', ProductPurchasePriceService::class);
    }

    public function productSalePrice(): ProductSalePriceService
    {
        return $this->getService('productSalePrice', ProductSalePriceService::class);
    }

    public function profile(): ProfileService
    {
        return $this->getService('profile', ProfileService::class);
    }

    public function project(): ProjectService
    {
        return $this->getService('project', ProjectService::class);
    }

    public function purchaseInvoice(): PurchaseInvoiceService
    {
        return $this->getService('purchaseInvoice', PurchaseInvoiceService::class);
    }

    public function purchaseOrder(): PurchaseOrderService
    {
        return $this->getService('purchaseOrder', PurchaseOrderService::class);
    }

    public function receipt(): ReceiptService
    {
        return $this->getService('receipt', ReceiptService::class);
    }

    public function resource(): ResourceService
    {
        return $this->getService('resource', ResourceService::class);
    }

    public function saleInvoice(): SaleInvoiceService
    {
        return $this->getService('saleInvoice', SaleInvoiceService::class);
    }

    public function saleOrder(): SaleOrderService
    {
        return $this->getService('saleOrder', SaleOrderService::class);
    }

    public function saleQuote(): SaleQuoteService
    {
        return $this->getService('saleQuote', SaleQuoteService::class);
    }

    public function session(): SessionService
    {
        return $this->getService('session', SessionService::class);
    }

    /**
     * @template T of AbstractService
     * @param class-string<T> $serviceClass
     * @return T
     */
    private function getService(string $key, string $serviceClass): AbstractService
    {
        if (!isset($this->services[$key])) {
            $this->services[$key] = new $serviceClass($this);
        }

        return $this->services[$key];
    }
}
