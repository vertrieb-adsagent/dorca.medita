<?php
/**
 * Dorca Medita Theme – Hauptvorlage.
 * Startseite: deutsche Landingpage. /es/: spanische Landingpage.
 * Alle anderen Seiten (z. B. Impressum): schlichte Inhaltsseite.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dorca_lang = dorca_medita_landing_lang();
if ( $dorca_lang ) {
	status_header( 200 );
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}

if ( $dorca_lang ) {
	$dorca_file = get_template_directory() . '/landing/' . $dorca_lang . '.html';
	if ( is_readable( $dorca_file ) ) {
		readfile( $dorca_file ); // phpcs:ignore
	}
} else {
	echo '<main class="dm-page">';
	echo '<a class="dm-page-back" href="' . esc_url( home_url( '/' ) ) . '">&larr; Dorca Medita</a>';
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			echo '<h1>' . esc_html( get_the_title() ) . '</h1>';
			the_content();
		}
	} else {
		echo '<h1>Seite nicht gefunden</h1><p>Diese Seite gibt es leider nicht. <a href="' . esc_url( home_url( '/' ) ) . '">Zur Startseite</a></p>';
	}
	echo '</main>';
}

wp_footer();
?>
</body>
</html>
