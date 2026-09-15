<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Application\ConvertTxtToSrt;
use NoviqLabs\TxtToSrt\Exception\ConversionException;
use PHPUnit\Framework\TestCase;

final class ConvertTxtToSrtTest extends TestCase
{
    private string $directory;

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir() . '/txt-to-srt-' . bin2hex(random_bytes(6));
        mkdir($this->directory);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->directory . '/*') ?: [] as $file) {
            unlink($file);
        }
        rmdir($this->directory);
    }

    public function testConvertsUtf8TxtIntoSrt(): void
    {
        $input = $this->directory . '/lyrics.txt';
        file_put_contents($input, "00:00:01,000 | 00:00:02,000 | Café 世界\n");
        $result = (new ConvertTxtToSrt())->convert($input);
        self::assertSame(1, $result->captionCount);
        self::assertStringContainsString('Café 世界', file_get_contents($result->outputFile));
    }

    public function testProtectsExistingOutput(): void
    {
        $input = $this->directory . '/lyrics.txt';
        $output = $this->directory . '/lyrics.srt';
        file_put_contents($input, "00:00:01,000 | 00:00:02,000 | Test\n");
        file_put_contents($output, 'existing');
        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage('Use --overwrite');
        (new ConvertTxtToSrt())->convert($input, $output);
    }
}
