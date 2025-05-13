<?php

/**
 * Custom Table of Contents Widget for Elementor
 *
 * @package Custom_TOC_Widget
 */

use Elementor\Widget_Base;
use Elementor\Plugin;

if (! defined('ABSPATH')) exit; // Exit if accessed directly

class Custom_TOC_Widget extends Widget_Base
{

    public function get_name(){
        return 'custom_toc';
    }

    public function get_title(){
        return ('Custom Table of Contents');
    }

    public function get_icon(){
        return 'eicon-table-of-contents';
    }

    public function get_categories(){
        return ['general'];
    }

    public function render(){
        $document = Plugin::$instance->documents->get_current();
        if (!$document) {
            return;
        }

        $elements = $document->get_elements_data();
        $toc_items = $this->get_toc_headings($elements);

        if (!empty($toc_items)) {
        ?>
            <div class="custom-toc">
                <ul>
                    <?php foreach ($toc_items as $item): ?>
                        <li>
                            <?php // <?= is shorthand for <?php echo ?> 
                            <a href="#<?= esc_attr($item['id']) ?>">
                                <?= esc_html($item['title']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php
        }
    }

    private function get_toc_headings(array $elements): array
    {
        $items = [];

        foreach ($elements as $element) {
            // Check if it's a heading widget
            if (
                ($element['elType'] ?? null) === 'widget' &&
                ($element['widgetType'] ?? null) === 'heading'
            ) {
                $settings = $element['settings'] ?? [];

                if (($settings['include_in_toc'] ?? 'no') === 'yes') {
                    $items[] = [
                        'id'    => 'toc-heading-' . ($element['id'] ?? uniqid()),
                        'title' => $settings['toc_title'] ?? strip_tags($settings['title'] ?? '')
                    ];
                }
            }

            // Recurse through nested elements
            if (!empty($element['elements'])) {
                $items = array_merge($items, $this->get_toc_headings($element['elements']));
            }
        }

        return $items;
    }
}
