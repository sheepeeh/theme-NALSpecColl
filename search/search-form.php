<form id="search-form" name="search-form" action="<?php echo url("/items/browse"); ?>" method="get">    <input type="text" name="search" id="query" value="" title="Search" placeholder="...enter keyword...">        
<fieldset id="advanced-form" title="advanced search fields">
<div id="search-narrow-by-fields" class="field">
        <div class="label"><?php echo __('<label for="advanced-0-element_id">Narrow by Specific Field</label>'); ?></div>
        <div class="inputs">
        <?php
        // If the form has been submitted, retain the number of search
        // fields used and rebuild the form
        if (!empty($_GET['advanced'])) {
            $search = $_GET['advanced'];
        } else {
            $search = array(array('field'=>'','type'=>'','value'=>''));
        }

        //Here is where we actually build the search form
        foreach ($search as $i => $rows): ?>
            <div class="search-entry">
                <?php
                //The POST looks like =>
                // advanced[0] =>
                //[field] = 'description'
                //[type] = 'contains'
                //[terms] = 'foobar'
                //etc
                echo $this->formSelect(
                    "advanced[$i][element_id]",
                    @$rows['element_id'],
                    array('title' => __("Select field ($i)")),
                    get_table_options('Element', null, array(
                        'record_types' => array('Item', 'All'),
                        'sort' => 'alphaBySet')
                    )
                );

                echo __('<br /><label for="advanced-0-type">Filter by</label><br />');
                echo $this->formSelect(
                    "advanced[$i][type]",
                    @$rows['type'],
                    array('title' => __("Select filter method ($i)")),
                    label_table_options(array(
                        'contains' => __('contains the search term'),
                        'does not contain' => __('does not contain  the search term'),
                        'is exactly' => __('is exactly  the search term'),
                        'is empty' => __('has no value'),
                        'is not empty' => __('has a value'))
                    )
                );
                echo __('<br /><label for="advanced-0-terms">Search Term</label><br />');
                echo $this->formText(
                    "advanced[$i][terms]",
                    @$rows['terms'],
                    array(
                        'size' => '20',
                        'title' => __("Select filter ($i)")
                    )
                );
                ?>
               
            </div>
        <?php endforeach; ?>
        </div>
       
    </div>
<p><a href="<?php echo url("/items/search?query=&amp;query_type=keyword&amp;record_types%5B%5D=Item&amp;record_types%5B%5D=File"); ?>">Advanced Search</a></p>
</fieldset>
<input type="submit" name="" value="Search"></form>

<script type="text/javascript">
    // jQuery(document).ready(function () {
    //     Omeka.Search.activateSearchButtons();
    // });

     jQuery(document).ready(function () {

    // Hide undesired terms
        var showDCTerms = [    // This is a list of all of the DC metadata elements we want to display
        'Contributor',
        'Date',
        'Description',
        'Source',
        'Subject',
        'Title',
        'Type'
        ];
    // Loop through the DC metadata elements in the HTML one by one
       var foundsw;
       jQuery("optgroup[label='Dublin Core'] > option").each ( function (index, element) {
        currentTerm = jQuery(this).text();         //Grab name of the current DC element in HTML e.g. Subject
        foundsw = false;
        for (i=0 ; i < showDCTerms.length ; i++) {
            if ( showDCTerms[i] == currentTerm ) {
                foundsw = true;
                break;
            }
        }
        if ( !foundsw ) {                           // Is the current DC element a keeper?
            jQuery(this).remove();                // No, remove it from the HTML
        }
    });                                           // end each loop
    

        var showITTerms = [    // This is a list of all of the Item Type metadata elements we want to display
        'Original Format',
        'Box',
        'Folder',
        'Series'
       ];
    // Loop through the IT metadata elements in the HTML one by one
       var foundsww;
       jQuery("optgroup[label='Item Type Metadata'] > option").each ( function (index, element) {
        currentTerm = jQuery(this).text();         //Grab name of the current IT element in HTML e.g. Subject
        foundsww = false;
        for (i=0 ; i < showITTerms.length ; i++) {
            if ( showITTerms[i] == currentTerm ) {
                foundsww = true;
                break;
            }
        }
        if ( !foundsww ) {                           // Is the current IT element a keeper?
            jQuery(this).remove();                // No, remove it from the HTML
        }
    });                                           // end each loop
        
    //Hide element set names
        jQuery('.search-entry optgroup').replaceWith(function () {
            return jQuery(this).children();
        });    

    });
</script>