<?php

use PHPUnit\Framework\TestCase;
use ScreenshotMax\Services\TaskService;
use ScreenshotMax\ApiClient;

class TaskServiceTest extends TestCase
{
    private ApiClient $client;
    private TaskService $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(ApiClient::class);
        $this->service = new TaskService($this->client);
    }

    public function testGetTasksCallsClient(): void
    {
        $tasks = [['id' => 1], ['id' => 2]];
        $this->client->expects($this->once())
            ->method('get')
            ->with('/v1/tasks')
            ->willReturn($tasks);

        $result = $this->service->getTasks();
        $this->assertSame($tasks, $result);
    }

    public function testGetTaskCallsClient(): void
    {
        $id = 123;
        $task = ['id' => $id];
        $this->client->expects($this->once())
            ->method('get')
            ->with('/v1/tasks/' . $id)
            ->willReturn($task);

        $result = $this->service->getTask($id);
        $this->assertSame($task, $result);
    }

    public function testCreateTaskCallsClient(): void
    {
        $body = ['name' => 'test'];
        $this->client->expects($this->once())
            ->method('post')
            ->with('/v1/tasks', $body)
            ->willReturn($body);

        $result = $this->service->createTask($body);
        $this->assertSame($body, $result);
    }

    public function testUpdateTaskCallsClient(): void
    {
        $id = 123;
        $body = ['name' => 'test'];
        $this->client->expects($this->once())
            ->method('patch')
            ->with('/v1/tasks/' . $id, $body)
            ->willReturn($body);

        $result = $this->service->updateTask($id, $body);
        $this->assertSame($body, $result);
    }

    public function testDeleteTaskCallsClient(): void
    {
        $id = 123;
        $this->client->expects($this->once())
            ->method('delete')
            ->with('/v1/tasks/' . $id);

        $result = $this->service->deleteTask($id);
    }
}