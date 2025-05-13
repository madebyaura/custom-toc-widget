<?php
/**
 * Twenty Twenty-Five-child functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five_Child
 * @since Twenty Twenty-Five_Child 1.0
 */


// Extend Elementor's Heading widget to add TOC custom fields
add_action('elementor/element/heading/section_title/after_section_end', function ($element, $args) {
    // Do not proceed if the element is not a heading widget.
    if ( 'heading' !== $element->get_name() ) {
        return;
    }

    // Start a new section in the Elementor panel called "TOC Settings"
    $element->start_controls_section(
        'custom_toc_section',
        [
            'label' => ('TOC Settings'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

    // Add a switch to include/exclude the heading in TOC.
    $element->add_control(
        'include_in_toc',
        [
            'label' => ( 'Include in TOC' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => ( 'Yes' ),
            'label_off' => ( 'No' ),
            'return_value' => 'yes',
            'default' => 'no',
        ]
    );

    // Add a text input for custom TOC title.
    $element->add_control(
        'toc_title',
        [
            'label' => ( 'TOC Title' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => ( 'Custom TOC title...' ),
            'condition' => [
                'include_in_toc' => 'yes',
            ],
        ]
    );

    $element->end_controls_section();
}, 10, 2);

/**
 * Inject TOC attributes and IDs into rendered heading widgets
 */
add_action('elementor/frontend/widget/before_render', function($widget) {
    // Do not proceed if the element is not a heading widget.
    if ( 'heading' !== $widget->get_name() ) {
        return;
    }

    $settings = $widget->get_settings_for_display();
    $include = $settings['include_in_toc'] ?? 'no';

    if ( $include === 'yes' ) {
        $tag = $settings['header_size'] ?? 'h2';
        $id = 'toc-heading-' . $widget->get_id();

        // Add ID to the actual heading tag
        add_filter( 'elementor/widget/render_content', function( $content, $widget_obj ) use ( $tag, $id ) {
           if ( 'heading' !== $widget_obj->get_name() ) {
                return $content;
            }

            $open_tag = '<' . $tag;
            $with_id = $open_tag . ' id="' . esc_attr($id) . '"';

            // Replace only the first occurrence of the opening tag
            $pos = strpos( $content, $open_tag );
            if ( $pos !== false ) {
                $content = substr_replace( $content, $with_id, $pos, strlen($open_tag) );
            }

            return $content;
        }, 10, 2);
    }
});

// Register the custom TOC widget
add_action('elementor/widgets/widgets_registered', function($widgets_manager) {
    require_once get_stylesheet_directory() . '/elementor-custom-widgets/custom-toc-widget.php';
    $widgets_manager->register( new \Custom_TOC_Widget() );
});
