# ScreenshotMAX PHP SDK

[![test](https://github.com/screenshotmax/php-sdk/actions/workflows/test.yml/badge.svg)](https://github.com/screenshotmax/php-sdk/actions/workflows/test.yml)

This is the official PHP SDK for the [ScreenshotMAX API](https://screenshotmax.com/).

It allows you to easily capture high-quality screenshots of any URL directly from your applications.
The SDK handles authentication, request signing, and provides a simple interface to integrate ScreenshotMAX’s powerful screenshot services into your Python projects.

Get started in minutes. Just [sign up](https://screenshotmax.com) to receive your access and secret keys, import the client, and you’re ready to capture screenshots.”

The SDK client is synchronized with the latest [ScreenshotMAX API options](https://docs.screenshotmax.com/guides/start/introduction).

## Installation
```bash
composer require screenshotmax/sdk
```

## Usage

Use the SDK to generate signed or unsigned URLs for screenshots, PDFs, web scraping, or animated screenshot—without executing the request. Or fetch and download the result directly. You have full control over when and how each capture runs.

### Screenshot example
```php
use ScreenshotMax\SDK;
use ScreenshotMax\Options\ScreenshotOptions;

$sdk = new SDK('<ACCESS_KEY>', '<SECRET_KEY>');

$opts = new ScreenshotOptions();
$opts->url = 'https://example.com';
$opts->format = 'png';

$sdk->screenshot->setOptions($opts);

$url = $sdk->screenshot->getUrl();
$result = $sdk->screenshot->fetch();
file_put_contents('screenshot.png', $result['data']);
```

### Web scraping example
```php
use ScreenshotMax\SDK;
use ScreenshotMax\Options\ScrapeOptions;

$sdk = new SDK('<ACCESS_KEY>', '<SECRET_KEY>');

$opts = new ScrapeOptions();
$opts->url = 'https://example.com';
$opts->format = 'html';

$sdk->scrape->setOptions($opts);

$url = $sdk->scrape->getUrl();
$result = $sdk->scrape->fetch();
file_put_contents('scrape.html', $result['data']);
```

### PDF generation example
```php
use ScreenshotMax\SDK;
use ScreenshotMax\Options\PDFOptions;

$sdk = new SDK('<ACCESS_KEY>', '<SECRET_KEY>');

$opts = new PDFOptions();
$opts->url = 'https://example.com';
$opts->pdf_paper_format = 'a4';

$sdk->pdf->setOptions($opts);

$url = $sdk->pdf->getUrl();
$result = $sdk->pdf->fetch();
file_put_contents('pdf.pdf', $result['data']);
```

### Scheduled task example
```php
use ScreenshotMax\SDK;

$sdk = new SDK('<ACCESS_KEY>', '<SECRET_KEY>');

# get all tasks from account
$tasks = $sdk->task->get_tasks()
# {"tasks":[{
# "id":5678133109850112,
# "name":"Test CRON",
# "api":"screenshot",
# "query":
# "url=https%3A%2F%2Fexample.com",
# "frequency":"every_day",
# "crontab":"25 13 * * *",
# "timezone":"Etc/UTC",
# "enabled":true,
# "created":1747229104,
# "last_run":1748611516,
# "runs":18}]}
```

## License

`screenshotmax/sdk` is released under [the MIT license](LICENSE).
