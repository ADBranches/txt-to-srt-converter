<?php
/** @var NoviqLabs\TxtToSrt\Support\UntimedTextResult $untimed */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Untimed text inspection</title>
    <link rel="stylesheet" href="/assets/tokens.css">
    <link rel="stylesheet" href="/assets/app.css">
    <link rel="stylesheet" href="/assets/preview.css">
    <link rel="stylesheet" href="/assets/untimed-text.css">
</head>
<body>
<?php require __DIR__ . '/header.php'; ?>
<main class="site-main">
    <div class="container">
        <section class="workspace-card untimed-panel" aria-labelledby="untimed-title">
            <p class="eyebrow">Text inspection</p>
            <h1 id="untimed-title">No timestamps were found</h1>
            <div class="untimed-warning" role="status" aria-live="polite">
                <strong>Final SRT conversion is unavailable.</strong>
                The text is accepted for review, but synchronized caption timing cannot be determined without timing data or corresponding media.
            </div>
            <p><strong><?= count($untimed->lines) ?></strong> non-empty lines and <strong><?= $untimed->characterCount ?></strong> characters were detected.</p>
            <ol class="untimed-lines">
            <?php foreach ($untimed->lines as $line) : ?>
                <li value="<?= $line['line'] ?>"><?= htmlspecialchars($line['text'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></li>
            <?php endforeach; ?>
            </ol>
            <div class="untimed-actions">
                <a class="button-link" href="/">Add timestamps manually</a>
            </div>
            <p class="future-note">Media-assisted synchronization is not implemented in this version. No timestamps were generated or inferred.</p>
        </section>
    </div>
</main>
<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>
