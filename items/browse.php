<?php
$pageTitle = __('Browse Items');
echo head(array('title'=>$pageTitle,'bodyclass' => 'items browse'));
?>

<h1><?php echo $pageTitle;?> <?php echo __('(%s total)', $total_results); ?></h1>

<nav class="items-nav navigation secondary-nav">
    <?php echo public_nav_items(); ?>
</nav>

<?php echo item_search_filters(); ?>

<?php echo pagination_links(); ?>

<?php if ($total_results > 0): ?>

<?php
$sortLinks[__('Title')] = 'Dublin Core,Title';
$sortLinks[__('Creator')] = 'Dublin Core,Creator';
$sortLinks[__('Date Added')] = 'added';
?>
<div id="sort-links">
    <span class="sort-label"><?php echo __('Sort by: '); ?></span><?php echo browse_sort_links($sortLinks); ?>
</div>

<?php endif; ?>

<?php foreach (loop('items') as $item): ?>
<div class="item hentry">

    <div class="item-meta">
    <?php if (metadata('item', 'has thumbnail')): ?>
    <div class="item-img">
            <?php echo files_for_item(array('item_image' => 'square_thumbnail', 'imgAttributes' => array('alt' => 'Thumbnail for the first content page of the item, linking to the full file.' ) ) ); ?>
    </div>
    <?php endif; ?>

    <!-- Print title with link to retain paging -->
    <?php custom_paging_browse(); ?>

    <?php if ($description = metadata('item', array('Dublin Core', 'Description'), array('snippet'=>250))): ?>
    <div class="item-description"><p><strong><?php echo __('Description'); ?>:</strong>
        <?php echo $description; ?>
    </div>
    <?php endif; ?>

    <?php if ($creator = metadata('item', array('Dublin Core', 'Creator'))): ?>
    <div class="item-creator"><p><strong><?php echo __('Creators'); ?>:</strong>
           <?php if (count(metadata('item', array('Dublin Core', 'Creator'), array('all' => true))) > 1): ?>
               <?php  $contrib = metadata('item', array('Dublin Core', 'Creator'), array('all' => true)); ?>
               <?php $count = sizeof($contrib);?>
               <?php foreach ($contrib as $c): ?>
                  <?php if ($count > 1): ?>
                        <?php  echo "$c, "; ?>
                        <?php $count -= 1; ?>
                  <?php else: ?>
                        <?php  echo "$c"; ?>
                    <?php endif; ?>
               <?php endforeach; ?>

           <?php else: ?>
                <?php echo $creator; ?>
           <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($date = metadata('item', array('Dublin Core', 'Date'), array('snippet'=>250))): ?>
    <div class="item-date"><p><strong><?php echo __('Date'); ?>:</strong>
        <?php echo $date; ?>
    </div>
    <?php endif; ?>

    <?php fire_plugin_hook('public_items_browse_each', array('view' => $this, 'item' =>$item)); ?>

    </div><!-- end class="item-meta" -->
</div><!-- end class="item hentry" -->
<?php endforeach; ?>

<?php echo pagination_links(); ?>

<div id="outputs">
    <span class="outputs-label"><?php echo __('Output Formats'); ?></span>
    <?php echo output_format_list(false); ?>
</div>

<?php fire_plugin_hook('public_items_browse', array('items'=>$items, 'view' => $this)); ?>

<?php echo foot(); ?>
