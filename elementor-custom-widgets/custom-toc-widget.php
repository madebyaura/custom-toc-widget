<?php
/**
 * Custom Table of Contents Widget for Elementor
 *
 * @package Custom_TOC_Widget
 */
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Custom_TOC_Widget extends Widget_Base {

    public function get_name() {
        return 'custom_toc';
    }

    public function get_title() {
        return ( 'Custom Table of Contents' );
    }

    public function get_icon() {
        return 'eicon-table-of-contents';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function get_script_depends() {
        return [ 'custom-toc-js' ];
    }

    public function render() {
        ?>
        <div id="custom-toc-widget">
            <h3>Table of Contents</h3>
            <ul class="custom-toc-list"></ul>
        </div>
        <?php
    }
}
