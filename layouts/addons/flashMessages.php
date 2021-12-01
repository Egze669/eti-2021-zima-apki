<?php if ($session->hasFlashMessages()): ?>
    <?php foreach ($session->getFlashMessages() as $type => $messages): ?>
        <ul class="flash-message flash-message--<?php echo $type ?>">
            <?php foreach ($messages as $message): ?>
                <li><?php echo $message ?></li>
            <?php endforeach ?>
        </ul>
    <?php endforeach ?>
<?php endif ?>


