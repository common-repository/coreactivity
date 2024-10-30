<?php

use Dev4Press\v51\Core\Quick\KSES;
use Dev4Press\v51\Core\Options\Render;
use function Dev4Press\v51\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="d4p-content">
	<?php

	panel()->settings_fields();

	$class   = panel()->settings_class;
	$options = $class::instance();

	foreach ( panel()->subpanels() as $subpanel => $obj ) {
		if ( $subpanel == 'index' || $subpanel == 'full' ) {
			continue;
		}

		if ( isset( $obj['break'] ) ) {
			echo KSES::standard( panel()->r()->settings_break( $obj['break'], $obj['break-icon'] ?? '', $obj['break-info'] ?? '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo KSES::standard( panel()->r()->settings_group_break( $obj['title'], $obj['icon'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$groups = $options->get( $subpanel );

		Render::instance( panel()->a()->n(), panel()->a()->plugin_prefix )->prepare( $subpanel, $groups )->render();
	}

	?>

	<?php panel()->include_accessibility_control(); ?>
</div>
