<?php

use PHPUnit\Framework\TestCase;
use ScreenshotMax\ApiClient;

class ApiClientTest extends TestCase
{
    private string $accessKey = 'test-access';
    private string $secretKey = 'secret-key';
    private ApiClient $client;

    protected function setUp(): void
    {
        $this->client = new ApiClient($this->accessKey, $this->secretKey);
    }

    public function testGenerateUrlWithoutParams(): void
    {
        $url = $this->client->generateUrl('/v1/test');
        $this->assertSame(
            'https://api.screenshotmax.com/v1/test?access_key=' . $this->accessKey,
            $url
        );
    }

    public function testGenerateUrlWithParams(): void
    {
        $url = $this->client->generateUrl('/v1/test', [
            'format' => 'pdf',
            'url' => 'https://example.com'
        ]);
        $this->assertStringContainsString('access_key=' . $this->accessKey, $url);
        $this->assertStringContainsString('format=pdf', $url);
        $this->assertStringContainsString('url=https%3A%2F%2Fexample.com', $url);
    }

    public function testGenerateSignedUrlIncludesSignature(): void
    {
        $params = ['url' => 'https://example.com'];
        $query = http_build_query(array_merge($params, ['access_key' => $this->accessKey]));
        $expected = hash_hmac('sha256', $query, $this->secretKey);

        $url = $this->client->generateSignedUrl('/v1/test', $params);

        $this->assertStringContainsString('signature=' . $expected, $url);
    }

    public function testConstructorThrowsOnEmptyKeys(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ApiClient('', '');
    }
}