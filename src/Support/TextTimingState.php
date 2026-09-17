<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Support;

enum TextTimingState: string
{
    case Timestamped = 'timestamped';
    case Untimed = 'untimed';
}
