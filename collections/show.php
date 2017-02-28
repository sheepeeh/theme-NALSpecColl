<?php
    $collectionTitle = strip_formatting(metadata('collection', array('Dublin Core', 'Title')));

    if (preg_match('/[sS]eries/',$collectionTitle)==true) {
        $items_link = 'Items in %s';
    } else {
        $items_link = 'Items in the %s Collection';
    }
?>

<?php echo head(array('title'=> $collectionTitle, 'bodyclass' => 'collections show')); ?>

<h1><?php echo $collectionTitle; ?></h1>


<div id="collection-meta">
    <!-- <p><?php echo metadata('collection', array('Dublin Core', 'Description')); ?></p> -->
    <?php if ($description = metadata('collection', array('Dublin Core', 'Description'),array('all' => true))): ?>
        <?php 
        foreach ($description as $desc) {
            echo $desc;
        }
        ?>
    <?php endif; ?>

    <?php if ($dates = metadata('collection', array('Dublin Core', 'Date'),array('all' => true))): ?>
        <?php 
        echo '<h2>Bulk Dates</h2>';
        foreach ($dates as $date) {
            echo $date;
            echo "<br />";
        }
        ?>
    <?php endif; ?>

    <?php if ($formats = metadata('collection', array('Dublin Core', 'Format'),array('all' => true))): ?>
        <?php 
        echo '<h2>Format</h2>';
        foreach ($formats as $format) {
            echo $format;
            echo "<br />";
        }
        ?>
    <?php endif; ?>

    <?php if ($subjects = metadata('collection', array('Dublin Core', 'Subject'),array('all' => true))): ?>
        <?php 
        echo '<h2>Subject</h2>';
        foreach ($subjects as $subject) {
            echo $subject;
            echo "<br />";
        }
        ?>
    <?php endif; ?>

    <?php if ($relations = metadata('collection', array('Dublin Core', 'Relation'),array('all' => true))): ?>
        <?php 
        echo '<h2>Relation</h2>';
        foreach ($relations as $relation) {
            echo $relation;
            echo "<br />";
        }
        ?>
    <?php endif; ?>
</div>

<div id="collection-items">
    <h2><?php echo link_to_items_browse(__($items_link, $collectionTitle), array('collection' => metadata('collection', 'id'))); ?></h2>
    <?php if (metadata('collection', 'total_items') > 0): ?>
        <p style="text-align:right;clear:both;"><?php echo link_to_items_browse("View all items in this collection.", array('collection' => metadata('collection', 'id'),'sort_field' => "Dublin Core,Identifier",'sort_order' => 'desc')); ?></p>
        <?php foreach (loop('items') as $item): ?>
        <?php $itemTitle = strip_formatting(metadata('item', array('Dublin Core', 'Title'))); ?>
        <div class="item hentry">
            <?php if (metadata('item', 'has thumbnail')): ?>
                <div class="item-img">
                    <?php echo link_to_item(item_image('square_thumbnail', array('alt' => $itemTitle))); ?>
                </div>
            <?php endif; ?>
            <div class="item-meta">
                <h3><?php echo link_to_item($itemTitle, array('class'=>'permalink')); ?></h3>

               <?php if ($text = metadata('item', array('Item Type Metadata', 'Transcription'), array('snippet'=>250))): ?>
                    <div class="item-description">
                        <p><?php echo $text; ?></p>
                    </div>
                <?php elseif ($description = metadata('item', array('Dublin Core', 'Description'), array('snippet'=>250))): ?>
                    <div class="item-description">
                        <?php echo $description; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <p style="text-align:right;clear:both;"><?php echo link_to_items_browse("View all items in this collection.", array('collection' => metadata('collection', 'id'),'sort_field' => "Dublin Core,Identifier",'sort_order' => 'desc')); ?></p>
    <?php else: ?>
        <p><?php echo __("Either this collection has no items, or the items are contained within its sub-collections (below)."); ?></p>
    <?php endif; ?>
</div><!-- end collection-items -->

<?php fire_plugin_hook('public_collections_show', array('view' => $this, 'collection' => $collection)); ?>

<?php echo foot(); ?>
