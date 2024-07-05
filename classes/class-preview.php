<?php

namespace Block_Styles;

class Preview {

	/**
	 * The block name.
	 * 
	 * @var string
	 */
	public $block_name;

	/**
	 * The block style ID.
	 * 
	 * @var array
	 */
	public $style_id;

	/**
	 * An instance of the Block_Styles\Theme_JSON class.
	 * 
	 * @var \Block_Styles\Theme_JSON
	 */
	private $theme_json;
	
	/**
	 * Constructor.
	 * 
	 * @param string $block_name The block name.
	 * @param string $style_id   The block style ID.
	 */
	public function __construct( $block_name, $style_id ) {
		$this->block_name = $block_name;
		$this->style_id   = $style_id;

		$this->theme_json = new Theme_JSON( [ $block_name => $style_id ] );
	}

	/**
	 * Render the block preview.
	 * 
	 * @return void
	 */
	public function render() {
		// Print the iframe.
		echo '<iframe src="' . \get_site_url( null, "/?block-style-preview=yes&block={$this->block_name}&style={$this->style_id}" ) . '"></iframe>';
	}

	/**
	 * Filter the global styles.
	 * 
	 * Hooked in the `wp_theme_json_data_user` filter.
	 * 
	 * @param \WP_Theme_JSON_Data $theme_json Class to access and update the underlying data.
	 * 
	 * @return \WP_Theme_JSON_Data
	 */
	public function filter_global_styles( $theme_json ) {
		return new \WP_Theme_JSON_Data( $this->theme_json->get_custom_styles(), 'custom' );
	}

	/**
	 * Get the block preview HTML.
	 * 
	 * @return string
	 */
	public function get_html() {
		$file = BLOCK_STYLES_PLUGIN_DIR . '/block-templates/' . $this->block_name . '.html';
		if ( ! file_exists( $file ) ) {
			return '';
		}
		return \do_blocks( \do_shortcode( file_get_contents( $file ) ) );
	}
}

// if ( isset( $_GET['block-style-preview'] ) && 'yes' === $_GET['block-style-preview'] ) {
// 	$block = $_GET['block'];
// 	$style = $_GET['style'];

// 	$preview = new Preview( $block, $style );
// 	// Disable the adminbar.
// 	add_filter( 'show_admin_bar', '__return_false' );

// 	// Filter the global styles.
// 	add_filter( 'wp_theme_json_data_user', [ $preview, 'filter_global_styles' ], 999 );

// 	add_filter( 'frontpage_template', function() {
// 		return BLOCK_STYLES_PLUGIN_DIR . '/preview-canvas.php';
// 	} );
// }
