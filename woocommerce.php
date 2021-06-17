<?php
/**
 * The template for WooCommerce
 *
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<article class="hentry">
				<div class="entry-content"><?php woocommerce_content(); ?></div>
			</article>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
