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
* On enregistre un nouvel onglet ...
*************************************************************************************************/
function msk_add_love_product_tab($tabs) {
	
	$tabs['love_tab'] = array(
		'title' 	=> __('Popularity', 'msk'),
		'priority' 	=> 15,
		'callback' 	=> 'msk_add_love_product_tab_content'
	);

	return $tabs;

}
add_filter('woocommerce_product_tabs', 'msk_add_love_product_tab');


/*************************************************************************************************
* ... puis on définit le contenu de ce nouvel onglet
*************************************************************************************************/
function msk_add_love_product_tab_content() {
	wc_get_template('single-product/tabs/love-product.php');
}


/*************************************************************************************************
* On ajoute 2 champs (post meta ou custom field) aux produits WC dans l'onglet "Avancé"
*************************************************************************************************/
function msk_add_loves_hates_fields_to_product() {
	woocommerce_wp_text_input(
		array(
			'id' => 'loves', 
			'data_type' => 'decimal', 
			'label' => __('Loves', 'msk'),
			'placeholder' => __('Amount of love', 'msk'),
			'description' => __('Love this product has received.', 'msk'),
		)
	);

	woocommerce_wp_text_input(
		array(
			'id' => 'hates', 
			'data_type' => 'decimal', 
			'label' => __('Hates', 'msk'),
			'placeholder' => __('Amount of hate', 'msk'),
			'description' => __('Hatred this product has received.', 'msk'),
		)
	);
}
add_action('woocommerce_product_options_advanced', 'msk_add_loves_hates_fields_to_product');


/*************************************************************************************************
* On enregistre les valeurs de LOVES & HATES lorsqu'on enregistre un post
*************************************************************************************************/
function msk_save_loves_hates_product_fields($product_id) {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

	if (isset($_POST['loves'])) {
		$loves = (int)$_POST['loves'];
		update_post_meta($product_id, 'loves', $loves);
	}

	if (isset($_POST['hates'])) {
		$hates = (int)$_POST['hates'];
		update_post_meta($product_id, 'hates', $hates);
	}
}
add_action('save_post', 'msk_save_loves_hates_product_fields');


/*************************************************************************************************
* On traite les actions Ajax LOVE et HATE lors du clic sur les boutons sur la page produit
*************************************************************************************************/
function msk_ajax_love_or_hate_product() {
	if (isset($_POST['product_id']) && get_post_type($_POST['product_id']) == 'product' && check_ajax_referer('love_product', 'nonce', false)) {
		$action = $_POST['action'];
		$product_id = $_POST['product_id'];
		
		$loves = get_post_meta($product_id, 'loves', true);
		$hates = get_post_meta($product_id, 'hates', true);

		switch ($action) {
			case 'love_product' :
				update_post_meta($product_id, 'loves', ++$loves);
				break;

			case 'hate_product' :
			update_post_meta($product_id, 'hates', ++$hates);
				break;
		}
		

		$response = array(
			'what' 			=> 'product',
			'action' 		=> 'love',
			'id' 			=> $product_id, 
			'supplemental' 	=> array(
				'loves'		=> $loves,
				'hates'		=> $hates,
			)
		);

		$data = array(
			'product_id' => $product_id,
			'loves' 	 => $loves,
			'hates' 	 => $hates
		);

	} else {

		$response = array(
			'what' 			=> 'product',
			'action' 		=> 'love',
			'id' 			=> 0
		);

	}

	$xmlResponse = new WP_Ajax_Response($response);
	$xmlResponse->send();
}
add_action('wp_ajax_love_product', 'msk_ajax_love_or_hate_product');
add_action('wp_ajax_nopriv_love_product', 'msk_ajax_love_or_hate_product');
add_action('wp_ajax_hate_product', 'msk_ajax_love_or_hate_product');
add_action('wp_ajax_nopriv_hate_product', 'msk_ajax_love_or_hate_product');