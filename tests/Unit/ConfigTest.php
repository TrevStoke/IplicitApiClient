<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit;

use Iplicit\Config;
use Iplicit\Http\MockHttpClient;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $config = new Config();
        $this->assertSame(Config::DEFAULT_BASE_URL, $config->getBaseUrl());
        $this->assertNull($config->getApiKey());
        $this->assertNull($config->getSessionToken());
        $this->assertNull($config->getDomain());
        $this->assertSame(30, $config->getTimeout());
        $this->assertTrue($config->isVerifySsl());
        $this->assertNull($config->getHttpClient());
    }

    public function testSettersAndGetters(): void
    {
        $mockHttp = new MockHttpClient();
        $config = new Config(
            apiKey: 'key123',
            sessionToken: 'token456',
            domain: 'my-domain',
            baseUrl: 'https://custom.api.com/',
            timeout: 60,
            verifySsl: false,
            httpClient: $mockHttp
        );

        $this->assertSame('https://custom.api.com', $config->getBaseUrl());
        $this->assertSame('key123', $config->getApiKey());
        $this->assertSame('token456', $config->getSessionToken());
        $this->assertSame('my-domain', $config->getDomain());
        $this->assertSame(60, $config->getTimeout());
        $this->assertFalse($config->isVerifySsl());
        $this->assertSame($mockHttp, $config->getHttpClient());

        $config->setBaseUrl('https://api.iplicit.com/');
        $this->assertSame('https://api.iplicit.com', $config->getBaseUrl());

        $config->setApiKey('newKey');
        $this->assertSame('newKey', $config->getApiKey());

        $config->setSessionToken('newToken');
        $this->assertSame('newToken', $config->getSessionToken());

        $config->setDomain('newDomain');
        $this->assertSame('newDomain', $config->getDomain());

        $config->setTimeout(15);
        $this->assertSame(15, $config->getTimeout());

        $config->setVerifySsl(true);
        $this->assertTrue($config->isVerifySsl());
    }
}
