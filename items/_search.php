<?php
$pageTitle = __('Search Items');
echo head(array('title' => $pageTitle,
           'bodyclass' => 'items advanced-search'));
?>

<h2><?php echo $pageTitle; ?></h2>
<p class="disclaimer">Please note that full-text search is only available for items which have successfully had their text extracted. Due to the nature of the materials, many documents are not OCR-compatible.</p>

<?php echo $this->partial('items/search-form.php',
    array('formAttributes' =>
        array('id'=>'advanced-search-form'))); ?>

<?php echo foot(); ?>
