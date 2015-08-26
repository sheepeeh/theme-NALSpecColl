<?php
echo head(array(
    'title' => metadata('exhibit_page', 'title') . ' &middot; ' . metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits show'));

?>

<h1><span class="exhibit-page"><?php echo metadata('exhibit_page', 'title'); ?></span></h1>


<div id="exhibit-pages">
	<nav>
		<?php echo exhibit_builder_page_nav_side(); ?>
	</nav>
</div>

<div role="main" class="primary">
    <?php NALLayoutsPlugin::nal_exhibit_builder_render_exhibit_page(); ?>
</div>


<div id="exhibit-page-navigation">
	<?php if ($prevLink = exhibit_builder_link_to_previous_page()): ?>
		<div id="exhibit-nav-prev">
		<?php echo $prevLink; ?>
		</div>
	<?php endif; ?>
	<?php if ($nextLink = exhibit_builder_link_to_next_page()): ?>
		<div id="exhibit-nav-next">
		<?php echo $nextLink; ?>
		</div>
	<?php endif; ?>
	<div id="exhibit-nav-up">
		<?php echo exhibit_builder_page_trail(); ?>
	</div>
</div>
<?php echo foot(); ?>
