# Dev4Press Library
## Migration Guide

### v3.9 => v4.0
#### Class Namespaces

* Dev4Press\v39\Core\Plugins\Shortcodes => Dev4Press\v40\WordPress\Legacy\Shortcodes
* Dev4Press\v39\Core\Plugins\Widget => Dev4Press\v40\WordPress\Legacy\Widget
* Dev4Press\v39\Core\Admin\Table => Dev4Press\v40\WordPress\Admin\Table
* Dev4Press\v39\Core\UI\Walker\CheckboxRadio => Dev4Press\v40\WordPress\Walker\CheckboxRadio
* Dev4Press\v39\Core\Helpers\Languages => Dev4Press\v40\API\Languages
* Dev4Press\v39\Core\Quick\DB => Dev4Press\v40\Core\Helpers\DB
* Dev4Press\v39\Core\Base => Dev4Press\v40\Core\Base\Obj

### v4.x => v4.8
#### Actions Name Prefixes

* d4p_mailer_notification_detected => dev4press_mailer_notification_detected
* d4p_settings_group_hidden_top => dev4press_settings_group_hidden_top
* d4p_settings_group_hidden_bottom => dev4press_settings_group_hidden_bottom
* d4p_settings_group_top => dev4press_settings_group_top
* d4p_settings_group_bottom => dev4press_settings_group_bottom
* d4p_install_db_delta => dev4press_install_db_delta
* d4p_process_option_call_back_for_$name => dev4press_process_option_call_back_for_$name
