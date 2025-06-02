<?php
namespace ScreenshotMax\Services;

use ScreenshotMax\ApiClient;

class TaskService
{
    private string $path = '/v1/tasks';
    private ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    public function getTasks(): array
    {
        return $this->client->get($this->path);
    }

    public function getTask(int $taskId): array
    {
        return $this->client->get($this->path . '/' . $taskId);
    }

    public function createTask(array $options): array
    {
        return $this->client->post($this->path, $options);
    }

    public function deleteTask(int $taskId): void
    {
        $this->client->delete($this->path . '/' . $taskId);
    }

    public function updateTask(int $taskId, array $options): array
    {
        return $this->client->patch($this->path . '/' . $taskId, $options);
    }
}