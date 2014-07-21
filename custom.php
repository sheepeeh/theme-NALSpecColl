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


// Hide in-progress exhibit pages
function exhibit_builder_page_nav_sneaky($exhibitPage = null)
{
    if (!$exhibitPage) {
        if (!($exhibitPage = get_current_record('exhibit_page', false))) {
            return;
        }
    }

    $exhibit = $exhibitPage->getExhibit();
    $html = '<ul class="exhibit-page-nav navigation" id="secondary-nav">' . "\n";
    $pagesTrail = $exhibitPage->getAncestors();
    $pagesTrail[] = $exhibitPage;
    $html .= '<li>';
    $html .= '<a class="exhibit-title" href="'. html_escape(exhibit_builder_exhibit_uri($exhibit)) . '">';
    $html .= html_escape($exhibit->title) .'</a></li>' . "\n";
    foreach ($pagesTrail as $page) {
        $linkText = $page->title;
        $pageExhibit = $page->getExhibit();
        $pageParent = $page->getParent();
        $pageSiblings = ($pageParent ? exhibit_builder_child_pages($pageParent) : $pageExhibit->getTopPages());

        $html .= "<li>\n<ul>\n";
        foreach ($pageSiblings as $pageSibling) {
          if ($pageSibling->title != "Items in the Exhibit" && $pageSibling->title != "In Progress") {
            $html .= '<li' . ($pageSibling->id == $page->id ? ' class="current"' : '') . '>';
            $html .= '<a class="exhibit-page-title" href="' . html_escape(exhibit_builder_exhibit_uri($exhibit, $pageSibling)) . '">';
            $html .= html_escape($pageSibling->title) . "</a></li>\n"; }
        }
        $html .= "</ul>\n</li>\n";
    }
    $html .= '</ul>' . "\n";
    $html = apply_filters('exhibit_builder_page_nav', $html);
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
    WHERE epba.item_id = ?";

    $exhibits = $db->getTable("Exhibit")->fetchObjects($select,array($item->id));

    if(!empty($exhibits)) {
        $inlist = array();
        echo '<div id="related-exhibits" class="element">';
        echo '<h3>Appears in Exhibits</h3>';
        foreach($exhibits as $exhibit) {
            if (!in_array($exhibit->slug, $inlist)) {
                echo '<p><a href="/exhibits/exhibits/show/'.$exhibit->slug.'">'.$exhibit->title.'</a></p>';
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
        elseif (strpos($_SERVER['HTTP_REFERER'],'exhibits/show/poster-collections') != false) {
            $exhibit_query = "search=&advanced[0][element_id]=&advanced[0][type]=&advanced[0][terms]=&range=&collection=&type=&user=&public=&featured=&exhibit=2&submit_search=Search&sort_field=Dublin+Core%2CDate";
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

        //Browsing exhibit 3 items
        elseif (strpos($_SERVER['HTTP_REFERER'],'exhibits/show/smokey-bear') != false) {
            $exhibit_query = "search=&advanced[0][element_id]=&advanced[0][type]=&advanced[0][terms]=&range=&collection=&type=&user=&public=&featured=&exhibit=3&submit_search=Search&sort_field=Dublin+Core%2CDate";
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

        //Browsing exhibit 4 items
        elseif (strpos($_SERVER['HTTP_REFERER'],'exhibits/show/popcorn') != false) {
            $exhibit_query = "search=&advanced[0][element_id]=&advanced[0][type]=&advanced[0][terms]=&range=&collection=&type=&user=&public=&featured=&exhibit=4&submit_search=Search&sort_field=Dublin+Core%2CDate";
            parse_str($exhibit_query, $queryarray);
            unset($queryarray['page']);

             // if (!array_key_exists('sort_field', $queryarray))
             //    {
             //            $queryarray['sort_field'] = 'added';
             //            $queryarray['sort_dir'] = 'd';
             //    }
                //Get an array of the items from the query.
                $list = get_db()->getTable('Item')->findBy($queryarray);
                foreach ($list as $value) {
                        $itemIds[] = $value->id;
                        $list[] = $value;
                }

        }

        //Browsing exhibit 5 items
        elseif (strpos($_SERVER['HTTP_REFERER'],'exhibits/show/the-american-dairy-industry') != false) {
            $exhibit_query = "search=&advanced[0][element_id]=&advanced[0][type]=&advanced[0][terms]=&range=&collection=&type=&user=&public=&featured=&exhibit=5&submit_search=Search&sort_field=Dublin+Core%2CDate";
            parse_str($exhibit_query, $queryarray);
            unset($queryarray['page']);

             // if (!array_key_exists('sort_field', $queryarray))
             //    {
             //            $queryarray['sort_field'] = 'added';
             //            $queryarray['sort_dir'] = 'd';
             //    }
                //Get an array of the items from the query.
                $list = get_db()->getTable('Item')->findBy($queryarray);
                foreach ($list as $value) {
                        $itemIds[] = $value->id;
                        $list[] = $value;
                }

        }

        //Browsing exhibit 6 items
        elseif (strpos($_SERVER['HTTP_REFERER'],'exhibits/show/an-illustrated-expedition') != false) {
            $exhibit_query = "search=&advanced[0][element_id]=&advanced[0][type]=&advanced[0][terms]=&range=&collection=&type=&user=&public=&featured=&exhibit=6&submit_search=Search&sort_field=Dublin+Core%2CDate";
            parse_str($exhibit_query, $queryarray);
            unset($queryarray['page']);

             // if (!array_key_exists('sort_field', $queryarray))
             //    {
             //            $queryarray['sort_field'] = 'added';
             //            $queryarray['sort_dir'] = 'd';
             //    }
                //Get an array of the items from the query.
                $list = get_db()->getTable('Item')->findBy($queryarray);
                foreach ($list as $value) {
                        $itemIds[] = $value->id;
                        $list[] = $value;
                }

        }

        //Browsing exhibit 7 items
        elseif (strpos($_SERVER['HTTP_REFERER'],'exhibits/show/frank-meyer') != false) {
            $exhibit_query = "search=&advanced[0][element_id]=&advanced[0][type]=&advanced[0][terms]=&range=&collection=&type=&user=&public=&featured=&exhibit=7&submit_search=Search&sort_field=Dublin+Core%2CDate";
            parse_str($exhibit_query, $queryarray);
            unset($queryarray['page']);

             // if (!array_key_exists('sort_field', $queryarray))
             //    {
             //            $queryarray['sort_field'] = 'added';
             //            $queryarray['sort_dir'] = 'd';
             //    }
                //Get an array of the items from the query.
                $list = get_db()->getTable('Item')->findBy($queryarray);
                foreach ($list as $value) {
                        $itemIds[] = $value->id;
                        $list[] = $value;
                }

        }

        //Browsing exhibit 8 items
        elseif (strpos($_SERVER['HTTP_REFERER'],'exhibits/show/micr-exhibits') != false) {
            $exhibit_query = "search=&advanced[0][element_id]=&advanced[0][type]=&advanced[0][terms]=&range=&collection=&type=&user=&public=&featured=&exhibit=8&submit_search=Search&sort_field=Dublin+Core%2CDate";
            parse_str($exhibit_query, $queryarray);
            unset($queryarray['page']);

             // if (!array_key_exists('sort_field', $queryarray))
             //    {
             //            $queryarray['sort_field'] = 'added';
             //            $queryarray['sort_dir'] = 'd';
             //    }
                //Get an array of the items from the query.
                $list = get_db()->getTable('Item')->findBy($queryarray);
                foreach ($list as $value) {
                        $itemIds[] = $value->id;
                        $list[] = $value;
                }

        }

       //Browsing exhibit 9 items
        elseif (strpos($_SERVER['HTTP_REFERER'],'exhibits/show/rare-books') != false) {
            $exhibit_query = "search=&advanced[0][element_id]=&advanced[0][type]=&advanced[0][terms]=&range=&collection=&type=&user=&public=&featured=&exhibit=9&submit_search=Search&sort_field=Dublin+Core%2CDate";
            parse_str($exhibit_query, $queryarray);
            unset($queryarray['page']);

             // if (!array_key_exists('sort_field', $queryarray))
             //    {
             //            $queryarray['sort_field'] = 'added';
             //            $queryarray['sort_dir'] = 'd';
             //    }
                //Get an array of the items from the query.
                $list = get_db()->getTable('Item')->findBy($queryarray);
                foreach ($list as $value) {
                        $itemIds[] = $value->id;
                        $list[] = $value;
                }

        }

        //Browsing exhibit 10 items
        elseif (strpos($_SERVER['HTTP_REFERER'],'exhibits/show/nusery-and-seed-trade-catalog') != false) {
            $exhibit_query = "search=&advanced[0][element_id]=&advanced[0][type]=&advanced[0][terms]=&range=&collection=&type=&user=&public=&featured=&exhibit=10&submit_search=Search&sort_field=Dublin+Core%2CDate";
            parse_str($exhibit_query, $queryarray);
            unset($queryarray['page']);

             // if (!array_key_exists('sort_field', $queryarray))
             //    {
             //            $queryarray['sort_field'] = 'added';
             //            $queryarray['sort_dir'] = 'd';
             //    }
                //Get an array of the items from the query.
                $list = get_db()->getTable('Item')->findBy($queryarray);
                foreach ($list as $value) {
                        $itemIds[] = $value->id;
                        $list[] = $value;
                }

        }

        //Browsing exhibit 11 items
        elseif (strpos($_SERVER['HTTP_REFERER'],'exhibits/show/manuscript-collections') != false) {
            $exhibit_query = "search=&advanced[0][element_id]=&advanced[0][type]=&advanced[0][terms]=&range=&collection=&type=&user=&public=&featured=&exhibit=11&submit_search=Search&sort_field=Dublin+Core%2CDate";
            parse_str($exhibit_query, $queryarray);
            unset($queryarray['page']);

             // if (!array_key_exists('sort_field', $queryarray))
             //    {
             //            $queryarray['sort_field'] = 'added';
             //            $queryarray['sort_dir'] = 'd';
             //    }
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
        $updatedquery = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $updatedquery);

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
?>