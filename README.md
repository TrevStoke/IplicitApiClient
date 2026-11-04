# Iplicit API Client for PHP

A clean, modern, and high-performance PHP API client for **Iplicit** (online financial accounting software), generated against the official Iplicit OpenAPI / Swagger specification.

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-blue.svg)](https://www.php.net)
[![License: BSD-3-Clause](https://img.shields.io/badge/License-BSD--3--Clause-blue.svg)](LICENSE)

---

## Features

- **Full OpenAPI 1.19.0 Coverage**: Covers all 30 domain services and 140 endpoints (Sale Invoices, Purchase Invoices, Documents, Payments, Contacts, Catalogs, Banking, Enquiries, Projects, etc.).
- **Native cURL Transport**: Fast, lightweight, dependency-free HTTP execution with configurable timeouts and SSL verification.
- **Generator-Based Pagination**: Paginate with ease using generators (`yield`) via `fetchAll*()` methods or access the `Paginator` directly for memory-efficient processing of large datasets.
- **Comprehensive Error Hierarchy**: Specific exceptions for `AuthenticationException` (401), `NotFoundException` (404), `ValidationException` (400/422), and `NetworkException`.
- **First-Class Testability**: Built-in `MockHttpClient` for instant unit and integration testing without network calls.

---

## Installation

Install the package via Composer:

```bash
composer require trevweb/iplicit-api-client
```

---

## Quickstart

### 1. Initialize Client & Authenticate

```php
use Iplicit\IplicitClient;

$client = new IplicitClient(
    configOrApiKey: 'YOUR_IPLICIT_API_KEY',
    domain: 'your-domain-code', // optional domain header
    username: 'API'             // optional username if required by tenant/user key
);

// Authenticate to obtain a user session token
$sessionToken = $client->authenticate();
```

Alternatively, if you already possess a user session token:

```php
$client = new IplicitClient(
    sessionToken: 'YOUR_EXISTING_USER_SESSION_TOKEN',
    domain: 'your-domain-code'
);
```

---

## Working with Services

All 30 API domain services are available as fluent methods or properties on the client:

```php
// Access via property or method
$saleInvoiceService = $client->saleInvoice;      // or $client->saleInvoice()
$documentService    = $client->document;         // or $client->document()
$contactService     = $client->contactAccount;   // or $client->contactAccount()
$projectService     = $client->project;          // or $client->project()
```

### Creating & Managing Records

```php
// Create a sale invoice
$invoice = $client->saleInvoice->createSaleInvoice([
    'contactAccount' => 'CUST001',
    'docDate' => '2026-10-31',
    'lines' => [
        [
            'description' => 'Software Development Services',
            'unitPrice' => 1250.00,
            'quantity' => 1,
            'taxCode' => 'STANDARD',
        ]
    ]
]);

// Fetch by ID
$details = $client->saleInvoice->getSaleInvoice($invoice['id']);

// Submit for authorization
$client->saleInvoice->submitSaleInvoice($invoice['id']);
```

---

## Pagination & Generator Iteration

### Memory-Efficient Generator Iteration (`yield`)

For any paginated resource, `fetchAll*()` returns a PHP `\Generator` that automatically handles fetching consecutive pages in batches behind the scenes while yielding records one by one:

```php
// Iterate through every sale invoice across all pages effortlessly
foreach ($client->saleInvoice->fetchAllSaleInvoices(['Outstanding' => true], pageSize: 50) as $invoice) {
    echo "Processing Invoice #{$invoice['docNo']} - Amount: {$invoice['amount']}\n";
}
```

### Using the `Paginator` Object

You can also retrieve the `Paginator` directly for fine-grained pagination control:

```php
$paginator = $client->saleInvoice->paginateSaleInvoices(['Outstanding' => true], pageSize: 25);

// Set maximum item limit
$paginator->limit(100);

// Fetch all matching records into an array
$allInvoices = $paginator->fetchAll();

// Or iterate page-by-page
foreach ($paginator->iteratePages() as $pageIndex => $pageItems) {
    echo "Batch " . ($pageIndex + 1) . " contains " . count($pageItems) . " invoices.\n";
}
```

---

## Running Enquiries

```php
$results = $client->enquiry->runEnquiry('FINANCIAL_SUMMARY_ID', [
    'criteria' => [
        'financialYear' => 2026,
    ]
]);
```

---

## Error Handling

The client maps HTTP error codes to clear, specific exception classes:

```php
use Iplicit\Exception\AuthenticationException;
use Iplicit\Exception\ValidationException;
use Iplicit\Exception\NotFoundException;
use Iplicit\Exception\ApiException;
use Iplicit\Exception\NetworkException;

try {
    $client->saleInvoice->getSaleInvoice('non-existent-id');
} catch (AuthenticationException $e) {
    // 401 Unauthorized (expired token or invalid API key)
} catch (NotFoundException $e) {
    // 404 Resource not found
} catch (ValidationException $e) {
    // 400 Bad Request / 422 Unprocessable Entity
    echo "Validation error: " . $e->getMessage();
    print_r($e->getResponseBody());
} catch (ApiException $e) {
    // Other API errors (5xx, etc.)
    echo "API error [{$e->getStatusCode()}]: " . $e->getMessage();
} catch (NetworkException $e) {
    // Connection failure, cURL error, DNS timeout
}
```

---

## Testing with Mocks

Use `MockHttpClient` in your test suite to test your application without executing real HTTP requests:

```php
use Iplicit\IplicitClient;
use Iplicit\Http\MockHttpClient;
use PHPUnit\Framework\TestCase;

class InvoiceSyncTest extends TestCase
{
    public function testSync(): void
    {
        $mockHttp = new MockHttpClient();
        $mockHttp->addJsonResponse([
            ['id' => 'inv-1', 'docNo' => 'SINV001', 'amount' => 100.00]
        ]);

        $client = new IplicitClient(sessionToken: 'test-token', httpClient: $mockHttp);
        $invoices = $client->saleInvoice->getSaleInvoices();

        $this->assertCount(1, $invoices);
        $this->assertSame('SINV001', $invoices[0]['docNo']);
    }
}
```

---

## Running the Test Suite

```bash
composer test
# or
vendor/bin/phpunit
```

---

## License

This package is open-sourced software licensed under the [BSD-3-Clause license](LICENSE).
