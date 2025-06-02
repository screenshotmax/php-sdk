<?php
namespace ScreenshotMax\Options;

class PDFOptions extends ScreenshotOptions
{
    public ?string $pdf_paper_format = null;
    public ?bool $pdf_landscape = null;
    public ?bool $pdf_print_background = null;
}