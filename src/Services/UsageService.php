<?php
namespace ScreenshotMax\Services;

use ScreenshotMax\ApiClient;

class UsageService
{
    private string $path = '/v1/usage';
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