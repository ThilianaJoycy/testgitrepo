<?php

/*************************************************************************************************
*
*
* 	Tutoriel Contact Form 7 sur l'enregistrement des messages dans un type de contenu WordPress
*	Plus d'infos sur http://mosaika.fr/recuperer-donnees-plugin-contact-form-7
*
*
*************************************************************************************************/

/*************************************************************************************************
* On créée notre type de contenu qui stockera nos messages ('form_msg')
*************************************************************************************************/
function msk_create_message_post_type() {
	register_post_type( 'form_msg',
		array(
			'labels' => array(
				'name' => __( 'Messages', 'msk'),
				'singular_name' => __( 'Message', 'msk'),
				'add_new' => __( 'Add New', 'msk' ),
				'add_new_item' => __( 'Add new message', 'msk'),
				'edit' => __( 'Edit', 'msk' ),
			),
			'menu_position' => 86,
			'show_ui' => true,
			'show_in_menu' => true,
			'public' => false,
			'supports' => array( 
				'title', 
				'editor',
			),
		)
	);
}
add_action('init', 'msk_create_message_post_type');



/*************************************************************************************************
* On intercepte les soumissions de formulaire WPCF7 et on les traite pour créer un post de type "form_msg"
*************************************************************************************************/
function msk_create_message_after_wpcf7_submission() {
	$submission = WPCF7_Submission::get_instance();

	if ($submission) {

		// On récupère les données du formulaire
		$posted_data = $submission->get_posted_data();

		// On stocke ce qu'on veut dans des variables, tout en assanissant les données
		$name = sanitize_text_field($posted_data['your-name']);
		$email = sanitize_email($posted_data['your-email']);
		$subject = sanitize_text_field($posted_data['your-subject']);
		$message = implode("\n", array_map('sanitize_text_field', explode("\n", $posted_data['your-message'])));

		// On rédige le contenu de notre post ...
		$post_body = sprintf(__('<h6>Message from %1$s <%2$s></h6>', 'msk'), $name, $email);
		$post_body .= '<hr>';
		$post_body .= $message;

		// ... puis on insère un post de type 'form_msg' dans WordPress
		wp_insert_post(
		   array(
				'post_type' => 'form_msg',
				'post_title' => $subject,
				'post_content' => $post_body
			)	
		);

	}
}
add_action('wpcf7_mail_sent', 'msk_create_message_after_wpcf7_submission');



/*************************************************************************************************
* Bonus : on affiche une bubble avec le nombre de messages non-lus (brouillons) dans le menu d'admin
*************************************************************************************************/
function msk_add_menu_msgform_bubble($menu) {
    $pending_count = wp_count_posts('form_msg')->draft;

    foreach($menu as $menu_key => $menu_data) {
        if ('edit.php?post_type=form_msg' != $menu_data[2])
    		continue;

        $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
    }

    return $menu;
}
add_filter( 'add_menu_classes', 'msk_add_menu_msgform_bubble');