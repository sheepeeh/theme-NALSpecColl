<?php echo head(array('bodyid'=>'home')); ?>

<aside id="intro" role="introduction">
    <p><?php echo option('description'); ?></p>
</aside>

<?php if (get_theme_option('Homepage Text')): ?>
<p><?php echo get_theme_option('Homepage Text'); ?></p>
<?php endif; ?>


<?php if (get_theme_option('Display Featured Item') !== '0'): ?>
<!-- Featured Item -->
<div id="featured-item">
    <h2><?php echo __('Featured Item'); ?></h2>
    <?php echo random_featured_items(1); ?>
</div><!--end featured-item-->
<?php endif; ?>

<?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>

<?php if (get_theme_option('Display Featured Collection') !== '0'): ?>
<!-- Featured Collection -->
<div id="featured-collection">
    <h2><?php echo __('Featured Collection'); ?></h2>
    <?php echo random_featured_collection(); ?>
</div><!-- end featured collection -->
<?php endif; ?>

<?php if ((get_theme_option('Display Featured Exhibit') !== '0')
        && plugin_is_active('ExhibitBuilder')
        && function_exists('exhibit_builder_display_random_featured_exhibit')): ?>
<!-- Featured Exhibit -->
<?php echo exhibit_builder_display_random_featured_exhibit(); ?>
<?php endif; ?>

<?php
$recentItems = get_theme_option('Homepage Recent Items');
if ($recentItems === null || $recentItems === ''):
    $recentItems = 3;
else:
    $recentItems = (int) $recentItems;
endif;
if ($recentItems):
?>
<div id="recent-items">
    <h2><?php echo __('Recently Added Items'); ?></h2>
    <?php
    $homepageRecentItems = (int)get_theme_option('Homepage Recent Items') ? get_theme_option('Homepage Recent Items') : '3';
    set_loop_records('items', get_recent_items($homepageRecentItems));
    if (has_loop_records('items')):
    ?>
    <ul class="items-list">
    <?php foreach (loop('items') as $item): ?>
    <li class="item">

        <?php if (metadata('item', 'has thumbnail')): ?>
            <?php echo files_for_item(array('item_image' => 'square_thumbnail', 'imgAttributes' => array('alt' => 'Thumbnail for the first content page of the item, linking to the full file.' ))); ?>
        <?php endif; ?>
        <h3><?php echo link_to_item(metadata('item', array('Dublin Core', 'Title'), array('snippet'=>150)), array('class'=>'permalink')); ?></h3>
        <?php if($itemDescription = metadata('item', array('Dublin Core', 'Description'), array('snippet'=>150))): ?>
            <p class="item-description"><?php echo $itemDescription; ?></p>
        <?php endif; ?>

    </li>
    <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <p><?php echo __('No recent items available.'); ?></p>
    <?php endif; ?>
    <p class="view-items-link"><a href="<?php echo url("/items/browse?="); ?>">View All Items</a></p>
</div><!-- end recent-items -->
<?php endif; ?>



<?php fire_plugin_hook('public_home', array('view' => $this)); ?>

<?php echo foot(); ?>
