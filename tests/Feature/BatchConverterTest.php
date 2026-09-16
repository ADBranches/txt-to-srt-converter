<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Application\BatchConverter;
use PHPUnit\Framework\TestCase;

final class BatchConverterTest extends TestCase
{
    private string $root;
    protected function setUp(): void
    {
        $this->root = sys_get_temp_dir() . '/batch-' . bin2hex(random_bytes(5));
        mkdir($this->root);
        mkdir($this->root . '/nested');
        file_put_contents($this->root . '/a.txt', "00:00:01,000 | 00:00:02,000 | A\n");
        file_put_contents($this->root . '/nested/b.txt', "00:00:02,000 | 00:00:03,000 | B\n");
    }
    protected function tearDown(): void
    {
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->root, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($it as $f) {
            $f->isDir() ? rmdir($f->getPathname()) : unlink($f->getPathname());
        } rmdir($this->root);
    }
    public function testDirectoryRecursiveAndDryRun(): void
    {
        $batch = new BatchConverter();
        $non = $batch->run($this->root, $this->root . '/out', false, false, false, false);
        self::assertSame(1, $non['passed']);
        self::assertFileExists($this->root . '/out/a.srt');
        $dry = $batch->run($this->root, $this->root . '/dry', true, false, false, true);
        self::assertSame(2, $dry['passed']);
        self::assertDirectoryDoesNotExist($this->root . '/dry');
    }
    public function testExistingOutputIsSkipped(): void
    {
        file_put_contents($this->root . '/a.srt', 'old');
        $r = (new BatchConverter())->run($this->root, null, false, false, false, false);
        self::assertSame(1, $r['skipped']);
        self::assertSame('old', file_get_contents($this->root . '/a.srt'));
    }
}
