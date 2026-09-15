<?php

declare(strict_types=1);

use NoviqLabs\TxtToSrt\Controller\EditorController;
use NoviqLabs\TxtToSrt\Controller\HealthController;
use NoviqLabs\TxtToSrt\Http\CsrfTokenManager;
use NoviqLabs\TxtToSrt\Http\HttpException;
use NoviqLabs\TxtToSrt\Http\Request;
use NoviqLabs\TxtToSrt\Http\Response;
use NoviqLabs\TxtToSrt\Http\Router;
use NoviqLabs\TxtToSrt\Http\SecurityHeaders;
use NoviqLabs\TxtToSrt\Http\Session;

require dirname(__DIR__) . '/vendor/autoload.php';

$security = require dirname(__DIR__) . '/config/security.php';
$request = Request::fromGlobals();
$https = ($request->server['HTTPS'] ?? '') !== '' && ($request->server['HTTPS'] ?? '') !== 'off';
(new Session())->start($security['session'], $https);
$csrf = new CsrfTokenManager((int) $security['csrf']['token_bytes']);
$router = new Router();
$router->add('GET', '/', new EditorController($csrf));
$router->add('GET', '/health', new HealthController());
$protected = static function (Request $request) use ($csrf): Response {
    $candidate = isset($request->post['_csrf']) && is_string($request->post['_csrf'])
        ? $request->post['_csrf']
        : null;
    if (!$csrf->verify($candidate)) {
        throw new HttpException(403, 'The security token is invalid or expired.');
    }
    return Response::json(['status' => 'ready', 'route' => $request->path], 200);
};
$router->add('POST', '/preview', $protected);
$router->add('POST', '/convert', $protected);
$router->add('GET', '/download', static fn (Request $request): Response => Response::json([
    'status' => 'not_ready',
    'message' => 'Downloads are enabled in UI Phase 3.',
], 501));

try {
    $response = $router->dispatch($request);
} catch (HttpException $error) {
    $response = Response::html('<!doctype html><html lang="en"><head><meta charset="utf-8">'
        . '<title>Request error</title></head><body><main><h1>Request error</h1><p>'
        . htmlspecialchars($error->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
        . '</p></main></body></html>', $error->status);
} catch (Throwable) {
    $response = Response::html('<!doctype html><html lang="en"><head><meta charset="utf-8">'
        . '<title>Server error</title></head><body><main><h1>Server error</h1>'
        . '<p>The request could not be completed safely.</p></main></body></html>', 500);
}
(new SecurityHeaders())->apply($response)->send();
