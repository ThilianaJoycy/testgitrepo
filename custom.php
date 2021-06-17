<?php

function msk_print() {
	$numargs = func_num_args();
	$arg_list = func_get_args();
	
    for ($i = 0; $i < $numargs; $i++) {
		echo "<pre style='background:#b9e0f5;padding:1em;margin:1em 0;border-radius:4px;overflow-x:scroll;'>";
		print_r($arg_list[$i]);
		echo "</pre>";
	}
}

function msk_p() {
	msk_print(func_get_args());
}

/*************************************************************************************************
* 	Tutoriel de création d'un formulaire personnalisé par Mosaika 
	Plus d'infos sur http://mosaika.fr/creer-formulaire-wordpress-sur-mesure
*************************************************************************************************/
function traitement_formulaire_don_cagnotte() {

	/*************************************************************************************************
	* On vérifie si le formulaire a été envoyé et si le NONCE est défini
	*************************************************************************************************/
	if (isset($_POST['cagnote-don-envoi']) && isset($_POST['cagnotte-verif'])) 
	{


		/*************************************************************************************************
		* On vérifie la validité du NONCE
		*************************************************************************************************/
		if (wp_verify_nonce($_POST['cagnotte-verif'], 'faire-don')) 
		{


			/*************************************************************************************************
			* On fait ce que l'on a à faire !
			*************************************************************************************************/

			$don = intval($_POST['don']);

			if ($don < 0)
			{
				
				$url = add_query_arg('erreur', 'radin', wp_get_referer());

				wp_safe_redirect($url);
				exit();

			} 

			else if ($don > 10000)
			{

				$url = add_query_arg('erreur', 'trop', wp_get_referer());

				wp_safe_redirect($url);
				exit();

			}

			else 
			{

				$cagnotte_actuelle = intval(get_option('valeur_cagnotte', 0));
				$nouvelle_cagnotte = $cagnotte_actuelle + $don;

				update_option('valeur_cagnotte', $nouvelle_cagnotte);

			}

		}

	}
}
add_action('template_redirect', 'traitement_formulaire_don_cagnotte');







/*
 * Parrainage : process formulaire enregistrement nouvel utilisateur/parrain
**/
function msk_test_feedbacks_gf_submission($entry, $form) {
	//msk_p($entry);

	//msk_p(GFFormsModel::get_leads(2));

	msk_p(GFFormsModel::get_form_meta(2));


}
add_action('gform_after_submission', 'msk_test_feedbacks_gf_submission');