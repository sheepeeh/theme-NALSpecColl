<?php
    $title = (isset($title) && $displayError)
           ? $title
           : __('Omeka has encountered an error');
?>
<?php
echo head(array('title'=>$title,'bodyclass' => 'debug'));
?>

    <div class="container container-sixteen">
        <div id="content" class="ten columns offset-by-three">
            <h1><?php echo $title; ?></h1>
            <?php if ($displayError): ?>
                <?php if (is_string($e)): ?>
                <p><?php echo nl2br($e); ?></p>
                <?php else: ?>
                <dl id="error-message">
                    <dt><?php echo get_class($e); ?></dt>
                    <dd>
                        <p><?php echo nl2br(htmlspecialchars($e->getMessage())); ?></p>
                    </dd>
                </dl>
                <pre id="backtrace"><?php echo htmlspecialchars($e); ?></pre>
                <?php endif; ?>
            <?php else: ?>
                <p><?php echo __('To learn how to see more detailed information about this error, see the Omeka Codex page on <a href="http://omeka.org/codex/Retrieving_error_messages">retrieving error messages</a>.'); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
<?php echo foot(); ?>
