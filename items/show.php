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

			} elseif (metadata($files[0], 'MIME Type') == 'aplication/pdf' && metadata($files[0], 'size') < 10000) {

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
				echo "<h2>" . metadata($files[0], 'size') . "</h2>";
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
					
					// $html = '<div class="item-file">';
					// $html += "<h2>" . metadata($files[0], 'size') . "LARGER" . metadata($files[0], 'MIME Type') ."</h2>";
					// "<a href=\"" . $files[0]->getWebPath('original') .  'title="' . metadata('item', array('Dublin Core', 'Title')) . '" alt="View the PDF.">';
     //                $image = file_image('fullsize', array(alt => '"Thumbnail for the first (or only) page of "' . metadata('item', array('Dublin Core', 'Title')) . '"'), $files[0]);
     //                $html += $image;
     //                $html += "</a></div>";  			

					$sizeTest = null;
					if(metadata($files[0], 'size') < 10000000) { 
						$sizeTest = "true"; 
					} else { 
						$sizeTest = "false";
					}

					$pdfTest = null;
					if(metadata($files[0], 'MIME Type') == "application/pdf") { 
						$pdfTest = "true"; 
					} else { 
						$pdfTest = "false";
					} 
					// if ($sizeTest == "false" && $pdfTest == "true")

					if (metadata($files[0], 'size') > 10000000 && metadata($files[0], 'MIME Type') == "application/pdf") {
					echo "<div>"	;
					echo "<p><span><strong>MIMEtype: </strong>" . metadata($files[0], 'MIME Type') . "</span></p>";
					echo "<p><span><strong>Size: </strong>" . metadata($files[0], 'size') . " (Type: " . gettype(metadata($files[0], 'size')) . ")</span></p>";
					echo "<p><span><strong>Is less than 10000000? </strong>" . $sizeTest . "</span></p>";
					echo "<p><span><strong>Is a PDF? </strong>" . $pdfTest . "</span></p>";
					echo "<p>This is supposed to be for if the filesize is than 10000000.</p>";
					echo "<p><strong>Path: </strong>". $files[0]->getWebPath('original'). "</div>";
					echo "<div class=\"item-file\">" . "<a href=\"" . $files[0]->getWebPath('original') .  '" title="' . metadata('item', array('Dublin Core', 'Title')) . '" alt="View the PDF.">'. file_image('fullsize', array(alt => 'Thumbnail for the first (or only) page of ' . metadata('item', array('Dublin Core', 'Title'))), $files[0]) . "</a></div>";
					

					
				} else {

					// $sizeTest = null;
					// if(metadata($files[0], 'size') < 100000) { 
					// 	$sizeTest2 = "true"; 
					// } else { 
					// 	$sizeTest2 = "false";
					// } 

					// $pdfTest2 = null;
					// if(metadata($files[0], 'MIME Type') == "application/pdf") { 
					// 	$pdfTest2 = "true"; 
					// } else { 
					// 	$pdfTest2 = "false";
					// } 

					echo "<div>"	;
					echo  "<p><span><strong>MIMEtype: </strong>" . metadata($files[0], 'MIME Type') . "</span></p>";
					echo  "<p><span><strong>Size: </strong>" . metadata($files[0], 'size') . " (Type: " . gettype(metadata($files[0], 'size')) . ")</span></p>";
					echo  "<p><span><strong>Is less than 10000?</strong> " . $sizeTest . "</span></p>";
					echo "<p>This is supposed to be for if the filesize is less than than 10000000.</p>";
					echo "<p><span><strong>Is a PDF? </strong>" . $pdfTest . "</span></p>";
					echo file_markup($files[0], array('imageSize' => 'fullsize', 'imgAttributes'=>array('alt'=>'Image for the first content page of the item, linking to the full file.', 'title'=>metadata('item', array('Dublin Core', 'Title')))));
					
					echo "</div>";

}
				// echo "<h2>" . metadata($files[0], 'size') . "SMALLER" . metadata($files[0], 'MIME Type') . " " . gettype(metadata($files[0], 'size'))."</h2>";
				// 	
			

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
