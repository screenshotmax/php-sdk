<?php
namespace ScreenshotMax\Services;

use ScreenshotMax\ApiClient;

class DeviceService
{
    private string $path = '/v1/devices';
    private ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    public function get(): array
    {
        return $this->client->get($this->path);
    }
}