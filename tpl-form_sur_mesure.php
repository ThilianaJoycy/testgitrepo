<?php
/*
Template Name: Formulaire sur-mesure
*/

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>

				<div class="entry-content">
					<h4>
						La cagnotte s'élève à <strong><?php echo get_option('valeur_cagnotte', 0); ?></strong> €.
					</h4>


					<!-- 

					 ####   ####  #####  ######    #####  ###### #####   ####   ####  
					#    # #    # #    # #         #    # #      #    # #      #    # 
					#      #    # #    # #####     #    # #####  #    #  ####  #    # 
					#      #    # #    # #         #####  #      #####       # #    # 
					#    # #    # #    # #         #      #      #   #  #    # #    # 
					 ####   ####  #####  ######    #      ###### #    #  ####   ####  

					 -->

					 <?php if (isset($_GET['erreur'])) 
					 {

					 	echo '<div class="alert">';

				 		switch ($_GET['erreur'])
				 		{
				 			case 'radin' :
				 				echo 'Vous ne pouvez pas faire un don inférieur à 0 €.';
				 				break;

			 				case 'trop' :
			 					echo 'Nous n\'acceptons pas les dons supérieurs à 10000 €.';
			 					break;

 							default :
 								echo 'Une erreur est survenue.';

				 		}


					 	echo '</div>';

					 } ?>


					 <form action="#" method="POST" class="comment-form">
					 	<?php wp_nonce_field('faire-don', 'cagnotte-verif'); ?>

						<p>
							<label for="don">Valeur du don</label>
					 		<input id="don" type="number" name="don" value="5" />
					 	</p>

					 	<p>
					 		<input id="submit" type="submit" name="cagnote-don-envoi" id="submit" class="submit" value="Envoyer" />
					 	</p>

					 </form>

				</div>

			</article>

			<?php if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; ?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
