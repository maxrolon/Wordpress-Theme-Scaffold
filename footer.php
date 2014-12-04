        
        <footer>
        	<span></span>
        </footer>
        
        <?php 
            /*
             * Write all JS files that belong before the closing body tag.
             * Add more by configuring them in config/<environment>.config.php
             */
            wp_footer(); 
        ?>
        
        <script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', '<?php echo get_option('analytics_code')?>', '<?php echo substr( SITE_URL, strpos( SITE_URL, ':', 0) + 3  ); ?>');
			ga('send', 'pageview');
		</script>
        
    </body>
</html>