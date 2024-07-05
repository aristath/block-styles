<?php

namespace Block_Styles;

class Base {

	/**
	 * The API styles.
	 * 
	 * @var array
	 */
	protected $api_styles;

	/**
	 * Block names.
	 * 
	 * @var array
	 */
	protected $block_names;

	/**
	 * An instance of this class.
	 * 
	 * @var Base
	 */
	private static $instance;

	/**
	 * Get an instance of this class.
	 * 
	 * @return Base An instance of this class.
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new Base();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}

	/**
	 * Initialize the plugin.
	 * 
	 * @return void
	 */
	public function init() {
		$this->register_block_styles();
		new Admin_Page();
	}

	/**
	 * Register block styles in the collection class.
	 * 
	 * @return void
	 */
	public function register_block_styles() {
		$styles = $this->get_api_styles();

		foreach ( $styles as $block => $block_styles ) {
			$this->block_names[ $block ] = $block_styles['label'];
			foreach ( $block_styles['styles'] as $block_style_id => $block_style ) {
				Styles_Collection::get_instance()->add( new Style(
					$block_style_id,
					$block,
					$block_style['style'],
					$block_style['label']
				) );
			}
		}
	}

	/**
	 * Get the API styles.
	 *
	 * @return array The API styles.
	 */
	private function get_api_styles() {
		static $api_styles;
		if ( ! $api_styles ) {
			$api_styles = json_decode( \file_get_contents( BLOCK_STYLES_PLUGIN_DIR . '/styles.json' ), true );
		}
		return $api_styles;
	}

	/**
	 * Get the block names.
	 * 
	 * @return array The block names.
	 */
	public function get_block_names() {
		return $this->block_names;
	}
}
