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
        $this->assertTrue($config->isFollowLocation());
        $this->assertSame(5, $config->getMaxRedirects());
        $this->assertSame(CURL_REDIR_POST_ALL, $config->getPostRedir());
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
            followLocation: false,
            maxRedirects: 10,
            postRedir: 0,
            httpClient: $mockHttp
        );

        $this->assertSame('https://custom.api.com', $config->getBaseUrl());
        $this->assertSame('key123', $config->getApiKey());
        $this->assertSame('token456', $config->getSessionToken());
        $this->assertSame('my-domain', $config->getDomain());
        $this->assertSame(60, $config->getTimeout());
        $this->assertFalse($config->isVerifySsl());
        $this->assertFalse($config->isFollowLocation());
        $this->assertSame(10, $config->getMaxRedirects());
        $this->assertSame(0, $config->getPostRedir());
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

        $config->setFollowLocation(true);
        $this->assertTrue($config->isFollowLocation());

        $config->setMaxRedirects(7);
        $this->assertSame(7, $config->getMaxRedirects());

        $config->setPostRedir(CURL_REDIR_POST_301 | CURL_REDIR_POST_302);
        $this->assertSame(CURL_REDIR_POST_301 | CURL_REDIR_POST_302, $config->getPostRedir());
    }
}
