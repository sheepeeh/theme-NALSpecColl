</div><!-- end content -->

<footer>

        <div id="custom-footer-text">
            <?php if ( $footerText = get_theme_option('Footer Text') ): ?>
            <p><?php echo $footerText; ?></p>
            <?php endif; ?>
            <?php if ((get_theme_option('Display Footer Copyright') == 1) && $copyright = option('copyright')): ?>
                <p><?php echo $copyright; ?></p>
            <?php endif; ?>
        </div>
    <!-- Begin NAL Footer -->


        <div id="footer-panels-wrapper">
            <div id="footer-links" class="navigation">
                        <ul>
                            <li><a href="http://www.nal.usda.gov" target="_blank">NAL Home</a></li>
                            <li><a href="http://www.usda.gov" target="_blank">USDA.gov</a></li>
                            <li><a href="http://www.ars.usda.gov" target="_blank">Agricultural Research Service</a></li>
                            <li><a href="http://www.nal.usda.gov/web-policies-and-important-links" target-"_blank">Policies &amp; Links</a></li>
                            <li><a href="http://www.usda.gov/wps/portal/usda/usdahome?navid=PLAIN_WRITING" target="_blank">Plain Language</a></li>
                            <li><a href="http://www.ars.usda.gov/Services/docs.htm?docid=1398" target="_blank">FOIA</a></li>
                            <li><a href="http://www.usda.gov/wps/portal/usda/usdahome?navtype=FT&amp;navid=ACCESSIBILITY_STATEM" target="_blank">Accessibility Statement</a> </li>
                        </ul>
                        <br>
                        <ul>
                            <li><a href="http://www.ars.usda.gov/Main/docs.htm?docid=8040" target="_blank">Information Quality</a></li>
                            <li><a href="http://www.ars.usda.gov/disclaim.html#Privacy" target="_blank">Privacy Policy</a></li>
                            <li><a href="http://www.usda.gov/wps/portal/usda/usdahome?navtype=FT&amp;navid=NON_DISCRIMINATION" target="_blank">Non-Discrimination Statement</a></li>
                            <li><a class="ext" href="http://www.usa.gov" target="_blank">USA.gov</a></li>
                            <li><a class="ext" href="http://www.whitehouse.gov" target="_blank">White House</a></li>
                        </ul>
            </div>
        </div>

        <footer id="footer" class="region region-footer">
            <div id="footer-address">National Agricultural Library &nbsp;&nbsp;&nbsp; 10301 Baltimore Avenue &nbsp;&nbsp;&nbsp; Beltsville, MD 20705 &nbsp;&nbsp;&nbsp; 301-504-5755</div>
        </div>
        </footer>
        
    <!-- End NAL Footer -->
        

    <?php fire_plugin_hook('public_footer', array('view' => $this)); ?>

</footer>

</div><!--end wrap-->

<script type="text/javascript">
jQuery(document).ready(function () {
    Seasons.showAdvancedForm();
    Seasons.mobileSelectNav();
});
</script>

</body>

</html>
