<?php

namespace Block_Styles;

class Styles_Collection {

	/**
	 * An instance of this collection.
	 * 
	 * @var Styles_Collection
	 */
	private static $instance;
	
	/**
	 * The styles.
	 * 
	 * @var array
	 */
	private $styles = [];

	/**
	 * Get an instance of this collection.
	 * 
	 * @return Styles_Collection An instance of this collection.
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new Styles_Collection();
		}
		return self::$instance;
	}

	/**
	 * Add a style.
	 * 
	 * @param Style $style The style.
	 * 
	 * @return void
	 */
	public function add( Style $style ) {
		$this->styles[ $style->block_name ]               = $this->styles[ $style->block_name ] ?? [];
		$this->styles[ $style->block_name ][ $style->id ] = $style;
	}

	/**
	 * Get the styles.
	 * 
	 * @return array The styles.
	 */
	public function get_styles() {
		return $this->styles;
	}

	/**
	 * Get the styles as an array.
	 * 
	 * @return array The styles as an array.
	 */
	public function get_styles_array() {
		foreach ( $this->styles as $block_name => $block_styles ) {
			$styles[ $block_name ] = [];
			foreach ( $block_styles as $style_id => $block_style ) {
				$styles[ $block_name ][ $style_id ] = $block_style->styles;
			}
		}
		return $styles;
	}
}
