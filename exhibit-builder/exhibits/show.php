<?php
echo head(array(
    'title' => metadata('exhibit_page', 'title') . ' &middot; ' . metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits show'));
?>

<h1><span class="exhibit-page"><?php echo metadata('exhibit_page', 'title'); ?></span></h1>

<div role="main" class="primary">
    <?php exhibit_builder_render_exhibit_page(); ?>
</div>


<nav id="exhibit-pages">
    <?php echo exhibit_builder_page_nav_sneaky(); ?>
</nav>

<?php echo foot(); ?>
