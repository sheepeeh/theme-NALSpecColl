<?php
$title = __('Special Collections Exhibits');
echo head(array('title' => $title, 'bodyclass' => 'exhibits browse'));
?>
<h1>All Exhibits <?php echo __('(%s total)', $total_results); ?></h1>
<?php if (count($exhibits) > 0): ?>


<nav class="navigation" id="secondary-nav">
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

<?php echo pagination_links(); ?>




<?php $exhibitCount = 0; ?>
<?php $exhibitCount = 0; ?>
<?php foreach (loop('exhibit') as $exhibit): ?>
    <?php $exhibitCount++; ?>
    <div class="exhibit <?php if ($exhibitCount%2==1) echo ' even'; else echo ' odd'; ?>">
        
        <?php if (strpos(metadata($exhibit,'title'),"Merrigan") == false): ?>
            <h2><?php echo link_to_exhibit(); ?></h2>

            <?php if ($exhibitImage = record_image($exhibit, 'square_thumbnail')): ?>
                    <?php echo exhibit_builder_link_to_exhibit($exhibit, $exhibitImage, array('class' => 'image')); ?>
            <?php endif; ?>
        
        <?php else: ?>
            <h2><a href="<?php echo url("/merrigan"); ?>" target="_blank">Dr. Kathleen Merrigan Collection</a></h2>
                
            <?php if ($exhibitImage = record_image($exhibit, 'square_thumbnail')): ?>
                    <?php echo  "<a href=\"" . url("/merrigan") . "\" target=\"_blank\" class=\"image\">" . $exhibitImage . "</a>"; ?>
            <?php endif; ?>
            
        <?php endif; ?>
    

        <?php if ($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true, 'snippet' => 750))):

            $exhibitDescription = preg_replace('/Search\sthis\sExhibit/' ,'', $exhibitDescription);
            $exhibitDescription = preg_replace('/\[exhibit_search\s.*\]/' ,'', $exhibitDescription);
            $exhibitDescription = preg_replace('/\[build_url.*\]/' ,'', $exhibitDescription);
            $exhibitDescription = preg_replace('/View\sall\sitems\sin\sthis\sexhibit./' ,'', $exhibitDescription);
            $exhibitDescription = preg_replace('/View\sall\sitems\sin\sthe.*\./' ,'', $exhibitDescription);
         ?>
                <div class="description"><?php echo $exhibitDescription; ?></div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<?php echo pagination_links(); ?>

<?php else: ?>
    <p><?php echo __('There are no exhibits available yet.'); ?></p>
<?php endif; ?>

<?php echo foot(); ?>
