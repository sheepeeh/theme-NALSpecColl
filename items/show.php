<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => 'items show')); ?>
<ul class="item-pagination navigation">
	<?php custom_paging(); ?>
</ul>
<br />
<h1><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h1>

<div id="primary">

	<?php if ((get_theme_option('Item FileGallery') == 0) && metadata('item', 'has files') == false): ?>
		<div style="text-align:center;"><p style="text-align:center;padding:10%;margin: 5% 25%; border:2px solid #ccc;"><em><strong>No file available</strong><br />Please <a href="http://specialcollections.nal.usda.gov/contact-us-0" target="_blank">contact Special Collections</a> for more information.</em></p></div>
	<?php endif; ?>

	<?php if ((get_theme_option('Item FileGallery') == 0) && metadata('item', 'has files')): ?>
		<?php $files = $item->Files; ?>
		<?php usort($files, 'filename_compare'); ?>
		<?php if (count($files) > 1): ?>
		<?php if (metadata('item','item_type_id') == 6) {
				echo file_markup($files[0], array('imageSize' => 'fullsize', 'imgAttributes'=>array('alt'=>'Image (illustration or photograph) for this item, linking to higher res image.', 'title'=>metadata('item', array('Dublin Core', 'Title')))));
			} else {
				echo file_markup($files[0], array('imageSize' => 'fullsize', 'imgAttributes'=>array('alt'=>'Image for the first content page of the item, linking to the full file.', 'title'=>metadata('item', array('Dublin Core', 'Title')))));
			} 
			unset($files[0]);
			?>

			<div id="secondary-files">

			<?php if (metadata('item','item_type_id') == 6) {
				echo file_markup($files, array('imageSize' => 'thumbnail', 'imgAttributes'=>array('alt'=>'Image (illustration or photograph) for this item, linking to higher res image.', 'title'=>metadata('item', array('Dublin Core', 'Title')))));
			} else {
				echo file_markup($files, array('imageSize' => 'thumbnail', 'imgAttributes'=>array('alt'=>'Image for the first content page of the item, linking to the full file.', 'title'=>metadata('item', array('Dublin Core', 'Title')))));
			} ?>
			</div>
		<?php else: ?>
			<?php if (metadata('item','item_type_id') == 6) {
				echo file_markup($files, array('imageSize' => 'fullsize', 'imgAttributes'=>array('alt'=>'Image (illustration or photograph) for this item, linking to higher res image.', 'title'=>metadata('item', array('Dublin Core', 'Title')))));
			} else {
				echo file_markup($files, array('imageSize' => 'fullsize', 'imgAttributes'=>array('alt'=>'Image for the first content page of the item, linking to the full file.', 'title'=>metadata('item', array('Dublin Core', 'Title')))));
			} ?>


		<?php endif; ?>
	<?php endif; ?>



	<!-- The following prints a citation for this item. -->
	<div id="item-citation" class="element">
		<h2><?php echo __('Citation'); ?></h2>
		<div class="element-text"><?php echo metadata('item', 'citation', array('no_escape' => true)); ?></div>
	</div>

	<?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>

</div><!-- end primary -->

<aside id="sidebar">

	<!-- The following returns all of the files associated with an item. -->
	<?php if ((get_theme_option('Item FileGallery') == 1) && metadata('item', 'has files')): ?>
		<div id="itemfiles" class="element">
			<h2><?php echo __('Files'); ?></h2>
			<?php echo item_image_gallery(); ?>
		</div>
	<?php endif; ?>


	<!-- Prints all metadata -->
	<?php echo all_element_texts('item'); ?>

	<!-- If the item belongs to a collection, the following creates a link to that collection. -->
	<?php if (metadata('item', 'Collection Name')): ?>
		<div id="collection" class="element">
			<h2><?php echo __('Collection'); ?></h2>
			<div class="element-text"><p><?php echo link_to_collection_for_item(); ?></p></div>
		</div>
	<?php endif; ?>


	<?php echo link_to_related_exhibits($item); ?>


	<div id="previous-page">
		<?php echo to_previous() ?>
	</div>
</aside>

<ul class="item-pagination navigation">
	<?php custom_paging(); ?>
</ul>

<?php echo foot(); ?>
