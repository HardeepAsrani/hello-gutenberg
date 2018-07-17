<?php
/**
 * Plugin Name: Hello Gutenberg
 * Plugin URI: https://github.com/HardeepAsrani/hello-gutenberg/
 * Description: Gutenberg examples.
 * Author: Hardeep Asrani
 * Author URI: http://www.hardeepasrani.com
 * Version: 1.0.0
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package hello-gutenberg
 */

//  Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Enqueue front end and editor JavaScript and CSS
 */
function hello_gutenberg_scripts() {
	$blockPath = '/dist/block.js';
	$stylePath = '/dist/block.css';

	// Enqueue the bundled block JS file
	wp_enqueue_script(
		'hello-gutenberg-block-js',
		plugins_url( $blockPath, __FILE__ ),
		[ 'wp-i18n', 'wp-blocks', 'wp-edit-post', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post', 'wp-api' ],
		filemtime( plugin_dir_path(__FILE__) . $blockPath )
	);

	// Enqueue frontend and editor block styles
	wp_enqueue_style(
		'hello-gutenberg-block-css',
		plugins_url( $stylePath, __FILE__ ),
		'',
		filemtime( plugin_dir_path(__FILE__) . $stylePath )
	);

}

// Hook scripts function into block editor hook
add_action( 'enqueue_block_assets', 'hello_gutenberg_scripts' );

/**
 * Shortcode Initializer.
 */
function hello_gutenberg_shortcode_callback( $attr ) {
	extract( $attr );
	if ( isset( $tweet ) ) {
		$output = 
			'<div class="' . ( ! empty( $theme ) ? $theme : 'click-to-tweet' ) . '">
				<div class="ctt-text">' . $tweet . '</div>
				<p><a href="https://twitter.com/intent/tweet?text='. ( ! empty( $tweetsent ) ? $tweetsent : $tweet ) .'" class="ctt-btn" target="_blank">' . $button . '</a></p>
			</div>';
		return $output;
	}
}

add_shortcode( 'clicktotweet', 'hello_gutenberg_shortcode_callback' );

/**
 * Block Initializer.
 */
add_action( 'plugins_loaded', function () {
	if ( function_exists( 'register_block_type' ) ) {
		// Hook server side rendering into render callback
		register_block_type(
			'hello-gutenberg/click-to-tweet', array(
				'render_callback' => 'hello_gutenberg_block_callback',
				'attributes'	  => array(
					'tweet'	 => array(
						'type' => 'string',
					),
					'tweetsent' => array(
						'type' => 'string',
					),
					'button'	=> array(
						'type'	=> 'string',
						'default' => 'Tweet',
					),
					'theme'	 => array(
						'type'	=> 'boolean',
						'default' => false,
					),
				),
			)
		);
	}
} );

/**
 * Block Output.
 */
function hello_gutenberg_block_callback( $attr ) {
	extract( $attr );
	if ( isset( $tweet ) ) {
        $theme = ( $theme === true ? 'click-to-tweet-alt' : 'click-to-tweet' );
		$shortcode_string = '[clicktotweet tweet="%s" tweetsent="%s" button="%s" theme="%s"]';
        return sprintf( $shortcode_string, $tweet, $tweetsent, $button, $theme );
	}
}
