<?php
namespace ScreenshotMax\Options;

class ScreenshotOptions
{
    public ?string $url = null;
    public ?bool $gpu_rendering = null;
    public ?bool $capture_beyond_viewport = null;
    public ?string $viewport_device = null;
    public ?int $viewport_width = null;
    public ?int $viewport_height = null;
    public ?bool $viewport_landscape = null;
    public ?bool $viewport_has_touch = null;
    public ?bool $viewport_mobile = null;
    public ?float $device_scale_factor = null;
    public ?string $attachment_name = null;
    public ?bool $dark_mode = null;
    public ?bool $reduced_motion = null;
    public ?string $media_type = null;
    public ?string $vision_deficiency = null;
    public ?int $clip_x = null;
    public ?int $clip_y = null;
    public ?int $clip_width = null;
    public ?int $clip_height = null;
    public ?string $block_annoyance = null;
    public ?array $block_ressources = null;
    public ?int $geolocation_accuracy = null;
    public ?float $geolocation_latitude = null;
    public ?float $geolocation_longitude = null;
    public ?string $timezone = null;
    public ?array $hide_selectors = null;
    public ?string $click_selector = null;
    public ?string $authorization = null;
    public ?string $user_agent = null;
    public ?array $cookies = null;
    public ?array $headers = null;
    public ?bool $bypass_csp = null;
    public ?string $ip_location = null;
    public ?string $proxy = null;
    public ?int $delay = null;
    public ?int $timeout = null;
    public ?array $wait_until = null;
    public ?bool $cache = null;
    public ?int $cache_ttl = null;
    public ?bool $metadata_icon = null;
    public ?bool $metadata_fonts = null;
    public ?bool $metadata_title = null;
    public ?bool $metadata_hash = null;
    public ?bool $metadata_status = null;
    public ?bool $metadata_headers = null;
    public ?bool $async = null;
    public ?string $webhook_url = null;
    public ?bool $webhook_signed = null;
    public ?string $signature = null;
    public ?string $html = null;
    public ?string $format = null;
    public ?bool $full_page = null;
    public ?bool $full_page_scroll = null;
    public ?int $full_page_scroll_by_amount = null;
    public ?int $full_page_scroll_by_duration = null;
    public ?int $image_quality = null;
    public ?int $image_width = null;
    public ?int $image_height = null;
    public ?bool $omit_background = null;
    public ?bool $metadata_image_size = null;

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), static fn($v) => $v !== null);
    }
}