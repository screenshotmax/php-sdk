<?php

use PHPUnit\Framework\TestCase;
use ScreenshotMax\Services\PDFService;
use ScreenshotMax\Options\PDFOptions;
use ScreenshotMax\ApiClient;

class PDFServiceTest extends TestCase
{
    private $client;
    private PDFService $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(ApiClient::class);
        $this->service = new PDFService($this->client);
    }

    public function testSetOptionsReturnsSelf(): void
    {
        $options = new PDFOptions();
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
        $options = new PDFOptions();
        $options->url = 'https://example.com';
        $this->service->setOptions($options);

        $this->client->expects($this->once())
            ->method('generateSignedUrl')
            ->with('/v1/pdf', $options->toArray())
            ->willReturn('http://signed.url');

        $url = $this->service->getUrl(true);
        $this->assertSame('http://signed.url', $url);
    }

    public function testFetchCallsClientGet(): void
    {
        $options = new PDFOptions();
        $options->url = 'https://example.com';
        $response = ['data' => 'pdf', 'headers' => ['content-type' => 'application/pdf']];

        $this->service->setOptions($options);
        $this->client->expects($this->once())
            ->method('get')
            ->with('/v1/pdf', $options->toArray(), true)
            ->willReturn($response);

        $result = $this->service->fetch(true);
        $this->assertSame($response, $result);
    }

    public function testSetOptionsWithPDFOptionsObject(): void
    {
        $options = $this->createMock(PDFOptions::class);
        $optionsArray = ['url' => 'https://example.com', 'pdf_paper_format' => 'a4'];
        $options->method('toArray')->willReturn($optionsArray);

        $result = $this->service->setOptions($options);
        $this->assertSame($this->service, $result);

        // Now test that getUrl(true) uses the array from PDFOptions
        $this->client->expects($this->once())
            ->method('generateSignedUrl')
            ->with('/v1/pdf', $optionsArray)
            ->willReturn('http://signed.url');

        $url = $this->service->getUrl(true);
        $this->assertSame('http://signed.url', $url);
    }
}