<?php
namespace ScreenshotMax\Services;

use ScreenshotMax\ApiClient;
use ScreenshotMax\Options\ScreencastOptions;

class ScreencastService
{
    private string $path = '/v1/screencast';
    private ?ScreencastOptions $options = null;
    private ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    public function setOptions(ScreencastOptions $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function getUrl(bool $signed = true): string
    {
        if ($this->options === null) {
            throw new \RuntimeException('Options not set.');
        }
        return $signed
            ? $this->client->generateSignedUrl($this->path, $this->options->toArray())
            : $this->client->generateUrl($this->path, $this->options->toArray());
    }

    public function fetch(bool $signed = true): array
    {
        if ($this->options === null) {
            throw new \RuntimeException('Options not set.');
        }
        return $this->client->get($this->path, $this->options->toArray(), $signed);
    }
}