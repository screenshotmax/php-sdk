<?php

use PHPUnit\Framework\TestCase;
use ScreenshotMax\Services\ScreencastService;
use ScreenshotMax\Options\ScreencastOptions;
use ScreenshotMax\ApiClient;

class ScreencastServiceTest extends TestCase
{
    private $client;
    private ScreencastService $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(ApiClient::class);
        $this->service = new ScreencastService($this->client);
    }

    public function testSetOptionsReturnsSelf(): void
    {
        $options = new ScreencastOptions();
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
        $options = new ScreencastOptions();
        $options->url = 'https://example.com';
        $this->service->setOptions($options);

        $this->client->expects($this->once())
            ->method('generateSignedUrl')
            ->with('/v1/screencast', $options->toArray())
            ->willReturn('http://signed.url');

        $url = $this->service->getUrl(true);
        $this->assertSame('http://signed.url', $url);
    }

    public function testFetchCallsClientGet(): void
    {
        $options = new ScreencastOptions();
        $options->url = 'https://example.com';
        $response = ['data' => 'mp4', 'headers' => ['content-type' => 'video/mp4']];

        $this->service->setOptions($options);
        $this->client->expects($this->once())
            ->method('get')
            ->with('/v1/screencast', $options->toArray(), true)
            ->willReturn($response);

        $result = $this->service->fetch(true);
        $this->assertSame($response, $result);
    }

    public function testSetOptionsWithScreencastOptionsObject(): void
    {
        $options = $this->createMock(ScreencastOptions::class);
        $optionsArray = ['url' => 'https://example.com', 'format' => 'mp4'];
        $options->method('toArray')->willReturn($optionsArray);

        $result = $this->service->setOptions($options);
        $this->assertSame($this->service, $result);

        // Now test that getUrl(true) uses the array from ScreencastOptions
        $this->client->expects($this->once())
            ->method('generateSignedUrl')
            ->with('/v1/screencast', $optionsArray)
            ->willReturn('http://signed.url');

        $url = $this->service->getUrl(true);
        $this->assertSame('http://signed.url', $url);
    }
}