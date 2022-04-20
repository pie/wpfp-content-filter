<?php

/*
Plugin Name: WP Favorite Posts: Filter Hooks
Description: By default, WPFP adds it's bookmark text to every 'single' post view, this plugin unhooks it and rehooks it checking that we're not on a product page before adding
Version: 0.1
Author: The team at PIE
Author URI: http://pie.co.de
License:     GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

/* PIE\WPFavouritePostsFilter is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.

PIE\WPFavouritePostsFilter is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with PIE\WPFavouritePostsFilter. If not, see https://www.gnu.org/licenses/gpl-3.0.en.html */

namespace PIE\WPFavouritePostsFilter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Re-add WP Favourite posts filter to dictate where the content shows
 */
function adjust_wpfp_filters(){
  remove_filter( 'the_content', '\wpfp_content_filter' );
  add_filter( 'the_content', __NAMESPACE__ . '\wpfp_content_filter' );
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\adjust_wpfp_filters' );

/**
 * Filter WP Favourite Posts content
 *
 * @param  string $content
 * @return string
 */
function wpfp_content_filter( $content ) {
    if ( is_page() ) {
      if ( strpos( $content,'{{wp-favorite-posts}}' ) !== false ) {
        $content = str_replace( '{{wp-favorite-posts}}', wpfp_list_favorite_posts(), $content );
      }
    }
    if ( strpos( $content,'[wpfp-link]' ) !== false ) {
        $content = str_replace( '[wpfp-link]', wpfp_link(1), $content );
    }
    if ( is_single() && ! is_singular( 'product' ) ) {
        if ( wpfp_get_option( 'autoshow' ) == 'before' ) {
            $content = wpfp_link(1) . $content;
        } else if ( wpfp_get_option( 'autoshow' ) == 'after' ) {
            $content .= wpfp_link(1);
        }
    }
    return $content;
}
