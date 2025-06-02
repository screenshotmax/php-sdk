<?php

use PHPUnit\Framework\TestCase;
use ScreenshotMax\Services\UsageService;
use ScreenshotMax\ApiClient;

class UsageServiceTest extends TestCase
{
    private ApiClient $client;
    private UsageService $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(ApiClient::class);
        $this->service = new UsageService($this->client);
    }

    public function testGetCallsClient(): void
    {
        $usage = ['hits', 'concurrency'];
        $this->client->expects($this->once())
            ->method('get')
            ->with('/v1/usage')
            ->willReturn($usage);

        $result = $this->service->get();
        $this->assertSame($usage, $result);
    }
}