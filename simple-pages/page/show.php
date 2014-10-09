<?php echo head(array(
    'title' => metadata('simple_pages_page', 'title'),
    'bodyid' => metadata('simple_pages_page', 'slug')
)); 

$body_class = "simple_pages_page";

$title = metadata('simple_pages_page', 'title');
$sid = metadata('simple_pages_page', 'id');

switch ($sid) {
	case 4:
		$exhibit_list = true;
		break;
	case 3:
		$exhibit_list = true;
		break;
	default:
		$exhibit_list = false;
}

?>

<h1><?php echo $title; ?></h1>

<?php if ($exhibit_list == True): ?>

	<nav class="navigation exhibits browse" id="secondary-nav">
	    <?php echo nav(array(
	        array(
	            'label' => __('Browse All'),
	            'uri' => url('')
	        ),
	        array(
	            'label' => __('Image Galleries'),
	            'uri' => url('image-galleries-list')
	        ),
	        array(
	            'label' => __('Special Exhibits'),
	            'uri' => url('special-exhibits')
	        )
	    )); ?>
	</nav>

<?php endif; ?>

<?php if ($exhibit_list == false): ?>
	<div id="primary">
<?php endif; ?>
    <?php
    $text = metadata('simple_pages_page', 'text', array('no_escape' => true));
    echo $this->shortcodes($text);
    ?>
<?php if ($exhibit_list == false): ?>
	</div>
<?php endif; ?>

<?php echo foot(); ?>
