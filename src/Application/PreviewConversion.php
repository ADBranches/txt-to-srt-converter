<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Application;

use NoviqLabs\TxtToSrt\Parser\LyricTxtParser;
use NoviqLabs\TxtToSrt\Repair\SafeInputRepairer;
use NoviqLabs\TxtToSrt\Support\AutoTimingOptions;
use NoviqLabs\TxtToSrt\Support\InputMode;
use NoviqLabs\TxtToSrt\Support\PreviewResult;
use NoviqLabs\TxtToSrt\Support\Timecode;

final readonly class PreviewConversion
{
    public function __construct(
        private LyricTxtParser $parser = new LyricTxtParser(),
        private SafeInputRepairer $repairer = new SafeInputRepairer(),
        private DetectInputMode $detector = new DetectInputMode(),
        private ConvertPlainTextToSrt $plainText = new ConvertPlainTextToSrt(),
    ) {
    }

    public function preview(string $content, bool $repair = false, InputMode $mode = InputMode::Auto, ?AutoTimingOptions $options = null): PreviewResult
    {
        $resolved = $mode === InputMode::Auto ? $this->detector->detect($content) : $mode;
        if ($resolved === InputMode::PlainText) {
            $captions = $this->plainText->captions($content, $options ?? new AutoTimingOptions());
        } else {
            if ($repair) {
                $content = $this->repairer->repair($content);
            }
            $captions = $this->parser->parse(str_replace(["\r\n", "\r"], "\n", $content), $repair);
        }
        $rows = [];
        foreach ($captions as $offset => $caption) {
            $start = Timecode::fromCanonical($caption->start)->milliseconds;
            $end = Timecode::fromCanonical($caption->end)->milliseconds;
            $rows[] = ['index' => $offset + 1, 'start' => $caption->start, 'end' => $caption->end, 'duration' => number_format(($end - $start) / 1000, 3) . ' s', 'text' => $caption->text, 'line' => $caption->sourceLine];
        }
        return new PreviewResult($rows, mb_strlen($content));
    }
}
