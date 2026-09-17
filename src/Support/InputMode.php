<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Support;

enum InputMode: string
{
    case Auto = 'auto';
    case Timestamped = 'timestamped';
    case PlainText = 'plain';
}
