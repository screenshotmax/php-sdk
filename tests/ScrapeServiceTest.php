<?php

use PHPUnit\Framework\TestCase;
use ScreenshotMax\Services\ScrapeService;
use ScreenshotMax\Options\ScrapeOptions;
use ScreenshotMax\ApiClient;

class ScrapeServiceTest extends TestCase
{
    private $client;
    private ScrapeService $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(ApiClient::class);
        $this->service = new ScrapeService($this->client);
    }

    public function testSetOptionsReturnsSelf(): void
    {
        $options = new ScrapeOptions();
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
        $options = new ScrapeOptions();
        $options->url = 'https://example.com';
        $this->service->setOptions($options);

        $this->client->expects($this->once())
            ->method('generateSignedUrl')
            ->with('/v1/scrape', $options->toArray())
            ->willReturn('http://signed.url');

        $url = $this->service->getUrl(true);
        $this->assertSame('http://signed.url', $url);
    }

    public function testFetchCallsClientGet(): void
    {
        $options = new ScrapeOptions();
        $options->url = 'https://example.com';
        $response = ['data' => 'html', 'headers' => ['content-type' => 'text/html']];

        $this->service->setOptions($options);
        $this->client->expects($this->once())
            ->method('get')
            ->with('/v1/scrape', $options->toArray(), true)
            ->willReturn($response);

        $result = $this->service->fetch(true);
        $this->assertSame($response, $result);
    }

    public function testSetOptionsWithScrapeOptionsObject(): void
    {
        $options = $this->createMock(ScrapeOptions::class);
        $optionsArray = ['url' => 'https://example.com', 'format' => 'html'];
        $options->method('toArray')->willReturn($optionsArray);

        $result = $this->service->setOptions($options);
        $this->assertSame($this->service, $result);

        // Now test that getUrl(true) uses the array from ScrapeOptions
        $this->client->expects($this->once())
            ->method('generateSignedUrl')
            ->with('/v1/scrape', $optionsArray)
            ->willReturn('http://signed.url');

        $url = $this->service->getUrl(true);
        $this->assertSame('http://signed.url', $url);
    }
}