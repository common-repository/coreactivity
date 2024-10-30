<?php

use Dev4Press\v51\Core\Helpers\Vendors;
use Dev4Press\v51\Core\Quick\File;
use Dev4Press\v51\Core\Quick\KSES;
use function Dev4Press\v51\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$file_name = $file_name ?? 'changelog.md';

Vendors::parsedown();

$parsedown = new Parsedown();
$changelog = File::get_contents( panel()->a()->plugin()->path . $file_name );

$rendered = $parsedown->text( $changelog );

$rendered = preg_replace( '/<h1>.+?<\/h1>/', '', $rendered );
$rendered = preg_replace( '/<h2>.+?<\/h2>/', '', $rendered );
$rendered = preg_replace( '/<h3>(.+?)<\/h3>/', '<h4>$1</h4>', trim( $rendered ) );
$rendered = preg_replace( '/<h4>|<ul>|<li>/', PHP_EOL . "$0", $rendered );
$rendered = explode( PHP_EOL, $rendered );
$rendered = array_filter( $rendered );

$_is_first = true;
$_versions = array();
foreach ( $rendered as $line ) {
	preg_match( '/<h4>Version:\s(.+?)\..+?<\/h4>/', $line, $output );

	if ( isset( $output[1] ) ) {
		$version = $output[1];
		if ( ! in_array( $version, $_versions ) ) {
			$_versions[] = $version;

			if ( ! $_is_first ) {
				echo '</div></div>';
			}

			echo '<div class="d4p-info-block d4p-info-block-changelog">';
			echo '<h3>' . esc_html__( 'Major Version', 'd4plib' ) . ': <strong>' . esc_html( $version ) . '</strong></h3>';
			echo '<div>';

			$_is_first = false;
		}
	}

	echo KSES::standard( $line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

echo '</div></div>';
