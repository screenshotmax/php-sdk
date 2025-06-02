<?php

use PHPUnit\Framework\TestCase;
use ScreenshotMax\Services\ScreenshotService;
use ScreenshotMax\Options\ScreenshotOptions;
use ScreenshotMax\ApiClient;

class ScreenshotServiceTest extends TestCase
{
    private $client;
    private ScreenshotService $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(ApiClient::class);
        $this->service = new ScreenshotService($this->client);
    }

    public function testSetOptionsReturnsSelf(): void
    {
        $options = new ScreenshotOptions();
        $options->url = 'https://example.com';
        $result = $this->service->setOptions($options);
        $this->assertSame($this->service, $result);
    }

    public function testGetUrlThrowsWithoutOptions(): void
    {
        $this->expectException(RuntimeException::class);
        $this->service->getUrl();
    }

    public function testGetUrlSigned(): void
    {
        $options = new ScreenshotOptions();
        $options->url = 'https://example.com';
        $this->service->setOptions($options);

        $this->client->expects($this->once())
            ->method('generateSignedUrl')
            ->with('/v1/screenshot', $options->toArray())
            ->willReturn('http://signed.url');

        $url = $this->service->getUrl(true);
        $this->assertSame('http://signed.url', $url);
    }

    public function testFetchCallsClientGet(): void
    {
        $options = new ScreenshotOptions();
        $options->url = 'https://example.com';
        $response = ['data' => 'img', 'headers' => ['content-type' => 'image/png']];

        $this->service->setOptions($options);
        $this->client->expects($this->once())
            ->method('get')
            ->with('/v1/screenshot', $options->toArray(), true)
            ->willReturn($response);

        $result = $this->service->fetch(true);
        $this->assertSame($response, $result);
    }

    public function testSetOptionsWithScreenshotOptionsObject(): void
    {
        $options = $this->createMock(ScreenshotOptions::class);
        $optionsArray = ['url' => 'https://example.com', 'format' => 'png'];
        $options->method('toArray')->willReturn($optionsArray);

        $result = $this->service->setOptions($options);
        $this->assertSame($this->service, $result);

        // Now test that getUrl(true) uses the array from ScreenshotOptions
        $this->client->expects($this->once())
            ->method('generateSignedUrl')
            ->with('/v1/screenshot', $optionsArray)
            ->willReturn('http://signed.url');

        $url = $this->service->getUrl(true);
        $this->assertSame('http://signed.url', $url);
    }
}