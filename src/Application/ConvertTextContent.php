<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Application;

use InvalidArgumentException;
use NoviqLabs\TxtToSrt\Formatter\SrtFormatter;
use NoviqLabs\TxtToSrt\Parser\LyricTxtParser;
use NoviqLabs\TxtToSrt\Repair\SafeInputRepairer;
use NoviqLabs\TxtToSrt\Support\AutoTimingOptions;
use NoviqLabs\TxtToSrt\Support\InputMode;
use NoviqLabs\TxtToSrt\Validation\SrtValidator;

final readonly class ConvertTextContent
{
    public function __construct(
        private LyricTxtParser $parser = new LyricTxtParser(),
        private SafeInputRepairer $repairer = new SafeInputRepairer(),
        private SrtFormatter $formatter = new SrtFormatter(),
        private SrtValidator $validator = new SrtValidator(),
        private DetectInputMode $detector = new DetectInputMode(),
        private ConvertPlainTextToSrt $plainText = new ConvertPlainTextToSrt(),
    ) {
    }

    public function convert(string $content, bool $repair = false, InputMode $mode = InputMode::Auto, ?AutoTimingOptions $options = null): string
    {
        if (!mb_check_encoding($content, 'UTF-8')) {
            throw new InvalidArgumentException('Input must be valid UTF-8.');
        }
        $resolved = $mode === InputMode::Auto ? $this->detector->detect($content) : $mode;
        if ($resolved === InputMode::PlainText) {
            $captions = $this->plainText->captions($content, $options ?? new AutoTimingOptions());
        } else {
            if ($repair) {
                $content = $this->repairer->repair($content);
            }
            $captions = $this->parser->parse(str_replace(["\r\n", "\r"], "\n", $content), $repair);
        }
        $srt = $this->formatter->format($captions);
        $this->validator->validate($srt, count($captions));
        return $srt;
    }
}
