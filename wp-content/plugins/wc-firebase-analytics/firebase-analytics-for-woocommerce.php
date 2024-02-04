<?php
/**
 * Plugin Name: Integration of Firebase Analytics and WooCommerce
 * Plugin URI: https://woocommerce.com/products/firebase-analytics-for-woocommerce/
 * Description: This plugin integrates firebase and woocommerce, which will help in generating website analytics.
 * Version: 0.2.8
 * Author: Appmaker
 * Author URI: https://appmaker.xyz/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Woo: 5220825:6041a9598a130f9c3e847e2da2f6a371
 * WC requires at least: 2.6.0
 * WC tested up to: 5.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;}
class WC_Firebase_Analytics {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'error_notice_message' ) );
		add_action( 'plugins_loaded', array( $this, 'include_files' ) );
		add_action( 'admin_menu', array( $this, 'wp_firebase_analytics_menu' ) );
		add_action( 'wp_head', array( $this, 'wp_firbase_analytics_settings' ) );
		register_activation_hook( __FILE__, array( $this, 'wp_add_data' ) );
		register_deactivation_hook( __FILE__, array( $this, 'wp_remove_data' ) );
		$this->options = get_option( 'wc_analytics_settings' );
		if ( empty( $this->options ) &&  ! ( isset( $_GET['page'] ) && 'wc-firebase-analytics' == $_GET['page'] ) ) {
			add_action( 'admin_notices', array( $this, 'show_settings_admin_message' ) );
		}
	}
	public function show_settings_admin_message() {
		?>
		<div class="notice notice-error" style="display: flex;">
				<a href="https://appmaker.xyz/woocommerce?utm_source=woocommerce-plugin&utm_medium=admin-notice&utm_campaign=after-plugin-install" class="logo" style="margin: auto;"><img src="https://storage.googleapis.com/stateless-appmaker-pages-wp/2019/04/10b81502-mask-group-141.png" alt="Appmaker.xyz"/></a>
				<div style="flex-grow: 1; margin: 15px 15px;">
					<h4 style="margin: 0;">Configure to continue</h4>
					<p><?php echo 'Ouch!😓 It appears that your firebase analytics is not configured correctly. Kindly configure with correct details.'; ?></p>
				</div>
				<a href="admin.php?page=wc-firebase-analytics" class="button button-primary" style="margin: auto 15px; background-color: #f16334; border-color: #f16334; text-shadow: none; box-shadow: none;">Take me there !</a>
		</div>
		<?php
	}
	// Actions perform to check if Woocommerce plugin is active
	public function error_notice_message() {
		$class   = 'notice notice-error';
		$message = 'Requires WooCommerce installed and activate to work properly.';
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			sprintf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
		}
	}

	// Actions perform to include files
	public function include_files() {
		require_once dirname( __FILE__ ) . '/includes/wc-analytics-hooks.php';
	}

	// Actions perform at loading of admin menu
	public function wp_firebase_analytics_menu() {
		add_menu_page(
			__( 'Analytics Settings', 'fbsanwoo' ),
			__( 'Analytics Settings', 'fbsanwoo' ),
			'manage_options',
			'wc-firebase-analytics',
			array( &$this, 'wp_include_file_path' ),
			'dashicons-chart-area'
		);
	}

	// Actions perform on loading of menu pages
	public function wp_include_file_path() {
		$screen = get_current_screen();
		if ( current_user_can( 'edit_posts' ) ) {
			if ( strpos( $screen->base, 'wc-firebase-analytics' ) !== false ) {
				include dirname( __FILE__ ) . '/includes/wc-analytics-settings.php';
			}
		}
	}

	// Loading JS at the head section of front end.
	public function wp_firbase_analytics_settings() {
		echo get_option( 'wc_analytics_settings' );

	}

	// Actions perform on activation of plugin
	public function wp_add_data() {
		add_option( 'wc_analytics_settings', '' );
	}


	// Actions perform on de-activation of plugin
	public function wp_remove_data() {
		delete_option( 'wc_analytics_settings' );
	}

}
new WC_Firebase_Analytics();

add_action( 'activated_plugin', 'appmaker_firebase_plugin_activated' );
function appmaker_firebase_plugin_activated( $plugin ) {
	if ( plugin_basename( __FILE__ ) == $plugin ) {
		wp_safe_redirect( admin_url( 'admin.php?page=wc-firebase-analytics' ) );
		exit;
	}
}
