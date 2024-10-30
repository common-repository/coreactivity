<?php

use Dev4Press\v51\Library;
use function Dev4Press\v51\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-whatsnew-section core-grid">
    <div class="core-row">
        <div class="core-col-sm-12 core-col-md-6">
            <h3><?php esc_html_e( 'Support plugin development', 'd4plib' ); ?></h3>
            <p><?php

				/* translators: About Donate page donation information. %s: Plugin name. */
				echo esc_html( sprintf( __( '%s is a free plugin, and if you find it useful or want to support its future development, please consider donating.', 'd4plib' ), panel()->a()->settings()->i()->name() ) );

				?></p>
            <p>
                <a href="https://www.buymeacoffee.com/millan" target="_blank" rel="noopener">
                    <img style="max-width: 230px" alt="BuyMeACoffee" src="<?php echo esc_url( panel()->a()->url . Library::instance()->base_path() . '/resources/gfx/buy_me_a_coffee.png' ); ?>"/>
                </a>
                <a href="https://ko-fi.com/milanpetrovic" target="_blank" rel="noopener">
                    <img style="max-width: 230px" alt="Ko-Fi" src="<?php echo esc_url( panel()->a()->url . Library::instance()->base_path() . '/resources/gfx/ko_fi.png' ); ?>"/>
                </a>
            </p>
        </div>
        <div class="core-col-sm-12 core-col-md-6">
            <h3><?php esc_html_e( 'Development repository', 'd4plib' ); ?></h3>
            <p><?php

				/* translators: About Donate page development information. %s: Plugin name. */
				echo esc_html( sprintf( __( '%s has a GitHub repository where you can contribute to development, or submit feature requests and suggestions.', 'd4plib' ), panel()->a()->settings()->i()->name() ) );

				?></p>
            <p>
                <a href="<?php echo esc_url( panel()->a()->settings()->i()->github_url ); ?>" target="_blank" rel="noopener">
                    <span class="github-repo-button"><i class="d4p-icon d4p-brand-github-alt d4p-icon-2x"></i><span>GitHub Repository</span></span>
                </a>
            </p>
        </div>
    </div>
</div>
