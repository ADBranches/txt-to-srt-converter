<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Validation;

use InvalidArgumentException;
use NoviqLabs\TxtToSrt\Support\AutoTimingOptions;

final class AutoTimingValidator
{
    public function validate(AutoTimingOptions $options): void
    {
        if ($options->startMilliseconds < 0) {
            throw new InvalidArgumentException('Start offset cannot be negative.');
        }
        if ($options->durationMilliseconds < 250 || $options->durationMilliseconds > 60000) {
            throw new InvalidArgumentException('Caption duration must be between 0.25 and 60 seconds.');
        }
        if ($options->gapMilliseconds < 0 || $options->gapMilliseconds > 10000) {
            throw new InvalidArgumentException('Caption gap must be between 0 and 10 seconds.');
        }
    }
}
