<?php
/**
 * Admin page.
 * 
 * @package blockStyles
 */

namespace Block_Styles;

/**
 * Admin page.
 */
class Admin_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'page_init' ] );
	}

	/**
	 * Add the admin menu.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		\add_menu_page(
			\__( 'Block Styles', 'my-theme' ),
			\__( 'Block Styles', 'my-theme' ),
			'manage_options',
			'block-styles',
			[ $this, 'render_admin_page' ],
			'dashicons-admin-appearance',
			30
		);
	}

	/**
	 * Render the admin page.
	 *
	 * @return void
	 */
	public function render_admin_page() {
		?>
		</div>
			<h1><?php \esc_html_e( 'Block Styles', 'block-styles' ); ?></h1>
			<form method="post" action="options.php">
			<?php
				\settings_fields( 'block_styles' );
				\do_settings_sections( 'block-styles' );
				\submit_button();
			?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function page_init() {
		\add_settings_section(
			'block_styles_setting_section',
			'My Custom Settings',
			'__return_false',
			'block-styles'
		);

		\register_setting(
			'block_styles',
			'block_styles_presets',
			[
				'type'              => 'array',
				'description'       => 'presets setting description',
				'sanitize_callback' => function( $value ) {
					foreach ( $value as $key => $val ) {
						$value[ $key ] = \sanitize_key( $val );
					}

					// Update the global styles post content.
					$theme_json = new Theme_JSON( $value );
					$theme_json->run();
					$theme_json->update_global_styles_post_content();

					return $value;
				}
			]
		);

		add_settings_field(
			'block_styles_presets',
			\esc_html__( 'Block styles presets', 'block-styles' ),
			[ $this, 'print_inputs' ],
			'block-styles',
			'block_styles_setting_section'
		);
	}

	/**
	 * Print the inputs.
	 * 
	 * @return void 
	 */
	function print_inputs() {
		$value = \get_option( 'block_styles_presets', [] );
		$block_names = Base::get_instance()->get_block_names();
		foreach ( Styles_Collection::get_instance()->get_styles() as $block_name => $block_styles ) {
			$active = $value[ $block_name ];
			echo '<p>' . \esc_html( $block_names[ $block_name ] ) . '</p>';
			foreach ( $block_styles as $block_style ) {
				echo '<label>';
					$input_attrs = [
						'type'  => 'radio',
						'name'  => 'block_styles_presets[' . $block_name . ']',
						'value' => $block_style->id,
					];
					if ( $active === $block_style->id ) {
						$input_attrs['checked'] = '';
					}
					echo '<input ';
					foreach ( $input_attrs as $key => $val ) {
						echo ( $val !== '' )
							? \sanitize_key( $key ) . '="' . \esc_attr( $val ) . '"'
							: " $key";
					}

					echo ' />';
					echo \esc_html( $block_style->style_name );

					// $preview = new Preview( $block_name, $block_style->id );
					// $preview->render();
				echo '</label>';
				echo '<br>';
			}
		}
	}
}