<?php
/**
 * Plugin Name: 	EDD Plugin
 * Plugin URI:		https://example.com/
 * Description:		Awesome EDD extension
 * Version: 		1.0.0
 * Author:			example
 * Author URI: 		https://example.com/
 * Text Domain: 	edd-plugin
 */

/**
 * Class EDD_Plugin for plugin initiation
 *
 * @since 1.0
 */
class EDD_Plugin {
	const VERSION = '1.0.0';

	/**
	 * @var EDD_Plugin
	 */
	private static $instance = null;


	private function __construct() {
		$this->path = plugin_dir_path( __FILE__ );
		$this->base_file = $this->path . basename( __FILE__ );
		$this->include_path = trailingslashit( $this->path . 'includes' );

		$this->url = trailingslashit( plugins_url( '', __FILE__ ) );
		$this->assets_url = trailingslashit( $this->url . 'assets' );

		// Adding settings tab
		add_filter( 'plugin_action_links_' . plugin_basename( $this->base_file ), function ( $links ) {
			return array_merge( $links, array(
				sprintf(
					'<a href="%s">Options</a>',
					admin_url( 'edit.php?post_type=download&page=eddp' )
				),
			) );
		} );

		$this->includes();

		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * @since 1.0
	 * @return $this
	 */
	public static function instance() {
		if ( is_null( self::$instance ) && ! ( self::$instance instanceof EDD_Plugin ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Activation function hook
	 *
	 * @since 1.0
	 * @return void
	 */
	public function activation() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;
	}

	/**
	 * Deactivation function hook
	 *
	 * @since 1.0
	 * @return void
	 */
	public function deactivation() {

	}

	private function includes() {
		require_once $this->include_path . 'functions.php';
	}

	/**
	 * Enqueue scripts on admin
	 *
	 * @since 1.0
	 */
	public function admin_enqueue_scripts() {
		// Styles
		wp_enqueue_style( 'eddp-css', $this->assets_url . 'css/eddp.css', array(), self::VERSION );

		// Scripts
		wp_enqueue_script( 'eddp-js', $this->assets_url . 'js/eddp.js', array( 'jquery' ), self::VERSION, true );
		wp_localize_script( 'eddp-js', 'EDDP_Cron', [
			'site_title' => get_bloginfo( 'name' )
		] );
	}
}

/**
 * @return EDD_Plugin
 */
function EDDP() {
	if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
		return;
	}

	return EDD_Plugin::instance();
}

// After EDD initialized
add_action( 'plugins_loaded', 'EDDP', 11 );