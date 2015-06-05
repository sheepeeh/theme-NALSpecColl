<?php 

// Display 750 words of exhibit text in Featured Item section of homepage
function exhibit_builder_display_random_featured_exhibit_more_text()
{
    $html = '<div id="featured-exhibit">';
    $featuredExhibit = exhibit_builder_random_featured_exhibit();
    $html .= '<h2>' . __('Featured Exhibit') . '</h2>';
    if ($featuredExhibit) {
       $html .= '<h3>' . exhibit_builder_link_to_exhibit($featuredExhibit) . '</h3>'."\n";
       $html .= '<p>'. metadata($featuredExhibit, 'description', array('no_escape'=>true, 'snippet'=>415)).'</p>';
    } else {
       $html .= '<p>' . __('You have no featured exhibits.') . '</p>';
    }
    $html .= '</div>';
    $html = apply_filters('exhibit_builder_display_random_featured_exhibit', $html);
    return $html;
}



// Sort exhibit pages by page order
function exhibit_page_compare ($a, $b) {
    return strcmp($a->order, $b->order);
}

// Put exhibit page navigation in a side div, give child lists their own class
function exhibit_builder_page_nav_side($exhibitPage = null)
{
    if (!$exhibitPage) {
        if (!($exhibitPage = get_current_record('exhibit_page', false))) {
            return;
        }
    }

    $exhibit = $exhibitPage->getExhibit();
    $eid = $exhibit->id;
    $db = get_db();

    // Get exhibit pages based on current page's parent exhibit

    $select = "
                SELECT a.*,b.title AS parent_title, c.title AS grandparent_title
                FROM omeka_exhibit_pages a 
                LEFT JOIN omeka_exhibit_pages b ON (a.parent_id = b.id)
                LEFT JOIN omeka_exhibit_pages c ON (b.parent_id = c.id)
                WHERE (a.exhibit_id=?)
                ORDER BY parent_id,`order`
                ";

    $pages = $db->getTable("ExhibitPage")->fetchObjects($select,array($eid));
    $vis = '';
    $html = '';

    if(!empty($pages)) {
        // Need a trash bin to prevent double displays
        $trash = array();
        $html = '<ul id="exhibit-nav-level1"><li><a class="exhibit-title" href="'. html_escape(exhibit_builder_exhibit_uri($exhibit)) . '">' . $exhibit->title . "</a></li>";

        foreach($pages as $page) {
            // Check the trash
            if (in_array($page['title'],$trash)==false) {
                $html .= '<li' . ($exhibitPage->id == $page->id ? ' class="current"' : '') .'><a class="exhibit-page-title" href="'. html_escape(exhibit_builder_exhibit_uri($exhibit, $page)) . '">' . $page->title . "</a></li>";
                $children = searchPages($pages,'parent_id',$page->id);

                // Check for children, build/show lists as appropriate
                if ($children) { 
                    $allIDs = array();
                    foreach ($children as $child) { array_push($allIDs,$child->id); }

                    $html .= "<li><ul class='exhibit-nav-level-2'>";
                    foreach ($children as $child) { 
                        $grandchildren = searchPages($pages,'parent_id',$child->id);
                        $grandIDs = array();

                        if ($grandchildren) {
                            foreach ($grandchildren as $gc) { array_push($grandIDs,$gc->id); array_push($allIDs,$gc->id); }
                        }

                      //  if ($exhibitPage->id != $child->parent_id && $exhibitPage->id != $child->id && in_array($exhibitPage->id,$allIDs)==false) { $vis = 'none'; }
                        
                        $html .= "<li class=\"$vis" . ($exhibitPage->id == $child->id ? " current" : '') .'"><a class="exhibit-page-title" href="'. html_escape(exhibit_builder_exhibit_uri($exhibit, $child)) . '">' . $child['title'] . "</a></li>" ; 
                        array_push($trash,$child['title']);
                        $vis = '';
                        
                        // Check for grandchildren, build/show lists as appropriate
                        if ($grandchildren) { 
                            $html .= "<li><ul class='exhibit-nav-level-3'>";
                            foreach ($grandchildren as $grandchild) { 
                                if ($exhibitPage->id != $grandchild->parent_id && $exhibitPage->id != $grandchild->id && in_array($exhibitPage->id,$grandIDs)==false ) { $vis = 'none'; }
                                $html .= "<li class=\"$vis" . ($exhibitPage->id == $grandchild->id ? " current" : '') .'"><a class="exhibit-page-title" href="'. html_escape(exhibit_builder_exhibit_uri($exhibit, $grandchild)) . '">' . $grandchild['title'] . "</a></li>" ; 
                                array_push($trash,$grandchild['title']);
                                $vis='';
                            } 
                            $html .= "</ul></li>";
                        }
                    } 
                    $html .= "</ul></li>";
                }
            }
        }
        $html .= "</ul>";
    }

    return $html;

}


