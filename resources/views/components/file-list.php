<ul class="file-list" id="file-list" aria-live="polite"><?php if (isset($result)) :
    foreach ($result->files as $file) :
        ?><li class="file-<?= $file['status'] ?>"><strong><?= htmlspecialchars($file['name']) ?></strong><span><?= htmlspecialchars($file['message']) ?></span><?php if ($file['token'] !== null) :
    ?><a href="/download?token=<?= $file['token'] ?>">Download SRT</a><?php
        endif ?></li><?php
    endforeach;
                                                        endif ?></ul>
