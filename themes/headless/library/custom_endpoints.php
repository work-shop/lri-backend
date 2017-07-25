<?php


add_action( 'rest_api_init', function () {
	register_rest_route( 'custom', '/forms', array(
		'methods'   =>  'GET',
		'callback'  =>  'get_form',
		) );
});

function get_form( $request ) {
	
	//$form_id = $_GET['form'];
	$form_id = '1';

	$form = gravity_form($form_id, false, false, false, '', true, 1);

	if ( empty( $form ) ) {
		return null;
	}

	return new WP_REST_Response( $form, 200 );

}


?>