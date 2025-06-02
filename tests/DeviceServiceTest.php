<?php

use PHPUnit\Framework\TestCase;
use ScreenshotMax\Services\DeviceService;
use ScreenshotMax\ApiClient;

class DeviceServiceTest extends TestCase
{
    private ApiClient $client;
    private DeviceService $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(ApiClient::class);
        $this->service = new DeviceService($this->client);
    }

    public function testGetCallsClient(): void
    {
        $devices = ['mobile', 'tablet'];
        $this->client->expects($this->once())
            ->method('get')
            ->with('/v1/devices')
            ->willReturn($devices);

        $result = $this->service->get();
        $this->assertSame($devices, $result);
    }
}