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
add_action('elementor/element/heading/section_title/after_section_end', function($element, $args) {
    if ( 'heading' !== $element->get_name() ) {
        return;
    }

    $element->start_controls_section(
        'custom_toc_section',
        [
            'label' => ('TOC Settings'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

    $element->add_control(
        'include_in_toc',
        [
            'label' => ( 'Include in TOC'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => ( 'Yes' ),
            'label_off' => ( 'No' ),
            'return_value' => 'yes',
            'default' => 'yes',
        ]
    );

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

// Add frontend render attributes to support TOC JS
add_action('elementor/frontend/widget/before_render', function( $widget ) {
    if ( 'heading' !== $widget->get_name() ) {
        return;
    }

    $settings = $widget->get_settings_for_display();

    if ( !empty($settings['include_in_toc']) && $settings['include_in_toc'] === 'yes' ) {
        $widget->add_render_attribute('_wrapper', 'data-include-in-toc', 'yes');

        if ( !empty($settings['toc_title']) ) {
            $widget->add_render_attribute('_wrapper', 'data-toc-title', esc_attr($settings['toc_title']));
        }
    }
});
