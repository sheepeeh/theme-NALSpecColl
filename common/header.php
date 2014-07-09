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
    queue_css_file('normalize');
    queue_css_file('style', 'screen');
    queue_css_file('print', 'print');
    queue_css_file('nal_header');
    echo head_css();
    ?>

    <!-- JavaScripts -->
    <?php queue_js_file('vendor/modernizr'); ?>
    <?php queue_js_file('vendor/selectivizr'); ?>
    <?php queue_js_file('jquery-extra-selectors'); ?>
    <?php queue_js_file('vendor/respond'); ?>
    <?php queue_js_file('globals'); ?>
    <?php echo head_js(); ?>
</head>
<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
    <div id="wrap">
        <header>
            <div id="nal-logos">
                <div id="header-logo">
                    <a id="logo" class="header__logo" target="_blank" title="United States Department of Agriculture" href="http://www.nal.usda.gov/">
                    <img class="header__logo-image" alt="United States Department of Agriculture" src="http://www.nal.usda.gov/sites/all/themes/nal/images/usdalogocolor.png"></img>
                    </a>
                </div>
                <div class="header__name-and-slogan" id="name-and-slogan">
                    <div>
                        <h1 class="header__site-name" id="site-name">
                        <a href="http://www.nal.usda.gov/" title="National Agricultural Library" class="header__site-link" rel="home" target="_blank">
                            <span>National Agricultural Library</span>
                        </a>
                        </h1>
                    </div>

                    <div>
                        <h2 class="header__site-slogan" id="site-slogan">
                        <a href="http://www.nal.usda.gov/" title="United States Department of Agriculture" target="_blank">
                            <span>United States Department of Agriculture</span>
                        </a>
                        </h2>
                    </div>
                </div>
            </div>

            <div id="site-title">
                <?php echo link_to_home_page(theme_logo()); ?>
            </div>
            <div id="search-container">
                <?php echo search_form(array('show_advanced' => true)); ?>
            </div>
             <!-- Bread crumbs -->
<div class="breadcrumbs"> <a href="http://nal.usda.gov">National Agricultural Library</a> &gt; <a href="http://specialcollections.nal.usda.gov/" title="Special Collections at the National Agricultural Library">Special Collections</a> &gt; Exhibits</div>
            <?php fire_plugin_hook('public_header', array('view'=>$this)); ?>
        </header>

        <nav class="top">
            <?php echo public_nav_main(); ?>
        </nav>

        <div id="content">
