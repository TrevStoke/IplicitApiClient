<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit;

use Iplicit\Client;
use Iplicit\Config;
use Iplicit\Exception\ApiException;
use Iplicit\Exception\AuthenticationException;
use Iplicit\Exception\NotFoundException;
use Iplicit\Exception\ValidationException;
use Iplicit\Http\MockHttpClient;
use Iplicit\Http\Request;
use Iplicit\Http\Response;
use Iplicit\IplicitClient;
use Iplicit\Service\SaleInvoiceService;
use PHPUnit\Framework\TestCase;

class IplicitClientTest extends TestCase
{
    public function testAuthenticationFlow(): void
    {
        $mockHttp = new MockHttpClient();
        $mockHttp->addJsonResponse('session-token-xyz-123', 200);

        $client = new IplicitClient(
            configOrApiKey: 'my-api-key',
            httpClient: $mockHttp
        );

        $token = $client->authenticate();

        $this->assertSame('session-token-xyz-123', $token);
        $this->assertSame('session-token-xyz-123', $client->getSessionToken());

        $lastRequest = $mockHttp->getLastRequest();
        $this->assertNotNull($lastRequest);
        $this->assertSame('POST', $lastRequest->getMethod());
        $this->assertSame('https://api.iplicit.com/api/Session/create/api', $lastRequest->getUrl());
        $this->assertSame(['apiKey' => 'my-api-key'], $lastRequest->getBody());
    }

    public function testAuthenticationWithUsername(): void
    {
        $mockHttp = new MockHttpClient();
        $mockHttp->addJsonResponse('session-token-abc', 200);

        $client = new IplicitClient(
            configOrApiKey: 'my-api-key',
            httpClient: $mockHttp,
            username: 'API'
        );

        $this->assertSame('API', $client->getUsername());

        $token = $client->authenticate();

        $this->assertSame('session-token-abc', $token);

        $lastRequest = $mockHttp->getLastRequest();
        $this->assertNotNull($lastRequest);
        $this->assertSame('POST', $lastRequest->getMethod());
        $this->assertSame('https://api.iplicit.com/api/Session/create/api', $lastRequest->getUrl());
        $this->assertSame(['apiKey' => 'my-api-key', 'username' => 'API'], $lastRequest->getBody());
    }

    public function testAuthenticationPassesUsernameExplicitly(): void
    {
        $mockHttp = new MockHttpClient();
        $mockHttp->addJsonResponse('session-token-explicit', 200);

        $client = new IplicitClient(
            configOrApiKey: 'my-api-key',
            httpClient: $mockHttp
        );

        $token = $client->authenticate(apiKey: 'override-key', username: 'override-user');

        $this->assertSame('session-token-explicit', $token);

        $lastRequest = $mockHttp->getLastRequest();
        $this->assertNotNull($lastRequest);
        $this->assertSame(['apiKey' => 'override-key', 'username' => 'override-user'], $lastRequest->getBody());
    }

    public function testAuthenticationWithJsonObjectResponse(): void
    {
        $mockHttp = new MockHttpClient();
        $mockHttp->addJsonResponse(['userSession' => 'token-abc'], 200);

        $client = new IplicitClient(
            configOrApiKey: 'my-api-key',
            httpClient: $mockHttp
        );

        $token = $client->authenticate();
        $this->assertSame('token-abc', $token);
    }

    public function testAuthenticateWithoutKeyThrowsException(): void
    {
        $client = new IplicitClient(httpClient: new MockHttpClient());
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('API key is required');

        $client->authenticate();
    }

    public function testCloseSession(): void
    {
        $mockHttp = new MockHttpClient();
        $mockHttp->addJsonResponse(['status' => 'ok'], 200);

        $client = new IplicitClient(
            sessionToken: 'token-to-close',
            httpClient: $mockHttp
        );

        $this->assertSame('token-to-close', $client->getSessionToken());
        $result = $client->closeSession();

        $this->assertTrue($result);
        $this->assertNull($client->getSessionToken());

        $lastReq = $mockHttp->getLastRequest();
        $this->assertNotNull($lastReq);
        $this->assertSame('POST', $lastReq->getMethod());
        $this->assertSame('https://api.iplicit.com/api/Session/close', $lastReq->getUrl());
        $this->assertSame('Bearer token-to-close', $lastReq->getHeaders()['Authorization']);
    }

    public function testHeadersIncludeBearerAndDomain(): void
    {
        $mockHttp = new MockHttpClient();
        $mockHttp->addJsonResponse(['result' => 'ok'], 200);

        $client = new IplicitClient(
            sessionToken: 'test-bearer-token',
            domain: 'acme-corp',
            httpClient: $mockHttp
        );

        $client->request('GET', '/api/Profile');

        $lastReq = $mockHttp->getLastRequest();
        $this->assertNotNull($lastReq);
        $headers = $lastReq->getHeaders();

        $this->assertSame('Bearer test-bearer-token', $headers['Authorization']);
        $this->assertSame('acme-corp', $headers['domain']);
        $this->assertSame('application/json', $headers['Accept']);
    }

    public function testUnauthorizedThrowsAuthenticationException(): void
    {
        $mockHttp = new MockHttpClient();
        $mockHttp->addJsonResponse(['message' => 'Invalid or expired session'], 401);

        $client = new IplicitClient(httpClient: $mockHttp);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid or expired session');

        $client->request('GET', '/api/Profile');
    }

    public function testNotFoundThrowsNotFoundException(): void
    {
        $mockHttp = new MockHttpClient();
        $mockHttp->addJsonResponse(['message' => 'Invoice not found'], 404);

        $client = new IplicitClient(httpClient: $mockHttp);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Invoice not found');

        $client->request('GET', '/api/SaleInvoice/invalid-id');
    }

    public function testBadRequestThrowsValidationException(): void
    {
        $mockHttp = new MockHttpClient();
        $mockHttp->addJsonResponse(['message' => 'Field "amount" is required'], 400);

        $client = new IplicitClient(httpClient: $mockHttp);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field "amount" is required');

        $client->request('POST', '/api/SaleInvoice', [], []);
    }

    public function testInternalServerErrorThrowsApiException(): void
    {
        $mockHttp = new MockHttpClient();
        $mockHttp->addResponse(new Response(500, 'Internal Server Error'));

        $client = new IplicitClient(httpClient: $mockHttp);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('API error (500)');

        $client->request('GET', '/api/Profile');
    }

    public function testServiceAccessorsViaMethodsAndProperties(): void
    {
        $client = new IplicitClient(httpClient: new MockHttpClient());

        $this->assertInstanceOf(SaleInvoiceService::class, $client->saleInvoice());
        $this->assertInstanceOf(SaleInvoiceService::class, $client->saleInvoice);
        $this->assertSame($client->saleInvoice(), $client->saleInvoice);
    }

    public function testClientAliasInstantiation(): void
    {
        $client = new Client('my-key');
        $this->assertInstanceOf(IplicitClient::class, $client);
        $this->assertSame('my-key', $client->getApiKey());
    }
}
