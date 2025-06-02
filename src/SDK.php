<?php
namespace ScreenshotMax;

use ScreenshotMax\Services\ScreenshotService;
use ScreenshotMax\Services\ScreencastService;
use ScreenshotMax\Services\ScrapeService;
use ScreenshotMax\Services\PDFService;
use ScreenshotMax\Services\TaskService;
use ScreenshotMax\Services\UsageService;
use ScreenshotMax\Services\DeviceService;

class SDK
{
    public ScreenshotService $screenshot;
    public ScreencastService $screencast;
    public ScrapeService $scrape;
    public PDFService $pdf;
    public TaskService $task;
    public UsageService $usage;
    public DeviceService $device;

    public function __construct(string $accessKey, string $secretKey)
    {
        $client = new ApiClient($accessKey, $secretKey);
        $this->screenshot = new ScreenshotService($client);
        $this->screencast = new ScreencastService($client);
        $this->scrape = new ScrapeService($client);
        $this->pdf = new PDFService($client);
        $this->task = new TaskService($client);
        $this->usage = new UsageService($client);
        $this->device = new DeviceService($client);
    }
}