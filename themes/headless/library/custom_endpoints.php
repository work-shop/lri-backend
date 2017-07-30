<?php


add_action( 'rest_api_init', function () {
	register_rest_route( 'custom', '/forms', array(
		'methods'   =>  'GET',
		'callback'  =>  'get_form',
		) );
});

/**
 * Handles a request of the form: `/wp-json/custom/form-scripts?form={{ int }}`,
 * where int is given as the id of the form in question.
 */
add_action( 'rest_api_init', function () {
	register_rest_route( 'custom', '/form-scripts', array(
		'methods'   =>  'GET',
		'callback'  =>  'get_form_scripts',
		) );
});


function get_form( $request ) {

	$form_id = $_GET['form'];

	$form = gravity_form($form_id, false, false, false, '', true, 1, false);

	if ( empty( $form ) ) {
		return null;
	}

	return new WP_REST_Response( $form, 200 );

}

/**
 * Enqueues all scripts needed for WordPress, including the passed form id,
 * Which is assumed to be an ajax enabled form.
 */
function get_form_scripts( $request ) {

    $form_id = $_GET['form'];

    if ( !empty( $form_id ) ) {
        gravity_form_enqueue_scripts( $form_id, true );
    }


    ob_start();

    wp_head();

    $scripts = ob_get_clean();

    return new WP_REST_Response( $scripts, 200 );

}

?>
