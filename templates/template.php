<?php
/**
 * Template Name: Custom Template
 */

get_header(); ?>

<div class="wrapper">	

	<main class="custom-template" role="main">
	
		<section class="section-1"></section>
	
		<?php 
		
		/** 
		 * Using include + locate_template
		 * Allows variables to be passed to partial
		 *	
		 */	
		include(locate_template('templates/partials/partial.php')); 
		
		?>

	</main>

</div>

<?php get_footer(); ?>