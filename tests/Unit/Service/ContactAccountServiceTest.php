<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit\Service;

use Iplicit\Http\MockHttpClient;
use Iplicit\IplicitClient;
use Iplicit\Service\ContactAccountService;
use PHPUnit\Framework\TestCase;

class ContactAccountServiceTest extends TestCase
{
    private MockHttpClient $mockHttp;
    private ContactAccountService $service;

    protected function setUp(): void
    {
        $this->mockHttp = new MockHttpClient();
        $client = new IplicitClient(sessionToken: 'test-token', httpClient: $this->mockHttp);
        $this->service = $client->contactAccount();
    }

    public function testGetContactAccounts(): void
    {
        $this->mockHttp->addJsonResponse([['id' => 'ca-1', 'code' => 'ACC01', 'isCustomer' => true]]);

        $accounts = $this->service->getContactAccounts(['IsCustomer' => true]);

        $this->assertCount(1, $accounts);
        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('GET', $req->getMethod());
        $this->assertSame('https://api.iplicit.com/api/ContactAccount?IsCustomer=true', $req->getFullUrl());
    }

    public function testCreateAndGetContactAccount(): void
    {
        $this->mockHttp->addJsonResponse(['id' => 'ca-new']);
        $this->mockHttp->addJsonResponse(['id' => 'ca-new', 'code' => 'NEW01']);

        $created = $this->service->createContactAccount(['code' => 'NEW01', 'description' => 'New Customer']);
        $this->assertSame(['id' => 'ca-new'], $created);

        $fetched = $this->service->getContactAccount('ca-new', ['include' => 'contacts']);
        $this->assertSame('NEW01', $fetched['code']);

        $req = $this->mockHttp->getLastRequest();
        $this->assertSame('https://api.iplicit.com/api/ContactAccount/ca-new?include=contacts', $req->getFullUrl());
    }

    public function testLockAndUnlockContactAccount(): void
    {
        $this->mockHttp->addJsonResponse(['status' => 'Locked']);
        $this->mockHttp->addJsonResponse(['status' => 'Unlocked']);

        $lockRes = $this->service->lockContactAccount('ACC01');
        $this->assertSame(['status' => 'Locked'], $lockRes);

        $unlockRes = $this->service->unlockContactAccount('ACC01');
        $this->assertSame(['status' => 'Unlocked'], $unlockRes);
    }
}
