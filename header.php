<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js ie lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js ie lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js ie lt-ie9"><![endif]-->
<!--[if IE 9]><html class="no-js ie ie9"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js"><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<title><?php wp_title( '|', true, 'right' ); echo get_bloginfo( 'name' ); ?></title>
		
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		
		<meta property="og:site_name" content="<?php wp_title( '|', true, 'right' ); echo get_bloginfo( 'name' ); ?>" /> 
		<?php #<meta property="og:image" content="./img" /> ?>
		<meta property="og:description" content="" />
		
		<link rel="shortcut icon" href="<?php echo bloginfo('stylesheet_directory') ?>/favicon.ico" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		
		<?php # Set cookie to allow the server to send adaptive images ?>
		<script>
			var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
			var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0)
			document.cookie='resolution='+Math.max(w,h)+("devicePixelRatio" in window ? ","+devicePixelRatio : ",1")+'; path=/';
		</script>
		
		<?php
			/* 
			 * Write all JS and CSS files that belong in the header. 
			 * Add more by configuring them in config/<environment>.config.php 
			 */
			wp_head(); 
		?>
	</head>
		
	<body <?php body_class(); ?>>
		<!--[if lt IE 7]>
				<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->
		<header class="container"></header>