// If an item appears in an exhibit, link to that exhibit on the individual item page
function link_to_related_exhibits($item) {

    $db = get_db();

    $select = "
    SELECT e.* FROM {$db->prefix}exhibits AS e
    INNER JOIN {$db->prefix}exhibit_pages AS ep on ep.exhibit_id = e.id
    INNER JOIN {$db->prefix}exhibit_page_blocks AS epb ON epb.page_id = ep.id
    INNER JOIN {$db->prefix}exhibit_block_attachments AS epba ON epba.block_id = epb.id
    WHERE e.public=1 AND epba.item_id = ?";

    $exhibits = $db->getTable("Exhibit")->fetchObjects($select,array($item->id));

    if(!empty($exhibits)) {
        $inlist = array();
        echo '<div id="exhibits" class="element"><h2>Appears in Exhibits</h2>';
        foreach($exhibits as $exhibit) {
            if (!in_array($exhibit->slug, $inlist)) {
                echo '<div class="element-text"><a href="' . url('/exhibits/show/') . $exhibit->slug . '">'.$exhibit->title.'</a></div>';
                array_push($inlist, $exhibit->slug);
            }
        }
        echo '</div>';
    }
}



// Link to referring exhibit (for item pages) or referring item page (for file pages)
function to_previous() {

    error_reporting(E_ALL ^ E_NOTICE);  // Suppress error when no referrer

    $referer = $_SERVER['HTTP_REFERER'];
    $uri = $_SERVER['REQUEST_URI'];

    if ($uri != "/exhibits" && $uri != "/exhibits") {
           if (strpos($referer, 'exhibits/show') != false && strpos($referer, '/item/') == false) {
              echo '<p><a href="' . $referer . '" title="Return to the previous page">&larrhk; Back to Exhibit</a></p>';
           } elseif (strpos($uri, 'files/show') != false) {
              echo '<p><a href="' . $referer . '" title="Return to the previous page">&larrhk; Back to Item</a></p>';
           } elseif (strpos($referer, 'items/browse') != false) {
              echo '<p><a href="' . $referer . '" title="Return to the previous page">&larrhk; Back to Search Results</a></p>';
           }   
        }
    }


