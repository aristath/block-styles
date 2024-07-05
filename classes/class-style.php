<?php

namespace Block_Styles;

class Style {

	/**
	 * The block-style ID.
	 * 
	 * @var string
	 */
	public $id;

	/**
	 * The block name.
	 * 
	 * @var string
	 */
	public $block_name;

	/**
	 * The style name.
	 * 
	 * @var string
	 */
	public $style_name;

	/**
	 * The block styles.
	 * 
	 * @var array
	 */
	public $styles;

	/**
	 * Constructor.
	 * 
	 * @param string $block_name The block name.
	 * @param array  $styles     The block styles.
	 * @param string $style_name The style name.
	 */
	public function __construct( $id, $block_name, $styles, $style_name = '' ) {
		$this->id         = $id;
		$this->block_name = $block_name;
		$this->styles     = $styles;
		$this->style_name = $style_name;
	}
}