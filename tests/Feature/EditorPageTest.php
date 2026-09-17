<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Controller\EditorController;
use NoviqLabs\TxtToSrt\Http\CsrfTokenManager;
use NoviqLabs\TxtToSrt\Http\Request;
use PHPUnit\Framework\TestCase;

final class EditorPageTest extends TestCase
{
    public function testRendersAccessibleNoJavaScriptForm(): void
    {
        $_SESSION = [];
        $response = (new EditorController(new CsrfTokenManager()))(new Request('GET', '/'));
        self::assertSame(200, $response->status);
        self::assertStringContainsString('formaction="/preview"', $response->body);
        self::assertStringContainsString('formaction="/convert"', $response->body);
        self::assertStringContainsString('Skip to editor', $response->body);
        self::assertStringContainsString('aria-live="polite"', $response->body);
    }

    public function testRendersStyledValidationErrorAndPreservesInput(): void
    {
        $_SESSION = [];
        $controller = new EditorController(new CsrfTokenManager());
        $lyrics = "bad <caption> & value\n";
        $response = $controller->render(
            $lyrics,
            422,
            'Line 1: expected START | END | TEXT.',
            true,
        );

        self::assertSame(422, $response->status);
        self::assertStringContainsString('/assets/editor.css', $response->body);
        self::assertStringContainsString('role="alert"', $response->body);
        self::assertStringContainsString('Line 1: expected START | END | TEXT.', $response->body);
        self::assertStringContainsString('bad &lt;caption&gt; &amp; value', $response->body);
        self::assertStringContainsString('name="repair" value="1" checked', $response->body);
    }
}
