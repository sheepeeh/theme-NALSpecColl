<?php
$pageTitle = __('Search Omeka ') . __('(%s total)', $total_results);
echo head(array('title' => $pageTitle, 'bodyclass' => 'search'));
$searchRecordTypes = get_search_record_types();
?>
<?php echo search_filters(); ?>
<?php if ($total_results): ?>
<?php echo pagination_links(); ?>
<?php 
    $querystring = $_SERVER['QUERY_STRING'];
    parse_str($querystring, $queryarray); 
?>

<?php if (array_key_exists('exhibit_id',$queryarray) && $queryarray['exhibit_id'] != ''): ?>

     <?php

        // If there's an exhibit ID in the query string, build an array of child page IDs.
         $db = get_db();

            $select = "
                        SELECT id
                        FROM omeka_exhibit_pages
                        WHERE (exhibit_id=?)
                        ORDER BY id
                        ";

            $pages = $db->getTable("ExhibitPage")->fetchObjects($select,array($queryarray['exhibit_id']));
            $page_ids = array();
            foreach ($pages as $page) {
                array_push($page_ids,$page['id']);
            }

            $page_string = join(",",$page_ids);


        
        ?>
<?php endif; ?>
<table id="search-results">
    <thead>
        <tr>
            <th  style="width:25%"><?php echo __('Record Type');?></th>
            <th><?php echo __('Title');?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach (loop('search_texts') as $searchText): ?>
            <?php 
                // If there's an array of page IDs, only show results from pages included in that array.
            if (isset($page_ids)==true && in_array($searchText['record_id'], $page_ids)==true): ?>
                    <?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
                    <tr>
                    <td><?php echo $searchRecordTypes[$searchText['record_type']]; ?></td>
                    <?php
                    // Preserve query when browsing items from search results.
                    if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']))
                    {
                        $searchlink = record_url($record, 'show').'?' . $_SERVER['QUERY_STRING'];

                    ?>



                        <td><a href="<?php echo $searchlink; ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a></td>

                    <?php
                    }
                    else
                    {

                    ?>

                        <td><a href="<?php echo record_url($record, 'show'); ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a></td>
                    <?php

                    }

                    ?>
                    </tr>


                <?php elseif (isset($page_ids) == false): ?>
                                        <?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
                    <tr>
                    
                                        <td><?php echo $searchRecordTypes[$searchText['record_type']]; ?></td>
                    <?php
                    // Preserve query when browsing items from search results.
                    if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']))
                    {
                        $searchlink = record_url($record, 'show').'?' . $_SERVER['QUERY_STRING'];

                    ?>



                        <td><a href="<?php echo $searchlink; ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a></td>

                    <?php
                    }
                    else
                    {

                    ?>

                        <td><a href="<?php echo record_url($record, 'show'); ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a></td>
                    <?php

                    }

                    ?>
                    </tr>

                
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>
<?php echo pagination_links(); ?>
<?php else: ?>
<div id="no-results">
    <p><?php echo __('Your query returned no results.');?></p>
</div>
<?php endif; ?>
<?php echo foot(); ?>