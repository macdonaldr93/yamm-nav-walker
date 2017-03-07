<?php
/**
 * Class Name: Yamm_Nav_Walker & Yamm_Fw_Nav_Walker
 * GitHub URI: https://github.com/macdonaldr93/yamm-nav-walker
 * Description: A custom WordPress nav walker class to implement the Yamm 3 bootstrap mega menu navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 1.0.1
 * Author: Ryan Macdonald - @macdonaldr93
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class Yamm_Nav_Walker extends Walker_Nav_Menu
{
    public function check_current($classes)
    {
        return preg_match('/(current[-_])|active|dropdown/', $classes);
    }

    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $output .= ($depth == 0) ? "\n<ul class=\"dropdown-menu\">\n" . "\n<div class=\"yamm-content\">\n" . "\n<div class=\"row\">\n" : "\n<ul class=\"elementy-ul yamm-fw\">\n";
    }


    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $item_html = '';
        parent::start_el($item_html, $item, $depth, $args);

        if ($item->is_dropdown && ($depth === 0)) {
            $item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#"', $item_html);
            $item_html = str_replace('</a>', ' <b class="caret"></b></a>', $item_html);
        } elseif (stristr($item_html, 'li class="divider')) {
            $item_html = preg_replace('/<a[^>]*>.*?<\/a>/iU', '', $item_html);
        } elseif (stristr($item_html, 'li class="dropdown-header')) {
            $item_html = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html);
        }

        $item_html = apply_filters('roots_wp_nav_menu_item', $item_html);
        $output .= $item_html;
    }


    public function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
    {
        $element->is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));
        if ($element->is_dropdown) {
            $element->classes[] = 'dropdown';
        }
        if ($element && ($depth === 1)) {
            $element->classes[] = 'col-sm-4 menu-col';
        }

        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

    public function end_lvl(&$output, $depth = 0, $args = array())
    {
        $output .= ($depth == 0) ? "\n</div>\n" . "\n</div>\n" . "\n</ul>\n" : "\n</ul>\n";
    }
}

/**
 * Remove the id="" on nav menu items
 * Return 'menu-slug' for nav menu classes
 */
function yamm_roots_nav_menu_css_class($classes, $item)
{
    $slug    = sanitize_title($item->title);
    $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
    $classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

    $classes[] = 'menu-' . $slug;

    $classes = array_unique($classes);

    return array_filter($classes);
}
add_filter('nav_menu_css_class', 'yamm_roots_nav_menu_css_class', 10, 2);
add_filter('nav_menu_item_id', '__return_null');

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Use Yamm_Nav_Walker() by default
 */
function yamm_roots_nav_menu_args($args = '')
{
    $yamm_roots_nav_menu_args['container'] = false;

    if (!$args['items_wrap']) {
        $yamm_roots_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
    }

    if (current_theme_supports('bootstrap-top-navbar') && !$args['depth']) {
        $yamm_roots_nav_menu_args['depth'] = 3;
    }

    if (!$args['walker']) {
        $yamm_roots_nav_menu_args['walker'] = new Yamm_Nav_Walker();
    }

    return array_merge($args, $yamm_roots_nav_menu_args);
}
add_filter('wp_nav_menu_args', 'yamm_roots_nav_menu_args');

/**
 * Fallback to simple list
 */
function Yamm_Nav_Walker_menu_fallback()
{
    $fb_output = '<ul class="nav navbar-nav">';
    $fb_output .= '<li><a href="' . esc_url(home_url()) . '">' . __('Home', THEMETD) . '</a></li>';
    if (current_user_can('manage_options')) {
        $fb_output .= '<li><a href="' . admin_url('nav-menus.php') . '">' . __('Add a menu', THEMETD) . '</a></li>';
    }
    $fb_output .= '</ul>';

    echo $fb_output;
}

class Yamm_Fw_Nav_Walker extends Walker_Nav_Menu
{
    public function check_current($classes)
    {
        return preg_match('/(current[-_])|active|dropdown/', $classes);
    }

    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $output .= ($depth == 0) ? "\n<ul class=\"dropdown-menu\">\n" . "\n<div class=\"yamm-content\">\n" . "\n<div class=\"row\">\n" : "\n<ul class=\"elementy-ul yamm-fw\">\n";
    }


    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $item_html = '';
        parent::start_el($item_html, $item, $depth, $args);

        if ($item->is_dropdown && ($depth === 0)) {
            $item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#"', $item_html);
            $item_html = str_replace('</a>', ' <b class="caret"></b></a>', $item_html);
        } elseif (stristr($item_html, 'li class="divider')) {
            $item_html = preg_replace('/<a[^>]*>.*?<\/a>/iU', '', $item_html);
        } elseif (stristr($item_html, 'li class="dropdown-header')) {
            $item_html = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html);
        }

        $item_html = apply_filters('roots_wp_nav_menu_item', $item_html);
        $output .= $item_html;
    }


    public function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
    {
        $element->is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));
        if ($element->is_dropdown) {
            $element->classes[] = 'dropdown yamm-fw';
        }
        if ($element && ($depth === 1)) {
            $element->classes[] = 'col-sm-4 menu-col';
        }

        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

    public function end_lvl(&$output, $depth = 0, $args = array())
    {
        $output .= ($depth == 0) ? "\n</div>\n" . "\n</div>\n" . "\n</ul>\n" : "\n</ul>\n";
    }
}

/**
 * Remove the id="" on nav menu items
 * Return 'menu-slug' for nav menu classes
 */
function yamm_fw_roots_nav_menu_css_class($classes, $item)
{
    $slug    = sanitize_title($item->title);
    $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
    $classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

    $classes[] = 'menu-' . $slug;

    $classes = array_unique($classes);

    return array_filter($classes);
}
add_filter('nav_menu_css_class', 'yamm_fw_roots_nav_menu_css_class', 10, 2);
add_filter('nav_menu_item_id', '__return_null');

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Use Yamm_Fw_Nav_Walker() by default
 */
function yamm_fw_roots_nav_menu_args($args = '')
{
    $yamm_fw_roots_nav_menu_args['container'] = false;

    if (!$args['items_wrap']) {
        $yamm_fw_roots_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
    }

    if (current_theme_supports('bootstrap-top-navbar') && !$args['depth']) {
        $yamm_fw_roots_nav_menu_args['depth'] = 3;
    }

    if (!$args['walker']) {
        $yamm_fw_roots_nav_menu_args['walker'] = new Yamm_Fw_Nav_Walker();
    }

    return array_merge($args, $yamm_fw_roots_nav_menu_args);
}
add_filter('wp_nav_menu_args', 'yamm_fw_roots_nav_menu_args');

/**
 * Fallback to simple list
 */
function Yamm_Fw_Nav_Walker_menu_fallback()
{
    $fb_output = '<ul class="nav navbar-nav">';
    $fb_output .= '<li><a href="' . esc_url(home_url()) . '">' . __('Home', THEMETD) . '</a></li>';
    if (current_user_can('manage_options')) {
        $fb_output .= '<li><a href="' . admin_url('nav-menus.php') . '">' . __('Add a menu', THEMETD) . '</a></li>';
    }
    $fb_output .= '</ul>';

    echo $fb_output;
}
