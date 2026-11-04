<?php

declare(strict_types=1);

namespace Iplicit;

use Iplicit\Http\HttpClientInterface;

/**
 * Configuration options for the Iplicit API Client.
 */
class Config
{
    public const DEFAULT_BASE_URL = 'https://api.iplicit.com';

    private string $baseUrl;
    private ?string $apiKey;
    private ?string $sessionToken;
    private ?string $domain;
    private int $timeout;
    private bool $verifySsl;
    private bool $followLocation;
    private int $maxRedirects;
    private ?HttpClientInterface $httpClient;

    public function __construct(
        ?string $apiKey = null,
        ?string $sessionToken = null,
        ?string $domain = null,
        string $baseUrl = self::DEFAULT_BASE_URL,
        int $timeout = 30,
        bool $verifySsl = true,
        bool $followLocation = true,
        int $maxRedirects = 5,
        ?HttpClientInterface $httpClient = null
    ) {
        $this->apiKey = $apiKey;
        $this->sessionToken = $sessionToken;
        $this->domain = $domain;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
        $this->verifySsl = $verifySsl;
        $this->followLocation = $followLocation;
        $this->maxRedirects = $maxRedirects;
        $this->httpClient = $httpClient;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(?string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function getSessionToken(): ?string
    {
        return $this->sessionToken;
    }

    public function setSessionToken(?string $sessionToken): self
    {
        $this->sessionToken = $sessionToken;
        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): self
    {
        $this->domain = $domain;
        return $this;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function isVerifySsl(): bool
    {
        return $this->verifySsl;
    }

    public function setVerifySsl(bool $verifySsl): self
    {
        $this->verifySsl = $verifySsl;
        return $this;
    }

    public function isFollowLocation(): bool
    {
        return $this->followLocation;
    }

    public function setFollowLocation(bool $followLocation): self
    {
        $this->followLocation = $followLocation;
        return $this;
    }

    public function getMaxRedirects(): int
    {
        return $this->maxRedirects;
    }

    public function setMaxRedirects(int $maxRedirects): self
    {
        $this->maxRedirects = $maxRedirects;
        return $this;
    }

    public function getHttpClient(): ?HttpClientInterface
    {
        return $this->httpClient;
    }

    public function setHttpClient(?HttpClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;
        return $this;
    }
}
