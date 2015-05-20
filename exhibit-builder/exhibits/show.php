<?php
echo head(array(
    'title' => metadata('exhibit_page', 'title') . ' &middot; ' . metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits show'));

?>
<style>
#sidebar-items {
	font-size: .75em;
}

#sidebar-items > ul.items-list{
	list-style-type: none;
    background-color:rgb(222, 227, 233); !important;
    overflow:hidden;
    padding:0.5em 0em;
}

#sidebar-items > ul.items-list > li {
	clear:both;
	vertical-align: top;
    height:100%;
    padding:0.5em 0.25em;
    line-height:1.25em;
}

ul.items-list #item-title {
	float: left;
	width: 67%;
	vertical-align: top;
    height:100%;
    clear:right;
}

#sidebar-items > ul.items-list > li > div.item-file {
	float: left;
	padding: 0 0.25em 0.5em 0.25em;
	width: 55px;
    clear:left;
}

#sidebar-items > ul.items-list > li:nth-child(n+2) > div {
    padding-top:0.25em;
}

#sidebar-items > ul.items-list > li:nth-child(n+2) {
	border-top:5px solid #fff;
    margin-top:0.75em;
}


</style>
<h1><span class="exhibit-page"><?php echo metadata('exhibit_page', 'title'); ?></span></h1>


<div id="exhibit-pages">
	<nav>
		<?php echo exhibit_builder_page_nav_side(); ?>
	</nav>

	<?php $items = get_records('Item', array('tags'=> metadata('exhibit_page', 'slug')), 20); 
	set_loop_records('items', $items); ?>
	<?php if (count($items) >0): ?>
		<div id="sidebar-items">
			<ul class="items-list">
				<?php foreach (loop('items') as $item): ?>

					<li>
						<?php $files = $item->Files; ?>
						<?php usort($files, 'filename_compare'); ?>
						<?php if (count($files) > 1) {
							$use_file = $files[0];
						} else {
							$use_file = $files;
						}
						?>

						<?php if (metadata('item','item_type_id') == 6) {
							echo file_markup($use_file, array('imageSize' => 'square_thumbnail', 
								'imgAttributes'=>array('alt'=>'Image (illustration or photograph) for this item, linking to the individual item page.', 
									'height' => '50px', 
									'width' => '50px',
									'title'=>metadata('item', array('Dublin Core', 'Title')))));
						} else {
							echo file_markup($use_file, array('imageSize' => 'square_thumbnail', 
								'imgAttributes'=>array('alt'=>'Image for the first content page of the item, linking to the the individual item page.', 
									'height' => '50px', 
									'width' => '50px',
									'title'=>metadata('item', array('Dublin Core', 'Title')))));
						} 
						unset($use_file);
						?>

						<div id="item-title"><?php echo link_to_item(metadata('item', array('Dublin Core', 'Title'), array('snippet'=>50)), array('class'=>'permalink')); ?></div>
					</li>

				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif;?>
</div>

<div role="main" class="primary">
    <?php exhibit_builder_render_exhibit_page(); ?>
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
