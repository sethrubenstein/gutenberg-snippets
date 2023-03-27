<?php

/**
 * Generates CSS styles from theme.json
 *
 * @return string
 */

function generate_css_styles_from_theme_json() {
	if ( !function_exists('wp_get_global_settings') ) {
		return new WP_Error('missing_function', 'wp_get_global_settings() is missing');
	}

	$colors = wp_get_global_settings();
	$colors = $colors['color']['palette']['theme'];

	ob_start();
	foreach( $colors as $color ) {
		$slug = $color['slug'];
		$color = $color['color'];
		// This class name follows the convention of the block's name and the color slug. For example, "color" is used for text color.
		// If you're using withColors and PanelColorSettings to generate your own color controls and attributes, this method is invaluable
		// for generating all the CSS you'll need to support that easily.
		// You can make your class names whatever you'd like, but you should maintain the convention, as it's the easiest way to know what the color is for.
		// For instance, if you're using a color attribute named "gridDividerColor", then your class name should be "has-{gridDividerColor}-grid-divider-color".
		?>
		.wp-block-my-block-name.has-<?php echo $slug; ?>-color .my-target {
			color: <?php echo $color; ?> !important;
            background-color: <?php echo $color; ?> !important;
            border-color: <?php echo $color; ?> !important;
		}
		<?php
	}
	$styles = ob_get_clean();
	return $styles;
}

/**
 * Enqueues the CSS styles generated from theme.json on the site frontend and in the block and site editors (Gutenberg).
 *
 * @return void
 */
function enqueue_custom_fill_styles() {
	$styles = generate_css_styles_from_theme_json();
	if ( is_wp_error($styles) ) {
		return;
	}
	wp_add_inline_style( 'my-block-style-handle', $styles );
}
add_action('wp_enqueue_scripts', 'enqueue_custom_fill_styles');
add_action('enqueue_block_editor_assets', 'enqueue_custom_fill_styles');
