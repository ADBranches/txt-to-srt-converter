<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Console\ConvertCommand;
use PHPUnit\Framework\TestCase;

final class ConvertCommandTest extends TestCase
{
    private string $directory;

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir() . '/txt-to-srt-command-' . bin2hex(random_bytes(6));
        mkdir($this->directory, 0775, true);
    }

    protected function tearDown(): void
    {
        if (!is_dir($this->directory)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );

        foreach ($iterator as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }

        rmdir($this->directory);
    }

    public function testValidFileReturnsSuccessAndCreatesExpectedOutput(): void
    {
        $input = $this->directory . '/valid.txt';
        $output = $this->directory . '/output';
        copy(dirname(__DIR__) . "/Fixtures/valid-lyrics.txt", $input);

        $status = (new ConvertCommand())->run([$input, "--output-dir", $output]);

        self::assertSame(0, $status);
        self::assertFileEquals(
            dirname(__DIR__) . "/Fixtures/expected-valid.srt",
            $output . "/valid.srt",
        );
    }

    public function testInvalidUsageReturnsTwo(): void
    {
        self::assertSame(2, (new ConvertCommand())->run(["--unsupported-option"]));
    }

    public function testFailedConversionReturnsOneWithoutOutput(): void
    {
        $input = $this->directory . '/invalid.txt';
        $output = $this->directory . '/output';
        copy(dirname(__DIR__) . "/Fixtures/invalid-lyrics.txt", $input);

        $status = (new ConvertCommand())->run([$input, "--output-dir", $output]);

        self::assertSame(1, $status);
        self::assertFileDoesNotExist($output . "/invalid.srt");
    }
}
