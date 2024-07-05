<?php

namespace Block_Styles;

class Theme_JSON {

	/**
	 * The global styles post ID.
	 * 
	 * @var int
	 */
	private $global_styles_post_id;

	/**
	 * The global styles JSON data.
	 * 
	 * @var array
	 */
	private $global_styles_data;

	/**
	 * The JSON resolver class name.
	 * 
	 * @var string
	 */
	private $json_resolver_class_name = 'WP_Theme_JSON_Resolver';

	/**
	 * The value to update.
	 * 
	 * @var array
	 */
	private $value;

	/**
	 * Constructor.
	 * 
	 * @param array $value The value to update.
	 */
	public function __construct( $value ) {
		$this->value = $value;

		// Change the global styles resolver class name if the Gutenberg plugin is active.
		if ( \class_exists( 'WP_Theme_JSON_Resolver_Gutenberg' ) ) {
			$this->json_resolver_class_name = 'WP_Theme_JSON_Resolver_Gutenberg';
		}
	}

	/**
	 * Update the global styles post content.
	 * 
	 * @return void
	 */
	private function run() {
		// Get the global styles post ID and data.
		$this->global_styles_post_id = $this->get_global_styles_post_id();
		$this->global_styles_data    = $this->get_user_global_styles();
	}

	/**
	 * Get the global styles post ID property.
	 * 
	 * @return int The global styles post ID.
	 */
	public function get_global_styles_post_id() {
		// Get the global styles post ID from the JSON resolver.
		$data = ( new $this->json_resolver_class_name )->get_user_data_from_wp_global_styles( \wp_get_theme(), true );

		// Return the global styles post ID.
		return $data['ID'];
	}

	/**
	 * Get the global styles JSON data.
	 * 
	 * @return array The global styles JSON data.
	 */
	private function get_user_global_styles() {
		// Get the global styles post.
		$post = \get_post( $this->global_styles_post_id );

		// Return an empty array if the post is not found.
		if ( ! $post ) {
			return [];
		}

		// Decode the post content.
		$data = \json_decode( $post->post_content, true );

		// Return an empty array if the data is not an array.
		if ( ! \is_array( $data ) ) {
			return [];
		}

		// Return the global styles data.
		return $data;
	}

	/**
	 * Filter the post content.
	 * 
	 * @param string $content The post content.
	 * 
	 * @return string The post content.
	 */
	public function update_global_styles_post_content() {
		// Update the post data.
		\wp_update_post( [
			'ID'           => $this->global_styles_post_id,
			'post_content' => \json_encode( $this->get_combined_styles() ),
		] );
	}

	/**
	 * Get the combined styles.
	 * 
	 * @return string The combined styles, in JSON format.
	 */
	public function get_combined_styles() {
		$data = $this->global_styles_data;
		$data = $this->merge_arrays( $data, $this->get_custom_styles() );

		return $data;
	}

	/**
	 * Get the custom styles.
	 * 
	 * @return array The custom styles.
	 */
	public function get_custom_styles() {
		$custom_blocks_styles = [];
		$styles               = Styles_Collection::get_instance()->get_styles_array();
		foreach ( $this->value as $block => $id ) {
			if ( isset( $styles[ $block ][ $id ] ) ) {
				$custom_blocks_styles[ $block ] = $styles[ $block ][ $id ];
			}
		}

		return [
			'styles' => [
				'blocks' => $custom_blocks_styles
			],
		];
	}

	/**
	 * Merge arrays recursively.
	 * 
	 * @param array $array1 The first array.
	 * @param array $array2 The second array.
	 * 
	 * @return array The merged array.
	 */
	private function merge_arrays( $array1, $array2 ) {
		$merged = $array1;
		foreach ( $array2 as $key => $value ) {
			if ( is_array( $value ) && isset( $merged[ $key ] ) && is_array( $merged[ $key ] ) ) {
				$merged[ $key ] = $this->merge_arrays( $merged[ $key ], $value );
				continue;
			}
			$merged[ $key ] = $value;
		}
		return $merged;
	}
}
