<?php
/**
 * Plugin Name: WP Auto Post Link Title Attributes
 * Plugin URI: https://github.com/wpexplorer/wpex-auto-link-titles
 * Description: Automatically adds link title attributes to links within posts that don't have them.
 * Author: AJ Clarke
 * Author URI: http://www.wpexplorer.com
 * Version: 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Only needed on the front-end
if ( is_admin() ) {
	return;
}

function wpex_auto_add_link_titles( $content ) {

	// No need to do anything if there isn't any content
	if ( empty( $content ) ) {
		return;
	}

	// Define links array
	$links = array();

	// Get page content
	$html = new DomDocument;
	$html->loadHTML( $content ); 
	$html->preserveWhiteSpace = false;

	// Loop through all links
	foreach( $html->getElementsByTagName( 'a' ) as $link ) {

		// Get link text
		$link_text = ! empty( $link->textContent ) ? $link->textContent : '';

		// Save links and link text in $links array
		if ( empty( $link->getAttribute( 'title' ) ) ) {
			$links[$link_text] = $link->getAttribute( 'href' );
		}

	}

	// Loop through links and update post content to add link titles
	if ( ! empty( $links ) ) {
		foreach ( $links as $text => $link ) {
			if ( $link && $text ) {
				$replace = $link .'" title="'. $text .'"';
				$content = str_replace( $link .'"', $replace, $content );
			}

		}
	}

	// Return post content
	return $content;

}
add_filter( 'the_content', 'wpex_auto_add_link_titles' );