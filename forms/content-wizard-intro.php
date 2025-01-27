<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-wizard-panel-header">
    <p>
		<?php esc_html_e( 'Welcome to the setup wizard for coreActivity plugin! Here you can quickly set up the plugin, and if you need to adjust all the plugin features in more detail, you can do that later through various plugin panels.', 'coreactivity' ); ?>
    </p>
    <p>
		<?php esc_html_e( 'Using this wizard will reconfigure the plugin. Each option might affect one or more plugin settings.', 'coreactivity' ); ?>
    </p>
    <p>
		<?php esc_html_e( 'Let\'s start with few basics.', 'coreactivity' ); ?>
    </p>
</div>

<div class="d4p-wizard-panel-content">
    <div class="d4p-wizard-option-block d4p-wizard-block-yesno">
        <p><?php esc_html_e( 'Do you want to get forwarded IP for visitors?', 'coreactivity' ); ?></p>
        <div>
            <em><?php esc_html_e( 'Forwarded IP header key can be faked, but it is the only way to get real IP of visitors behind proxies or internal networks. By default, if this option is disabled, plugin will only use REMOTE_ADDR key for visitor IP.', 'coreactivity' ); ?></em>
			<?php coreactivity_wizard()->render_yes_no( 'intro', 'forwarded', 'no' ); ?>
        </div>
    </div>
    <div class="d4p-wizard-option-block d4p-wizard-block-yesno">
        <p><?php esc_html_e( 'Do you want to skip logging duplicated entries?', 'coreactivity' ); ?></p>
        <div>
            <em><?php esc_html_e( 'Plugin can attempt to detected duplicated events, and skip logging duplicated events inside the 24 hours period to avoid flooding database with the same entry over and over again.', 'coreactivity' ); ?></em>
			<?php coreactivity_wizard()->render_yes_no( 'intro', 'duplicated' ); ?>
        </div>
    </div>
    <div class="d4p-wizard-option-block d4p-wizard-block-yesno">
        <p><?php esc_html_e( 'Do you want to log Referer URL for each request?', 'coreactivity' ); ?></p>
        <div>
            <em><?php esc_html_e( 'Each request should referer URL, and depending on the request, this can be quite a long string. Referer is useful for checking the source of the request, but it can be faked by the request source. CoreActivity currently has no direct use for this value.', 'coreactivity' ); ?></em>
			<?php coreactivity_wizard()->render_yes_no( 'intro', 'referer', 'no' ); ?>
        </div>
    </div>
    <div class="d4p-wizard-option-block d4p-wizard-block-yesno">
        <p><?php esc_html_e( 'Do you want to log User Agent for each request?', 'coreactivity' ); ?></p>
        <div>
            <em><?php esc_html_e( 'Each request contains User Agent of the software (usually browser) making the request. This value can be faked by the request source. CoreActivity currently has no use for this value, but other plugins using the log may use it for some additional processing.', 'coreactivity' ); ?></em>
			<?php coreactivity_wizard()->render_yes_no( 'intro', 'user_agent' ); ?>
        </div>
    </div>
    <div class="d4p-wizard-option-block d4p-wizard-block-yesno">
        <p><?php esc_html_e( 'Do you want to log Device Detection for each request?', 'coreactivity' ); ?></p>
        <div>
            <em><?php esc_html_e( 'Based on User Agent for each request, plugin can detect the type of device, client, OS and other information. Or it can identify various types of Bots..', 'coreactivity' ); ?></em>
			<?php coreactivity_wizard()->render_yes_no( 'intro', 'device_detection' ); ?>
        </div>
    </div>
    <div class="d4p-wizard-option-block d4p-wizard-block-yesno">
        <p><?php esc_html_e( 'Do you want to add Admin bar menu?', 'coreactivity' ); ?></p>
        <div>
            <em><?php esc_html_e( 'Menu in the WordPress Admin bar will show quick links to the plugin panels for direct access from anywhere on the website where the admin bar is visible. It is only available to website administrators.', 'coreactivity' ); ?></em>
			<?php coreactivity_wizard()->render_yes_no( 'intro', 'admin_bar' ); ?>
        </div>
    </div>
</div>
