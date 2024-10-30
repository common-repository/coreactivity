<?php

namespace Dev4Press\v51\Core\UI\Admin;

use Dev4Press\v51\Core\Quick\KSES;
use Dev4Press\v51\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Panel {
	private static $_current_instance = null;

	/** @var \Dev4Press\v51\Core\Admin\Plugin|\Dev4Press\v51\Core\Admin\Menu\Plugin|\Dev4Press\v51\Core\Admin\Submenu\Plugin */
	private $admin;

	/** @var \Dev4Press\v51\Core\UI\Admin\Render */
	private $render;
	protected $render_class = '\\Dev4Press\\v51\\Core\\UI\\Admin\\Render';
	protected $table_object = null;

	protected array $subpanels = array();
	protected bool $sidebar = true;
	protected bool $form = false;
	protected bool $table = false;
	protected bool $cards = false;
	protected bool $form_multiform = false;
	protected string $form_autocomplete = 'off';
	protected string $form_method = 'post';
	protected string $wrapper_class = '';
	protected string $default_subpanel = 'index';
	protected string $directory = '';

	public function __construct( $admin ) {
		$render = $this->render_class;

		$this->admin  = $admin;
		$this->render = $render::instance();

		$page_id = $this->admin->screen_id;

		if ( is_network_admin() && str_ends_with( $page_id, '-network' ) ) {
			$page_id = substr( $page_id, 0, - 8 );
		}

		add_action( 'load-' . $page_id, array( $this, 'screen_options_show' ) );

		add_action( $this->h( 'enqueue_scripts_early' ), array( $this, 'enqueue_scripts_early' ) );
		add_action( $this->h( 'enqueue_scripts' ), array( $this, 'enqueue_scripts' ) );
	}

	/** @return static */
	public static function instance( $admin = null ) {
		if ( is_null( self::$_current_instance ) && ! is_null( $admin ) ) {
			self::$_current_instance = new static( $admin );
		}

		return self::$_current_instance;
	}

	public function a() {
		return $this->admin;
	}

	public function r() {
		return $this->render;
	}

	public function h( $hook ) : string {
		return $this->a()->plugin_prefix . '_' . $hook;
	}

	public function subpanels() : array {
		return $this->subpanels;
	}

	public function header_fill() : string {
		return '';
	}

	public function object() : object {
		$subpanel = $this->current_subpanel();

		if ( isset( $this->subpanels[ $subpanel ] ) ) {
			return (object) $this->subpanels[ $subpanel ];
		}

		return $this->a()->panel_object();
	}

	public function current_subpanel() : string {
		$_subpanel = $this->a()->subpanel;

		if ( ! empty( $this->subpanels ) ) {
			$_available = array_keys( $this->subpanels );

			if ( ! in_array( $_subpanel, $_available ) ) {
				$_subpanel = $this->default_subpanel;
			}
		}

		return $_subpanel;
	}

	public function has_form() : bool {
		return $this->form;
	}

	public function has_sidebar() : bool {
		return $this->sidebar;
	}

	public function has_table() : bool {
		return $this->table;
	}

	public function has_cards() : bool {
		return $this->cards;
	}

	public function wrapper_class() : array {
		$_classes = array(
			'd4p-wrap',
			'd4p-plugin-' . $this->a()->plugin,
			'd4p-panel-' . $this->a()->panel,
		);

		$_subpanel = $this->current_subpanel();

		if ( ! empty( $_subpanel ) ) {
			$_classes[] = 'd4p-subpanel-' . $_subpanel;
		}

		if ( $this->has_sidebar() ) {
			$_classes[] = 'd4p-with-sidebar';
		} else {
			$_classes[] = 'd4p-full-width';
		}

		if ( $this->table ) {
			$_classes[] = 'd4p-with-table';
		}

		if ( $this->cards ) {
			$_classes[] = 'd4p-with-cards';
		}

		if ( ! empty( $this->wrapper_class ) ) {
			$_classes[] = $this->wrapper_class;
		}

		return $_classes;
	}

	public function validate_subpanel( $name ) {
		if ( empty( $this->subpanels ) ) {
			return '';
		}

		if ( isset( $this->subpanels[ $name ] ) ) {
			return $name;
		}

		$valid = array_keys( $this->subpanels );

		return $valid[0];
	}

	public function enqueue_scripts_early() {

	}

	public function enqueue_scripts() {

	}

	public function screen_options_show() {
	}

	public function prepare() {
	}

	public function show() {
		$this->include_header();

		echo '<div class="d4p-inside-wrapper">';
		if ( $this->has_form() ) {
			$this->form_tag_open();
		}

		echo '<div class="d4p-content-wrapper">';
		if ( $this->has_sidebar() ) {
			$this->include_sidebar();
		}

		$this->include_content();
		echo '</div>';

		if ( $this->has_form() ) {
			$this->form_tag_close();
		}
		echo '</div>';

		$this->include_footer();
	}

	public function forms_path_library( $path = '' ) : string {
		return $this->a()->path . Library::instance()->base_path() . '/forms/' . ( empty( $path ) ? '' : $path . '/' );
	}

	public function forms_path_plugin( $path = '' ) : string {
		return $this->a()->path . 'forms/' . ( empty( $path ) ? '' : $path . '/' );
	}

	public function include_header_fill() {
		echo KSES::standard( $this->header_fill() ); // phpcs:ignore WordPress.Security.EscapeOutput
	}

	public function include_messages() {
		$this->load( 'message.php' );
	}

	public function include_notices() {
		if ( $this->a()->panel == 'dashboard' ) {
			if ( $this->a()->settings()->i()->is_bbpress_plugin ) {
				$this->load( 'notices-bbpress.php' );
			}
		}
	}

	public function include_header( $name = '', $subname = '' ) {
		$this->interface_colors();
		$this->include_generic( 'header', $name, $subname );
	}

	public function include_footer( $name = '', $subname = '' ) {
		$this->include_generic( 'footer', $name, $subname );
	}

	public function include_sidebar( $name = '', $subname = '' ) {
		$this->include_generic( 'sidebar', $name, $subname );
	}

	public function include_content( $name = '', $subname = '' ) {
		$this->include_generic( 'content', $name, $subname );
	}

	public function form_tag_open() {
		$id  = $this->a()->plugin_prefix . '-form-' . $this->a()->panel;
		$enc = $this->form_multiform ? 'enctype="multipart/form-data"' : '';

		echo '<form method="' . esc_attr( $this->form_method ) . '" action="" id="' . esc_attr( $id ) . '" ' . $enc . ' autocomplete="' . esc_attr( $this->form_autocomplete ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function settings_fields( $action = 'update', $subpanel = false ) {
		$group   = $this->a()->plugin . '-' . $this->a()->panel;
		$handler = $this->a()->v();

		echo "<input type='hidden' name='option_page' value='" . esc_attr( $group ) . "' />";
		echo "<input type='hidden' name='" . esc_attr( $handler ) . "' value='postback' />";

		if ( ! empty( $action ) ) {
			echo "<input type='hidden' name='action' value='" . esc_attr( $action ) . "' />";
		}

		if ( $subpanel ) {
			echo "<input type='hidden' name='" . esc_attr( $this->a()->n() ) . "[subpanel]' value='" . esc_attr( $this->a()->subpanel ) . "' />";
		}

		wp_nonce_field( $group . '-options' );
	}

	public function form_tag_close() {
		echo '</form>';
	}

	public function include_accessibility_control() {
	}

	public function include_generic( $base, $name = '', $subname = '', $args = array() ) {
		$name    = $this->get_panel_suffix( $name );
		$subname = $this->get_subpanel_suffix( $subname );

		$fallback = $this->get_fallback_include( $base, $name, $subname );
		$content  = $this->get_content_include( $base, $name, $subname );

		$this->load( $content, $fallback, $base . '.php', $args );
	}

	public function get_table_object() {
		return null;
	}

	protected function get_content_include( $base, $name, $subname = '' ) : string {
		$content = $base . '-' . $name;

		if ( ! empty( $subname ) ) {
			$content .= '-' . $subname;
		}

		$content .= '.php';

		return $content;
	}

	protected function get_fallback_include( $base, $name, $subname = '' ) : string {
		return $base . '-' . $name . '.php';
	}

	protected function get_panel_suffix( $name = '' ) {
		return empty( $name ) ? $this->a()->panel : $name;
	}

	protected function get_subpanel_suffix( $subname = '' ) : string {
		return empty( $subname ) ? ( empty( $this->a()->subpanel ) ? '' : $this->a()->subpanel ) : $subname;
	}

	protected function interface_colors() {
		if ( $this->a()->auto_mod_interface_colors ) {
			?>

            <style>
                .<?php echo 'd4p-plugin-' . esc_html( $this->a()->plugin ); ?> {
                    --d4p-color-layout-accent: <?php echo esc_html( $this->a()->settings()->i()->color() ); ?>;
                    --d4p-color-sidebar-icon-text: <?php echo esc_html( $this->a()->settings()->i()->color() ); ?>;
                }
            </style>

			<?php
		}
	}

	protected function load( $name, $fallback = '', $default = '', $args = array() ) {
		$list = array(
			$this->forms_path_plugin( $this->directory ) . $name,
			$this->forms_path_plugin() . $name,
			$this->forms_path_library() . $name,
		);

		if ( ! empty( $fallback ) ) {
			$list[] = $this->forms_path_plugin( $this->directory ) . $fallback;
			$list[] = $this->forms_path_plugin() . $fallback;
			$list[] = $this->forms_path_library() . $fallback;
		}

		if ( ! empty( $default ) ) {
			$list[] = $this->forms_path_plugin( $this->directory ) . $fallback;
			$list[] = $this->forms_path_plugin() . $default;
			$list[] = $this->forms_path_library() . $default;
		}

		foreach ( $list as $path ) {
			if ( file_exists( $path ) ) {
				include $path;
				break;
			}
		}
	}
}
