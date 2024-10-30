<?php

use Dev4Press\v51\Core\Quick\KSES;
use function Dev4Press\v51\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_panel     = panel()->a()->panel_object();
$_subpanel  = panel()->a()->subpanel;
$_subpanels = panel()->subpanels();

?>
<div class="d4p-sidebar">
    <div class="d4p-panel-scroller d4p-scroll-active">
        <div class="d4p-panel-title">
            <div class="_icon">
				<?php echo KSES::strong( panel()->r()->icon( $_panel->icon ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <h3><?php echo KSES::strong( $_panel->title ); ?></h3>
			<?php

			echo '<h4>' . KSES::strong( panel()->r()->icon( $_subpanels[ $_subpanel ]['icon'] ) ) . esc_html( $_subpanels[ $_subpanel ]['title'] ) . '</h4>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			?>
            <div class="_info">
				<?php

				echo esc_html( $_subpanels[ $_subpanel ]['info'] );

				if ( isset( $_subpanels[ $_subpanel ]['kb'] ) ) {
					$url   = $_subpanels[ $_subpanel ]['kb']['url'];
					$label = $_subpanels[ $_subpanel ]['kb']['label'] ?? __( 'Knowledge Base', 'd4plib' );

					?>

                    <div class="_kb">
                        <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $label ); ?></a>
                    </div>

					<?php
				}

				?>
            </div>
        </div>
        <div class="d4p-panel-buttons">
            <input type="submit" value="<?php esc_attr_e( 'Save Settings', 'd4plib' ); ?>" class="button-primary"/>
        </div>
        <div class="d4p-return-to-top">
            <a href="#wpwrap"><?php esc_html_e( 'Return to top', 'd4plib' ); ?></a>
        </div>
    </div>
</div>
