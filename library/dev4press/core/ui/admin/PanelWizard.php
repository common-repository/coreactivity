<?php

namespace Dev4Press\v51\Core\UI\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class PanelWizard extends Panel {
	protected bool $sidebar = false;

	public function __construct( $admin ) {
		parent::__construct( $admin );

		$this->init_default_subpanels();
	}

	protected function init_default_subpanels() {
		$this->subpanels = array();

		foreach ( $this->a()->wizard()->panels as $panel => $obj ) {
			$this->subpanels[ $panel ] = array( 'title' => $obj['label'] );
		}
	}

	public function show() {
		$this->load( 'content-wizard.php' );
	}

	public function enqueue_scripts() {
		$this->a()->e()->css( 'wizard' )->js( 'wizard' );
	}
}
