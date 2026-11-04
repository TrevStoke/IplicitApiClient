<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit;

use Iplicit\Exception\NetworkException;
use Iplicit\Http\CurlHttpClient;
use Iplicit\Http\Request;
use PHPUnit\Framework\TestCase;

class CurlHttpClientTest extends TestCase
{
    public function testInvalidUrlThrowsNetworkException(): void
    {
        $client = new CurlHttpClient([], 1, false);
        $request = new Request('GET', 'http://127.0.0.1:1/api/test');

        $this->expectException(NetworkException::class);
        $client->send($request);
    }

    public function testInstantiationWithCustomOptions(): void
    {
        $client = new CurlHttpClient(
            defaultHeaders: ['X-Custom' => 'HeaderVal'],
            defaultTimeout: 10,
            verifySsl: false,
            followLocation: true,
            maxRedirects: 10,
            postRedir: CURL_REDIR_POST_301 | CURL_REDIR_POST_302
        );

        $this->assertInstanceOf(CurlHttpClient::class, $client);
        $this->assertTrue($client->isFollowLocation());
        $this->assertSame(10, $client->getMaxRedirects());
        $this->assertSame(CURL_REDIR_POST_301 | CURL_REDIR_POST_302, $client->getPostRedir());

        $client->setFollowLocation(false);
        $this->assertFalse($client->isFollowLocation());

        $client->setMaxRedirects(3);
        $this->assertSame(3, $client->getMaxRedirects());

        $client->setPostRedir(0);
        $this->assertSame(0, $client->getPostRedir());
    }

    public function testPostRequestFollowsRedirectWithPayloadAndContentLength(): void
    {
        $serverScript = sys_get_temp_dir() . '/iplicit_test_server_' . uniqid() . '.php';
        file_put_contents($serverScript, '<?php
if ($_SERVER["REQUEST_URI"] === "/redirect") {
    header("Location: /destination", true, 302);
    exit;
}
echo json_encode([
    "method" => $_SERVER["REQUEST_METHOD"],
    "content_length" => $_SERVER["HTTP_CONTENT_LENGTH"] ?? $_SERVER["CONTENT_LENGTH"] ?? null,
    "body" => file_get_contents("php://input"),
]);
');

        $port = 9876;
        $pipes = [];
        $process = proc_open(
            "php -S 127.0.0.1:{$port} " . escapeshellarg($serverScript),
            [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes
        );

        usleep(100000); // 100ms startup

        try {
            $client = new CurlHttpClient(followLocation: true);
            $request = new Request('POST', "http://127.0.0.1:{$port}/redirect", body: ['apiKey' => 'test-key-123']);

            $response = $client->send($request);
            $this->assertSame(200, $response->getStatusCode());

            $data = $response->json();
            $this->assertSame('POST', $data['method']);
            $this->assertNotNull($data['content_length']);
            $this->assertNotSame('', $data['body']);
            $this->assertSame(['apiKey' => 'test-key-123'], json_decode($data['body'], true));
        } finally {
            if (is_resource($process)) {
                proc_terminate($process);
                proc_close($process);
            }
            if (file_exists($serverScript)) {
                unlink($serverScript);
            }
        }
    }
}
