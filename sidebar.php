<?php

/**
 * Register Hello Gutenbert Meta Box
 */
function hello_gutenberg_add_meta_box() {
	add_meta_box( 
		'hello_gutenberg_meta_box', 
		__( 'Hello Gutenberg Meta Box', 'hello-gutenberg' ), 
		'hello_gutenberg_metabox_callback',
		null,
		'side',
		'low',
		'post',
		array(
			'__back_compat_meta_box' => false,
		)
	);
}
add_action( 'add_meta_boxes', 'hello_gutenberg_add_meta_box' );

/**
 * Hello Gutenberg Metabox Callback
 */
function hello_gutenberg_metabox_callback( $post ) {
	$value = get_post_meta( $post->ID, '_hello_gutenberg_field', true );
	?>
	<label for="hello_gutenberg_field"><?php _e( 'What\'s your name?', 'hello-gutenberg' ) ?></label>
	<input type="text" name="hello_gutenberg_field" id="hello_gutenberg_field" value="<?php echo $value ?>" />
	<?php
}

/**
 * Save Hello Gutenberg Metabox
 */
function hello_gutenberg_save_postdata( $post_id ) {
	if ( array_key_exists( 'hello_gutenberg_field', $_POST ) ) {
		update_post_meta( $post_id, '_hello_gutenberg_field', $_POST['hello_gutenberg_field'] );
	}
}
add_action( 'save_post', 'hello_gutenberg_save_postdata' );

/**
 * Register Hello Gutenberg Meta Field to Rest API
 */
function hello_gutenberg_register_meta() {
	register_meta(
		'post', '_hello_gutenberg_field', array(
			'type'			=> 'string',
			'single'		=> true,
			'show_in_rest'	=> true,
		)
	);
}
add_action( 'init', 'hello_gutenberg_register_meta' );

/**
 * Register Hello Gutenberg Metabox to Rest API
 */
function hello_gutenberg_api_posts_meta_field() {
	register_rest_route(
		'hello-gutenberg/v1', '/update-meta', array(
			'methods'  => 'POST',
			'callback' => 'hello_gutenberg_update_callback',
			'args'     => array(
				'id' => array(
					'sanitize_callback' => 'absint',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'hello_gutenberg_api_posts_meta_field' );

/**
 * Hello Gutenberg REST API Callback for Gutenberg
 */
function hello_gutenberg_update_callback( $data ) {
	return update_post_meta( $data['id'], $data['key'], $data['value'] );
}