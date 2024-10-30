<?php

use Dev4Press\v51\Core\Quick\KSES;
use Dev4Press\v51\Library;
use function Dev4Press\v51\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-sidebar">
    <div class="d4p-dashboard-badge" style="background-color: <?php echo esc_attr( panel()->a()->settings()->i()->color() ); ?>;">
        <div class="_icon">
			<?php echo KSES::strong( panel()->r()->icon( 'plugin-' . panel()->a()->plugin, '9x' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <h3>
			<?php echo KSES::strong( panel()->a()->title() ); ?>
        </h3>
        <div class="_version-wrapper">
            <span class="_edition"><?php echo esc_html( panel()->a()->settings()->i()->get_edition() ); ?></span>
            <span class="_version"><?php

				/* translators: Plugin version label. %s: Version number. */
				echo KSES::strong( sprintf( __( 'Version: %s', 'd4plib' ), '<strong>' . esc_html( panel()->a()->settings()->i()->version_full() ) . '</strong>' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				?></span>
        </div>
    </div>

	<?php

	if ( panel()->a()->buy_me_a_coffee ) {
		?>

        <div class="d4p-links-group buy-me-a-coffee">
            <a href="https://www.buymeacoffee.com/millan" target="_blank" rel="noopener">
                <img alt="BuyMeACoffee" src="<?php echo esc_url( panel()->a()->url . Library::instance()->base_path() . '/resources/gfx/buy_me_a_coffee.png' ); ?>"/>
            </a>
            <a href="https://ko-fi.com/milanpetrovic" target="_blank" rel="noopener">
                <img alt="KoFi" src="<?php echo esc_url( panel()->a()->url . Library::instance()->base_path() . '/resources/gfx/ko_fi.png' ); ?>"/>
            </a>
        </div>

		<?php
	}

	foreach ( panel()->sidebar_links as $group ) {
		if ( ! empty( $group ) ) {
			echo '<div class="d4p-links-group">';

			foreach ( $group as $_link ) {
				echo '<a class="' . esc_attr( $_link['class'] ) . '" href="' . esc_url( $_link['url'] ) . '">' . KSES::strong( panel()->r()->icon( $_link['icon'] ) ) . '<span>' . $_link['label'] . '</span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo '</div>';
		}
	}

	?>
</div>
