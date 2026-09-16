<?php

declare(strict_types=1);

use NoviqLabs\TxtToSrt\Support\ZipArchiveBuilder;
use NoviqLabs\TxtToSrt\Controller\BatchConversionController;
use NoviqLabs\TxtToSrt\Controller\ArchiveDownloadController;
use NoviqLabs\TxtToSrt\Application\ConvertUploadedBatch;
use NoviqLabs\TxtToSrt\Application\ConvertTextContent;
use NoviqLabs\TxtToSrt\Application\PreviewConversion;
use NoviqLabs\TxtToSrt\Controller\ConversionController;
use NoviqLabs\TxtToSrt\Controller\DownloadController;
use NoviqLabs\TxtToSrt\Controller\EditorController;
use NoviqLabs\TxtToSrt\Controller\HealthController;
use NoviqLabs\TxtToSrt\Controller\PreviewController;
use NoviqLabs\TxtToSrt\Http\CsrfTokenManager;
use NoviqLabs\TxtToSrt\Http\HttpException;
use NoviqLabs\TxtToSrt\Http\Request;
use NoviqLabs\TxtToSrt\Http\Response;
use NoviqLabs\TxtToSrt\Http\Router;
use NoviqLabs\TxtToSrt\Http\SecurityHeaders;
use NoviqLabs\TxtToSrt\Http\Session;
use NoviqLabs\TxtToSrt\Support\SafeFilename;
use NoviqLabs\TxtToSrt\Validation\UploadValidator;

require dirname(__DIR__) . '/vendor/autoload.php';
$security = require dirname(__DIR__) . '/config/security.php';
$request = Request::fromGlobals();
(new Session())->start($security['session'], false);
$csrf = new CsrfTokenManager((int)$security['csrf']['token_bytes']);
$router = new Router();
$router->add('GET', '/', new EditorController($csrf));
$router->add('GET', '/health', new HealthController());
$validator = new UploadValidator((int)$security['uploads']['max_single_bytes'], $security['uploads']['allowed_extensions'], $security['uploads']['allowed_mime_types']);
$convert = new ConversionController(new ConvertTextContent(), $validator, new SafeFilename());
$protected = static function (Request $request) use ($csrf, $convert): Response {
    $candidate = is_string($request->post['_csrf'] ?? null) ? $request->post['_csrf'] : null;
    if (!$csrf->verify($candidate)) {
        throw new HttpException(403, 'The security token is invalid or expired.');
    }return $convert($request);
};
$router->add('POST', '/convert', $protected);
$previewController = new PreviewController(new PreviewConversion());

$previewProtected = static function (Request $request) use ($csrf, $previewController): Response {
    $candidate = is_string($request->post['_csrf'] ?? null)
        ? $request->post['_csrf']
        : null;

    if (!$csrf->verify($candidate)) {
        throw new HttpException(
            403,
            'The security token is invalid or expired.'
        );
    }

    return $previewController($request);
};

$router->add('POST', '/preview', $previewProtected);
$router->add(
    'GET',
    '/batch',
    static function (Request $request) use ($csrf): Response {
        $csrfToken = $csrf->token();
        ob_start();
        require dirname(__DIR__) . '/resources/views/batch.php';
        return Response::html((string) ob_get_clean());
    }
);

$batchService = new ConvertUploadedBatch(
    new ConvertTextContent(),
    $validator,
    new SafeFilename(),
    (int) $security['uploads']['max_batch_count'],
    (int) $security['uploads']['max_batch_total_bytes'],
);
$batchController = new BatchConversionController(
    $batchService,
    new ZipArchiveBuilder(),
);
$batchProtected = static function (Request $request) use ($csrf, $batchController): Response {
    $candidate = is_string($request->post['_csrf'] ?? null)
        ? $request->post['_csrf']
        : null;

    if (!$csrf->verify($candidate)) {
        throw new HttpException(403, 'The security token is invalid or expired.');
    }

    return $batchController($request);
};
$router->add('POST', '/batch/convert', $batchProtected);
$router->add('GET', '/batch/archive', new ArchiveDownloadController());
$router->add('GET', '/download', new DownloadController());
try {
    $response = $router->dispatch($request);
} catch (HttpException $error) {
    $response = Response::html('<!doctype html><html lang="en"><body><main><h1>Request error</h1><p>' . htmlspecialchars($error->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p><a href="/">Return to editor</a></main></body></html>', $error->status);
} catch (Throwable) {
    $response = Response::html('<!doctype html><html lang="en"><body><main><h1>Server error</h1><p>The request could not be completed safely.</p></main></body></html>', 500);
} (new SecurityHeaders())->apply($response)->send();