// Base pagination on search results
function custom_paging() {
    error_reporting(E_ALL ^ E_NOTICE);  // Suppress error when no referrer
    
//Starts a conditional statement that determines a search has been run
    if (isset($_SERVER['QUERY_STRING'])) {

        // Sets the current item ID to the variable $current
        $current = metadata('item', 'id');

        //Break the query into an array
        parse_str($_SERVER['QUERY_STRING'], $queryarray);

        //Items don't need the page level
        unset($queryarray['page']);

        $itemIds = array();
        $list = array();
        if (isset($queryarray['query'])) {
                //We only want to browse previous and next for Items
                $queryarray['record_types'] = array('Item');
                //Get an array of the texts from the query.
                $textlist = get_db()->getTable('SearchText')->findBy($queryarray);
                //Loop through the texts ans populate the ids and records.
                foreach ($textlist as $value) {
                        $itemIds[] = $value->record_id;
                        $record = get_record_by_id($value['record_type'], $value['record_id']);
                        $list[] = $record;
                }
        }
        elseif (isset($queryarray['advanced'])) {
                if (!array_key_exists('sort_field', $queryarray))
                {
                        $queryarray['sort_field'] = 'added';
                        $queryarray['sort_dir'] = 'd';
                }
                //Get an array of the items from the query.
                $list = get_db()->getTable('Item')->findBy($queryarray);
                foreach ($list as $value) {
                        $itemIds[] = $value->id;
                        $list[] = $value;
                }
        }
        //Browsing exhibit 2 items
        elseif (strpos($_SERVER['HTTP_REFERER'],'exhibits/show/') != false) {
            $exhibit_slug = preg_replace('/(.*)(exhibits\/show\/)(\w*)(.*)/', "$3", $_SERVER['HTTP_REFERER']);
            $exhibit = get_db()->getTable('Exhibit')->findBySlug($exhibit_slug);

            $exhibit_query = "search=&advanced[0][element_id]=&advanced[0][type]=&advanced[0][terms]=&range=&collection=&type=&user=&public=&featured=&exhibit=" . $exhibit['id'] . "&submit_search=Search&sort_field=Dublin+Core%2CDate";
            parse_str($exhibit_query, $queryarray);
            unset($queryarray['page']);

             if (!array_key_exists('sort_field', $queryarray))
                {
                        $queryarray['sort_field'] = 'added';
                        $queryarray['sort_dir'] = 'd';
                }
                //Get an array of the items from the query.
                $list = get_db()->getTable('Item')->findBy($queryarray);
                foreach ($list as $value) {
                        $itemIds[] = $value->id;
                        $list[] = $value;
                }
        }                

        //Browsing all items in general
        else
        {
                if (!array_key_exists('sort_field', $queryarray))
                {
                        $queryarray['sort_field'] = 'added';
                        $queryarray['sort_dir'] = 'd';
                }
                $list = get_db()->getTable('Item')->findBy($queryarray);
                foreach ($list as $value) {
                        $itemIds[] = $value->id;
                }
        }

        //Update the query string without the page and with the sort_fields
        $updatedquery = http_build_query($queryarray);


        // Find where we currently are in the result set
        $key = array_search($current, $itemIds);

        // If we aren't at the beginning, print a Previous link
        if ($key > 0) {
            $previousItem = $list[$key - 1];
            $previousUrl = record_url($previousItem, 'show') . '?' . $updatedquery;
                $text = __('&larr; Previous Item');
            echo '<li id="previous-item" class="previous"><a href="' . html_escape($previousUrl) . '">' . $text . '</a></li>';
        }

        // If we aren't at the end, print a Next link
        if ($key >= 0 && $key < (count($list) - 1)) {
            $nextItem = $list[$key + 1];
            $nextUrl = record_url($nextItem, 'show') . '?' . $updatedquery;
                $text = __("Next Item &rarr;");
                echo '<li id="next-item" class="next"><a href="' . html_escape($nextUrl) . '">' . $text . '</a></li>';
        }
    } else {
        // If a search was not run, then the normal next/previous navigation is displayed.
        echo '<li id="previous-item" class="previous">'.link_to_previous_item_show().'</li>';
        echo '<li id="next-item" class="next">'.link_to_next_item_show().'</li>';
    }
}

function custom_paging_browse()
{    if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {

        $searchlink = record_url('item').'?' . $_SERVER['QUERY_STRING'];

        echo '<h2><a href="'.$searchlink.'">'. metadata('item', array('Dublin Core','Title')).'</a></h2>';
    }

    else
    {
        echo '<h2>'.link_to_item(metadata('item', array('Dublin Core','Title')), array('class'=>'permalink')).'</h2>';
    }
}

// Sort files by original filename
function filename_compare ($a, $b) {
    return strcmp($a->original_filename, $b->original_filename);
}



// Search a multidimensional array
function searchPages($arr,$field,$value)
    {
        $vals = array();
       foreach($arr as $key => $member)
       {
          if ( $member[$field] === $value )
             array_push($vals,$member);

       }
       return $vals;
    }



?>

