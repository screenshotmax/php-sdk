<?php
namespace ScreenshotMax\Options;

class ScreencastOptions extends ScreenshotOptions
{
    public ?int $duration = null;
    public ?string $scenario = null;
    public ?int $scroll_by_amount = null;
    public ?int $scroll_by_delay = null;
    public ?int $scroll_by_duration = null;
    public ?bool $scroll_back = null;
    public ?int $scroll_back_delay = null;
    public ?string $scroll_easing = null;
}