<?php
/**
 * Umbra Theme Customizer
 *
 * @package Umbra
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function umbra_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$color_label = __( 'Auto color posts', 'umbra' );
	$color_descrip = __( 'Use the featured image to create the page&rsquo;s color scheme.', 'umbra' );
	$color_warning = sprintf(
		'<p style="font-style:normal;font-weight:bold;"><span style="color:#ffba00;" class="dashicons dashicons-info"></span> %s</p>',
		sprintf( _x( 'Activate %s to enable.', 'Placeholder is a link to Jetpack.me', 'umbra' ), '<a href="http://jetpack.me" target="_blank">Jetpack</a>' )
	);

	if ( ! class_exists( 'Jetpack' ) || ! current_theme_supports( 'tonesque' ) ) {

		$wp_customize->add_setting( 'umbra_use_tonesque', array(
			'default' => false,
			'sanitize_callback' => 'umbra_sanitize_bool',
		) );

		$wp_customize->add_control( new Umbra_Customize_Control( $wp_customize, 'umbra_use_tonesque', array(
			'label'       => $color_label,
			'section'     => 'colors',
			'type'        => 'checkbox',
			'description' => $color_descrip . $color_warning,
			'input_attrs' => array(
				'disabled' => 'disabled',
			),
		) ) );

	} else {

		$wp_customize->add_setting( 'umbra_use_tonesque', array(
			'default'           => true,
			'sanitize_callback' => 'umbra_sanitize_bool',
		) );

		$wp_customize->add_control( 'umbra_use_tonesque', array(
			'label'       => $color_label,
			'section'     => 'colors',
			'type'        => 'checkbox',
			'description' => $color_descrip,
		) );

		$wp_customize->add_setting( 'umbra_base_color', array(
			'default' => '424046',
			'sanitize_callback' => 'sanitize_hex_color_no_hash',
			'sanitize_js_callback' => 'maybe_hash_hex_color',
			'transport' => 'postMessage',
		) );

		$wp_customize->add_control(new Umbra_Customize_Color_Control( $wp_customize, 'umbra_base_color', array(
			'label'   => __( 'Default Colors', 'umbra' ),
			'section' => 'colors',
		) ) );

	}
}
add_action( 'customize_register', 'umbra_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function umbra_customize_preview_js() {
	wp_enqueue_script( 'umbra_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview', 'underscore' ), '20130508', true );
	wp_localize_script( 'umbra_customizer', 'umbra', array( 'url' => home_url( '/umbra-css/' ) ) );
}
add_action( 'customize_preview_init', 'umbra_customize_preview_js' );

/**
 * Sanitization function to return a boolean value
 * @param  mixed  $maybebool  Value that should be either true or false.
 * @return bool  A boolean.
 */
function umbra_sanitize_bool( $maybebool ){
	if ( $maybebool == true ){
		return true;
	}
	return false;
}

if ( class_exists( 'WP_Customize_Color_Control' ) ) {
	/**
	 * Create the Umbra_Customize_Color_Control based on WP_Customize_Color_Control.
	 *
	 * The new control inherits all methods of WP_Customize_Color_Control, the only
	 * change is in adding the data-palettes attribute, so we can set our own palettes.
	 */
	class Umbra_Customize_Color_Control extends WP_Customize_Color_Control {
		public function render_content() {
			ob_start();
			parent::render_content();
			$output = ob_get_clean();
			$output = str_replace( '"', "'", $output );

			/**
			 * Filter the default palettes passed to the color picker.
			 *
			 * @param array  $palettes  The array of selected hex colors for Iris's palettes.
			 */
			$palettes = apply_filters( 'umbra_default_palettes', array( '#e8eaf0', '#e0bc64', '#98cc7e', '#6bc1ce', '#598bd1', '#332c7c', '#966c8e', '#222') );

			if ( is_array( $palettes ) ) {
				// Create a JSON string for the options
				$palettes = '["' . implode( '","', $palettes ) . '"]';
			} elseif ( 'false' == $palettes || ! $palettes ) {
				// Disable the palettes
				$palettes = 'false';
			} else {
				// Use the default palettes.
				$palettes = 'true';
			}

			echo str_replace( "color-picker-hex'", "color-picker-hex' data-palettes='$palettes'", $output );
		}
	}

	/**
	 * Create our own Customize Control to use input_attrs on more than just text types.
	 * We also need to create input_attrs(), for backwards compat with pre-4.0.
	 */
	class Umbra_Customize_Control extends WP_Customize_Control {
		/**
		 * Render the custom attributes for the control's input element.
		 * Copied from WP_Customize_Control to provide backcompat
		 *
		 * @access public
		 */
		public function input_attrs() {
			foreach ( $this->input_attrs as $attr => $value ) {
				echo $attr . '="' . esc_attr( $value ) . '" ';
			}
		}

		/**
		 * Render the control's content. Copied from WP_Customize_Control
		 *
		 * Allows the content to be overriden without having to rewrite the wrapper in $this->render().
		 *
		 * Supports basic input types `text`, `checkbox`, `textarea`, `radio`, and `select`.
		 * Additional input types such as `email`, `url`, `number`, `hidden` and `date` are supported implicitly.
		 */
		protected function render_content() {
			switch ( $this->type ) {
				case 'checkbox':
					?>
					<label>
						<input type="checkbox" <?php $this->input_attrs(); ?> value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> />
						<?php echo esc_html( $this->label ); ?>
						<?php if ( ! empty( $this->description ) ) : ?>
							<span class="description customize-control-description"><?php echo $this->description; ?></span>
						<?php endif; ?>
					</label>
					<?php
					break;
				case 'radio':
					if ( empty( $this->choices ) )
						return;

					$name = '_customize-radio-' . $this->id;

					if ( ! empty( $this->label ) ) : ?>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php endif;
					if ( ! empty( $this->description ) ) : ?>
						<span class="description customize-control-description"><?php echo $this->description ; ?></span>
					<?php endif;

					foreach ( $this->choices as $value => $label ) :
						?>
						<label>
							<input type="radio" <?php $this->input_attrs(); ?> value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
							<?php echo esc_html( $label ); ?><br/>
						</label>
						<?php
					endforeach;
					break;
				case 'select':
					if ( empty( $this->choices ) )
						return;

					?>
					<label>
						<?php if ( ! empty( $this->label ) ) : ?>
							<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<?php endif;
						if ( ! empty( $this->description ) ) : ?>
							<span class="description customize-control-description"><?php echo $this->description; ?></span>
						<?php endif; ?>

						<select <?php $this->input_attrs(); ?> <?php $this->link(); ?>>
							<?php
							foreach ( $this->choices as $value => $label )
								echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
							?>
						</select>
					</label>
					<?php
					break;
				case 'textarea':
					?>
					<label>
						<?php if ( ! empty( $this->label ) ) : ?>
							<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<?php endif;
						if ( ! empty( $this->description ) ) : ?>
							<span class="description customize-control-description"><?php echo $this->description; ?></span>
						<?php endif; ?>
						<textarea rows="5" <?php $this->input_attrs(); ?> <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
					</label>
					<?php
					break;
				default:
					?>
					<label>
						<?php if ( ! empty( $this->label ) ) : ?>
							<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<?php endif;
						if ( ! empty( $this->description ) ) : ?>
							<span class="description customize-control-description"><?php echo $this->description; ?></span>
						<?php endif; ?>
						<input type="<?php echo esc_attr( $this->type ); ?>" <?php $this->input_attrs(); ?> value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
					</label>
					<?php
					break;
			}
		}
	}
}
