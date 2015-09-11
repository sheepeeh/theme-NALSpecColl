<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => 'items show')); ?>
<ul class="item-pagination navigation">
	<?php custom_paging(); ?>
</ul>
<br />
<h1><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h1>

<div id="primary">

	<?php if ((get_theme_option('Item FileGallery') == 0) && metadata('item', 'has files') == false): ?>
		<div style="text-align:center;"><p style="text-align:center;padding:10%;margin: 5% 20%; border:2px solid #ccc;"><strong>No <em>digital</em> file available.</strong><br />Please <a href="http://specialcollections.nal.usda.gov/contact-us" alt="Link to contact form." target="_blank">contact Special Collections</a> for access.</p></p></div>
	<?php endif; ?>
	<?php if ((get_theme_option('Item FileGallery') == 0) && metadata('item', 'has files')): ?>
		<?php $files = $item->Files; ?>
		<?php usort($files, 'filename_compare'); ?>
		<?php if (count($files) > 1): ?>
			<?php if (metadata('item','item_type_id') == 6) {
				echo file_markup($files[0], array('imageSize' => 'fullsize', 'imgAttributes'=>array('alt'=>'Image (illustration or photograph) for this item, linking to higher res image.', 'title'=>metadata('item', array('Dublin Core', 'Title')))));
			} elseif (metadata($files[0], 'MIME Type') == 'aplication/pdf' && metadata($files[0], 'size') < 10000 || isMobile()) {
				$html = '<div class="item-file">';
				$html += "<h2>" . metadata($files[0], 'size') . "</h2>";
				$html += '<a href="';
				$html += $files[0]->getWebPath('original');
				$html += 'title="' . metadata('Dublin Core', 'Title') . '" ';
				$html += 'alt="View the PDF.">';
				$image = file_image('fullsize', array(alt => '"Thumbnail for the first (or only) page of "' . metadata('item', array('Dublin Core', 'Title')) . '"'), $files[0]);
				$html += $image;
				$html += "</a></div>";  					
				echo $html;
				unset($html);
			} else {
				echo file_markup($files[0], array('imageSize' => 'fullsize', 'imgAttributes'=>array('alt'=>'Image for the first content page of the item, linking to the full file.', 'title'=>metadata('item', array('Dublin Core', 'Title')))));
			} 
			unset($files[0]);
			?>

			<div id="secondary-files">

				<?php if (metadata('item','item_type_id') == 6) {
					echo file_markup($files, array('imageSize' => 'thumbnail', 'imgAttributes'=>array('alt'=>'Image (illustration or photograph) for this item, linking to higher res image.', 'title'=>metadata('item', array('Dublin Core', 'Title')))));
				} else {
					foreach($files as $file) {
						echo "<div class=\"item-file\">";
						echo "<a class==\"download-file\" href=\"" . $file->getWebPath('original') .  '" title="' . metadata('item', array('Dublin Core', 'Title')) . '" alt="View the full-size file.">'. file_image('thumbnail', array(alt => 'Thumbnail of an additional file for this item.'), $file) . "</a></div>";	
					}} ?>
				</div>
			<?php else: ?>
				<?php if (metadata('item','item_type_id') == 6) {
					echo file_markup($files, array('imageSize' => 'fullsize', 'imgAttributes'=>array('alt'=>'Image (illustration or photograph) for this item, linking to higher res image.', 'title'=>metadata('item', array('Dublin Core', 'Title')))));
				} else {
					
					if (metadata($files[0], 'size') > 10000000 && metadata($files[0], 'MIME Type') == "application/pdf" || isMobile()) {
						echo "<div class=\"item-file\">";
						echo "<p><strong>This PDF is too  large to display. Click on the image below to view the PDF.</strong></p>";
						echo "<a href=\"" . $files[0]->getWebPath('original') .  '" title="' . metadata('item', array('Dublin Core', 'Title')) . '" alt="View the PDF.">'. file_image('fullsize', array(alt => 'Thumbnail for the first (or only) page of ' . metadata('item', array('Dublin Core', 'Title'))), $files[0]) . "</a></div>";	
						
					} else {
						echo file_markup($files[0], array('imageSize' => 'fullsize', 'imgAttributes'=>array('alt'=>'Image for the first content page of the item, linking to the full file.', 'title'=>metadata('item', array('Dublin Core', 'Title')))));
					}
					
				} 
				unset($files[0]); ?>

			<?php endif; ?>
		<?php endif; ?>


	<!-- The following prints a citation for this item. -->
	<div id="item-citation" class="element">
		<h2><?php echo __('Citation'); ?></h2>
		<div class="element-text"><?php echo metadata('item', 'citation', array('no_escape' => true)); ?></div>
	</div>

	<?php if ($transcription = metadata('item', array('Item Type Metadata', 'Transcription'))): ?>
		<div id="text-item-type-metadata-transcription" class="element">
			<h2><?php echo __('Transcription'); ?></h2>
			<div class="element-text"><?php echo $transcription; ?></div>
		</div>
	<?php endif; ?>

	<?php if ($biography = metadata('item', array('Item Type Metadata', 'Biographical Text'))): ?>
		<div id="person-item-type-metadata-biography" class="element">
			<h2><?php echo __('Biography'); ?></h2>
			<div class="element-text"><?php echo $biography; ?></div>
		</div>
	<?php endif; ?>

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
