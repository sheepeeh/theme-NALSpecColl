<!DOCTYPE html>
<html class="<?php echo get_theme_option('Style Sheet'); ?>" lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ($description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>">
    <?php endif; ?>

    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <!-- Plugin Stuff -->
    <?php fire_plugin_hook('public_head', array('view'=>$this)); ?>

    <!-- Stylesheets -->
    <?php
    queue_css_url('//fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic');
    queue_css_file(array('iconfonts', 'normalize'), 'screen');
    queue_css_file('style');
    queue_css_file('nal_header');
    echo head_css();
    ?>

    <!-- JavaScripts -->
    <?php queue_js_file('vendor/modernizr'); ?>
    <?php queue_js_file('vendor/selectivizr'); ?>
    <?php queue_js_file('jquery-extra-selectors'); ?>
    <?php queue_js_file('vendor/respond'); ?>
    <?php queue_js_file('globals'); ?>
    <?php if ($_SERVER['SERVER_NAME'] == 'www.nal.usda.gov') {
        queue_js_file('ga');
        queue_js_url('//www.nal.usda.gov/sites/all/themes/gatag.js');
    } ?>



    <?php echo head_js(); ?>
</head>
<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
    <div id="wrap">
        <a class="hidden" alt="Skip to content." href="#content">Skip to content.</a><!--Skip link for screen readers. -->
        <header id="header-back">
            <!-- Begin NAL main header -->
                 <header class="header" id="header" role="banner">

                    <div id="sub-nav-container">
                        <nav class="header__secondary-menu" id="sub-links" role="navigation">
                        <ul id="secondary-menu-sub-links" class="links inline clearfix">
                        <li class="menu-432 first"><a href="http://www.nal.usda.gov/ask-question" title="Ask a Question" id="ask" target="_blank">Ask</a></li>
                        <li class="menu-433"><a target="_blank" href="http://www.nal.usda.gov/contact-us" title="Contact Us" id="contact" target="_blank">Contact</a>
                        </li>
                        <li class="menu-434 last"><a target="_blank" href="http://www.nal.usda.gov/visit-library" title="Visit the Library" id="visit" target="_blank">Visit</a>
                        </li>
                        </ul>
                        </nav>
                     </div>
             


                   <div id="header_logo_site_info">
    
                        <div class="logo-col">
                             <a href="/" title="United States Department of Agriculture" class="header__logo" id="logo"><img src="http://www.nal.usda.gov/sites/all/themes/nal/images/usdalogocolor.png" alt="United States Department of Agriculture" class="header__logo-image"></a>
                        </div>

                        <div class="header__name-and-slogan" id="name-and-slogan">
                            <div>
                                <h2 class="header__site-slogan" id="site-slogan">
                                    <a href="/" title="United States Department of Agriculture"><span>United States Department of Agriculture</span></a>
                                </h2>
                            </div>  

                          <div>
                            <h1 class="header__site-name" id="site-name">
                                <a href="/" title="National Agricultural Library" class="header__site-link" rel="home"><span>National Agricultural Library</span></a>
                            </h1>
                        </div>

                    </div><!-- /#name-and-slogan -->
        


                    </div>

                </header>
            <!-- End NAL main header -->         
         <!-- Bread crumbs -->
            <div class="breadcrumbs"> <a href="http://www.nal.usda.gov">National Agricultural Library</a> &gt; <a href="http://specialcollections.nal.usda.gov/" title="Special Collections at the National Agricultural Library">Special Collections</a> &gt; Exhibits</div>
            <div>
                <div id="site-title">
                    <?php echo link_to_home_page(theme_logo()); ?>
                </div>
                <div id="search-container">
                    <?php if (get_theme_option('use_advanced_search') === null || get_theme_option('use_advanced_search')): ?>
                    <?php echo search_form(array('show_advanced' => true)); ?>
                    <?php else: ?>
                    <?Php echo search_form(); ?>
                    <?php endif; ?>
                </div>
            </div>


            <?php fire_plugin_hook('public_header', array('view'=>$this)); ?>
        </header></header>

        <nav class="top">
            <?php echo public_nav_main(); ?>
        </nav>

        <div id="content">
            <?php
                if(! is_current_url(WEB_ROOT)) {
                  fire_plugin_hook('public_content_top', array('view'=>$this));
                }
            ?>
