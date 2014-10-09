<?php
echo head(array(
    'title' => metadata('exhibit_page', 'title') . ' &middot; ' . metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits show'));
?>

<h1><span class="exhibit-page"><?php echo metadata('exhibit_page', 'title'); ?></span></h1>

<div id="exhibit-pages">
	<nav>
    	<?php echo exhibit_builder_page_nav_sneaky(); ?>
    		<?php if (count(exhibit_builder_child_pages()) > 0): ?>
		<nav id="exhibit-child-pages" class="secondary-nav">
			<?php echo exhibit_builder_child_page_nav(); ?>
		</nav>
	<?php endif; ?>
	</nav>


</div>

<div role="main" class="primary">
    <?php exhibit_builder_render_exhibit_page(); ?>
</div>



<?php echo foot(); ?>
