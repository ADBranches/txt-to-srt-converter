<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Application;

use InvalidArgumentException;
use NoviqLabs\TxtToSrt\Domain\Caption;
use NoviqLabs\TxtToSrt\Support\AutoTimingOptions;
use NoviqLabs\TxtToSrt\Support\Timecode;
use NoviqLabs\TxtToSrt\Validation\AutoTimingValidator;

final readonly class ConvertPlainTextToSrt
{
    public function __construct(private AutoTimingValidator $validator = new AutoTimingValidator())
    {
    }

    /** @return list<Caption> */
    public function captions(string $content, AutoTimingOptions $options): array
    {
        $this->validator->validate($options);
        $lines = preg_split('/\R/u', str_replace(["\r\n", "\r"], "\n", $content)) ?: [];
        $captions = [];
        $cursor = $options->startMilliseconds;
        foreach ($lines as $index => $line) {
            $text = trim($line);
            if ($text === '') {
                continue;
            }
            $end = $cursor + $options->durationMilliseconds;
            $captions[] = new Caption(
                (new Timecode($cursor))->format(),
                (new Timecode($end))->format(),
                $text,
                $index + 1,
            );
            $cursor = $end + $options->gapMilliseconds;
        }
        if ($captions === []) {
            throw new InvalidArgumentException('Plain text must contain at least one non-empty line.');
        }
        return $captions;
    }
}
