<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Application\BatchConverter;
use PHPUnit\Framework\TestCase;

final class BatchConversionTest extends TestCase
{
    private string $directory;

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir() . '/txt-to-srt-batch-quality-' . bin2hex(random_bytes(6));
        mkdir($this->directory . "/input/nested", 0775, true);
        copy(dirname(__DIR__) . "/Fixtures/valid-lyrics.txt", $this->directory . "/input/valid.txt");
        copy(dirname(__DIR__) . "/Fixtures/unicode-lyrics.txt", $this->directory . "/input/nested/unicode.txt");
    }

    protected function tearDown(): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );

        foreach ($iterator as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }

        rmdir($this->directory);
    }

    public function testRecursiveBatchPreservesRelativeOutputPaths(): void
    {
        $summary = (new BatchConverter())->run(
            $this->directory . "/input",
            $this->directory . "/output",
            true,
            false,
            false,
            false,
        );

        self::assertSame(2, $summary["passed"]);
        self::assertSame(0, $summary["failed"]);
        self::assertFileExists($this->directory . "/output/valid.srt");
        self::assertFileExists($this->directory . "/output/nested/unicode.srt");
    }

    public function testDryRunCreatesNoOutputs(): void
    {
        $summary = (new BatchConverter())->run(
            $this->directory . "/input",
            $this->directory . "/dry-output",
            true,
            false,
            false,
            true,
        );

        self::assertSame(2, $summary["passed"]);
        self::assertDirectoryDoesNotExist($this->directory . "/dry-output");
    }
}
