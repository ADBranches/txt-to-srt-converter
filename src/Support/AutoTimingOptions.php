<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Support;

final readonly class AutoTimingOptions
{
    public function __construct(
        public int $startMilliseconds = 0,
        public int $durationMilliseconds = 3000,
        public int $gapMilliseconds = 0,
    ) {
    }
}
