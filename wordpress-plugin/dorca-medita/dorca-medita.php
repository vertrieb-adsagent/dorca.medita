<?php
/**
 * Plugin Name:       Dorca Medita Landingpage
 * Description:       Landingpage für Dorca Medita auf Deutsch und Spanisch, mit Sprachwahl DE/ES. Shortcodes: [dorca_medita] (Deutsch) und [dorca_medita lang="es"] (Spanisch). Optional mit der Seitenvorlage «Dorca Medita – Vollbild».
 * Version:           1.0.0
 * Author:            Klarton
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * License:           GPL-2.0-or-later
 * Text Domain:       dorca-medita
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DORCA_MEDITA_DIR', plugin_dir_path( __FILE__ ) );
define( 'DORCA_MEDITA_TEMPLATE', 'dorca-medita-vollbild.php' );

/**
 * Shortcode [dorca_medita lang="de|es"].
 */
function dorca_medita_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'lang' => 'de' ), $atts, 'dorca_medita' );
	$lang = in_array( $atts['lang'], array( 'de', 'es' ), true ) ? $atts['lang'] : 'de';
	$file = DORCA_MEDITA_DIR . 'templates/' . $lang . '.html';

	if ( ! is_readable( $file ) ) {
		return '';
	}

	return file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
}
add_shortcode( 'dorca_medita', 'dorca_medita_shortcode' );

/**
 * Seitenvorlage «Dorca Medita – Vollbild»: ohne Header, Footer und Seitenränder des Themes.
 */
function dorca_medita_register_template( $templates ) {
	$templates[ DORCA_MEDITA_TEMPLATE ] = 'Dorca Medita – Vollbild';
	return $templates;
}
add_filter( 'theme_page_templates', 'dorca_medita_register_template' );

function dorca_medita_load_template( $template ) {
	if ( is_singular( 'page' ) && DORCA_MEDITA_TEMPLATE === get_page_template_slug( get_queried_object_id() ) ) {
		$file = DORCA_MEDITA_DIR . 'templates/vollbild.php';
		if ( is_readable( $file ) ) {
			return $file;
		}
	}
	return $template;
}
add_filter( 'template_include', 'dorca_medita_load_template', 99 );
