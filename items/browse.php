<?php
$pageTitle = __('Browse Items');
echo head(array('title'=>$pageTitle,'bodyclass' => 'items browse'));
?>

<?php parse_str($_SERVER['QUERY_STRING'], $queryarray); ?>

<?php if (array_key_exists('collection',$queryarray) && $queryarray['collection'] != ''): ?>

  <?php
  $db = get_db();
  $collection = $db->getTable('Collection')->find($queryarray['collection']);
  $collectionTitle = strip_formatting(metadata($collection, array('Dublin Core', 'Title')));
  if ($collectionTitle == '') {
    $collectionTitle = __('[Untitled]');
  }
  ?>

  <h1><?php echo $collectionTitle; ?></h1>

  <?php if ($description = metadata($collection, array('Dublin Core', 'Description'),array('index' => 0))): ?>
    <?php echo $description; ?>
  <?php endif; ?>
  <?php if ($description = metadata($collection, array('Dublin Core', 'Description'),array('index' => 1))): ?>
    <?php echo $description; ?>
  <?php endif; ?>

  <?php if ($queryarray['collection'] == 30): ?>

    <h3><?php echo __('%s Items', $total_results); ?></h3>
    <p class="disclaimer">Please note that full-text search is only available for items which have successfully had their text extracted. Due to the nature of the materials, many documents are not OCR-compatible.</p>

    <?php echo pagination_links(); ?>

    <?php echo item_search_filters(); ?>

    <?php if ($total_results > 0): ?>
      <?php
      $sortLinks[__('Title')] = 'Dublin Core,Title';
      $sortLinks[__('Creator')] = 'Dublin Core,Creator';
      $sortLinks[__('Box Number')] = 'Item Type Metadata,Box';
      ?>
      <div id="sort-links">
        <span class="sort-label"><?php echo __('Sort by: '); ?></span><?php echo browse_sort_links($sortLinks); ?>
      </div>

    <?php endif; ?>

    <?php foreach (loop('items') as $item): ?>
      <div class="ao item hentry">
        <div class="item-meta">
          <?php if (metadata('item', 'has thumbnail') || metadata('item','has files')): ?>

            <div class="ao-thumb">
              <?php echo link_to_item(item_image('square_thumbnail',array('alt' => 'Thumbnail for the first content page of the item, linking to the full file.' ))); ?>
            <?php else: ?>

              <div class="ao-no-thumb">
                <p style="text-align:center; color:#aaa;">No <em>digital</em> file available.<br />Please <a href="http://specialcollections.nal.usda.gov/contact-us" alt="Link to contact form." target="_blank">contact Special Collections</a> for access.</p>

              <?php endif; ?>
            </div>

            <?php if ($box = metadata('item', array('Item Type Metadata','Box'))): ?>
              <div class="ao-container">
                <p><strong>Box:</strong><br />
                  <?php echo $box; ?></p>
                </div>
              <?php endif; ?>  

              <?php if ($folder = metadata('item', array('Item Type Metadata','Folder'))): ?>
                <div class="ao-container">
                  <p><strong>Folder:</strong><br />
                    <?php echo $folder; ?></p>
                  </div>
                <?php endif; ?>


                <div class="ao-details">

                  <?php custom_paging_browse(); ?>

                  <?php if ($series = metadata('item', array('Item Type Metadata', 'Series'))): ?>
                    <div><p><strong>Series:</strong>
                      <?php echo $series; ?>
                    </p></div>
                  <?php endif; ?>

                  <?php if ($description = metadata('item', array('Dublin Core', 'Description'), array('snippet'=>250))): ?>
                    <div><p><strong><?php echo __('Description'); ?>:</strong>
                      <?php echo $description; ?>
                    </div>
                  <?php endif; ?>

                  <?php if ($creator = metadata('item', array('Dublin Core', 'Creator'))): ?>
                    <div><p><strong><?php echo __('Creators'); ?>:</strong>
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



                <?php if ($date = metadata('item', array('Dublin Core', 'Date'))): ?>
                  <div><p><strong><?php echo __('Date'); ?>:</strong>
                    <?php if (count(metadata('item', array('Dublin Core', 'Date'), array('all' => true))) > 1): ?>
                     <?php  $dates = metadata('item', array('Dublin Core', 'Date'), array('all' => true)); ?>
                     <?php $count = sizeof($dates);?>
                     <?php foreach ($dates as $d): ?>
                      <?php if ($count > 1): ?>
                        <?php  echo "$d, "; ?>
                        <?php $count -= 1; ?>
                      <?php else: ?>
                        <?php  echo "$d"; ?>
                      <?php endif; ?>
                    <?php endforeach; ?>

                  <?php else: ?>
                    <?php echo $date . '</p>'; ?>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>

            <?php fire_plugin_hook('public_items_browse_each', array('view' => $this, 'item' =>$item)); ?>

          </div><!-- end class="item-meta" -->
        </div><!-- end class="item hentry" -->
      <?php endforeach; ?>
  


  <?php else: ?>

    <h3><?php echo __('%s Items', $total_results); ?></h3>
    <p class="disclaimer">Please note that full-text search is only available for items which have successfully had their text extracted. Due to the nature of the materials, many documents are not OCR-compatible.</p>

    <?php echo pagination_links(); ?>

    <?php echo item_search_filters(); ?>

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
      <?php if (metadata($item,'id') != 1058): ?>
        <div class="item hentry">

          <div class="item-meta">
            <?php if (metadata('item', 'has thumbnail') || metadata('item','has files')): ?>
              <div class="item-img">
                <?php echo link_to_item(item_image('square_thumbnail',array('alt' => 'Thumbnail for the first content page of the item, linking to the full file.' ))); ?>
              <?php else: ?>

                <div class="ao-no-thumb">
                  <p>No <em>digital</em> file available. Please contact Special Collections for access.</p>

                <?php endif; ?>
              </div>

              <?php  // Print title with link to retain paging ?>
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
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>

   <?php else: ?>

    <h3><?php echo __('%s Items', $total_results); ?></h3>
    <p class="disclaimer">Please note that full-text search is only available for items which have successfully had their text extracted. Due to the nature of the materials, many documents are not OCR-compatible.</p>

    <?php echo pagination_links(); ?>

    <?php echo item_search_filters(); ?>

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
      <?php if (metadata($item,'id') != 1058): ?>
        <div class="item hentry">

          <div class="item-meta">
            <?php if (metadata('item', 'has thumbnail') || metadata('item','has files')): ?>
              <div class="item-img">
                <?php echo link_to_item(item_image('square_thumbnail',array('alt' => 'Thumbnail for the first content page of the item, linking to the full file.' ))); ?>
              <?php else: ?>

                <div class="ao-no-thumb">
                  <p style="text-align:center; color:#aaa;">No <em>digital</em> file available. Please contact Special Collections for access.</p>

                <?php endif; ?>
              </div>

              <?php  // Print title with link to retain paging ?>
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
      <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
  <?php echo pagination_links(); ?>

  <div id="outputs">
    <span class="outputs-label"><?php echo __('Output Formats for Search Results'); ?></span>
    <?php echo output_format_list(false); ?>
  </div>

  <?php fire_plugin_hook('public_items_browse', array('items'=>$items, 'view' => $this)); ?>

  <?php echo foot(); ?>