<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Exception;

use RuntimeException;

final class ConversionException extends RuntimeException
{
    public static function forLine(int $line, string $reason): self
    {
        return new self(sprintf('Line %d: %s', $line, $reason));
    }
}
