<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Unit;

use NoviqLabs\TxtToSrt\Domain\Caption;
use NoviqLabs\TxtToSrt\Formatter\SrtFormatter;
use PHPUnit\Framework\TestCase;

final class SrtFormatterTest extends TestCase
{
    public function testFormatsSequentialSrtBlocks(): void
    {
        $result = (new SrtFormatter())->format([
            new Caption('00:00:01,000', '00:00:02,000', 'One', 1),
            new Caption('00:00:02,000', '00:00:03,000', 'Two', 2),
        ]);
        self::assertSame("1\n00:00:01,000 --> 00:00:02,000\nOne\n\n2\n00:00:02,000 --> 00:00:03,000\nTwo\n", $result);
    }
}
