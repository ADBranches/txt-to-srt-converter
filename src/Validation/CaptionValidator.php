<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Validation;

use NoviqLabs\TxtToSrt\Domain\Caption;
use NoviqLabs\TxtToSrt\Exception\ConversionException;
use NoviqLabs\TxtToSrt\Support\Timecode;

final class CaptionValidator
{
    /** @param list<Caption> $captions */
    public function validate(array $captions): void
    {
        $previous = null;
        foreach ($captions as $caption) {
            if (trim($caption->text) === '') {
                throw ConversionException::forLine($caption->sourceLine, 'caption text cannot be empty.');
            }
            $start = Timecode::fromCanonical($caption->start)->milliseconds;
            $end = Timecode::fromCanonical($caption->end)->milliseconds;
            if ($end <= $start) {
                throw ConversionException::forLine($caption->sourceLine, 'end time must be greater than start time.');
            }
            if ($previous !== null && $start < Timecode::fromCanonical($previous->end)->milliseconds) {
                throw ConversionException::forLine($caption->sourceLine, sprintf('caption overlaps line %d.', $previous->sourceLine));
            }
            $previous = $caption;
        }
    }
}
