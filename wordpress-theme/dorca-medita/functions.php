<?php
/**
 * Dorca Medita Theme – Funktionen.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'dorca-medita', get_stylesheet_uri(), array(), '1.0.0' );
} );

/**
 * Pfad der aktuellen Anfrage relativ zur WordPress-Startseite, z. B. "" oder "es".
 */
function dorca_medita_path() {
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/'; // phpcs:ignore
	$path = trim( (string) wp_parse_url( $uri, PHP_URL_PATH ), '/' );
	$home = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	if ( '' !== $home && 0 === strpos( $path, $home ) ) {
		$path = trim( substr( $path, strlen( $home ) ), '/' );
	}
	return $path;
}

/** Spanische Landingpage unter /es/ */
function dorca_medita_is_es_url() {
	return 'es' === dorca_medita_path();
}

/** Keine 404-Seite für /es/ */
add_filter( 'pre_handle_404', function ( $preempt ) {
	return dorca_medita_is_es_url() ? true : $preempt;
} );

/** Welche Landingpage gezeigt wird: 'de', 'es' oder null (normale Seite). */
function dorca_medita_landing_lang() {
	if ( dorca_medita_is_es_url() ) {
		return 'es';
	}
	if ( is_front_page() || is_home() ) {
		return 'de';
	}
	return null;
}

add_filter( 'pre_get_document_title', function ( $title ) {
	$lang = dorca_medita_landing_lang();
	if ( 'es' === $lang ) {
		return 'Dorca Medita · Terapia craneosacral, fascial y aromaterapia en Zúrich';
	}
	if ( 'de' === $lang ) {
		return 'Dorca Medita · Kraniosakrale Therapie, Faszientherapie & Aromatherapie in Zürich';
	}
	return $title;
} );

add_filter( 'language_attributes', function ( $output ) {
	return dorca_medita_is_es_url() ? 'lang="es"' : $output;
} );

add_action( 'wp_head', function () {
	$lang = dorca_medita_landing_lang();
	if ( ! $lang ) {
		return;
	}
	$desc = 'es' === $lang
		? 'Terapia craneosacral, terapia fascial, aromaterapia, masaje clásico y facelift fascial con Dorca Felder en la Bahnhofplatz de Zúrich.'
		: 'Kraniosakrale Therapie, Faszientherapie, Aromatherapie, klassische Massage und Faszien-Facelift bei Dorca Felder am Bahnhofplatz Zürich.';
	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="de" href="' . esc_url( home_url( '/' ) ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="es" href="' . esc_url( home_url( '/es/' ) ) . '">' . "\n";
}, 1 );
