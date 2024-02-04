<?php
/*
Plugin Name: Ni WooCommerce Sales Report Email
Description: The Ni WooCommerce Sales Report Email plugin allows users to receive periodic email reports on order status and sales, providing a comprehensive overview of sales data, including order counts, monthly and yearly sales, graphical representations of sales reports, and insights into the top-selling orders.
Author: anzia
Author URI:  http://naziinfotech.com/
Plugin URI: https://wordpress.org/plugins/ni-woocommerce-sales-report-email/
Version: 3.1.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/agpl-3.0.html
Requires at least: 4.7
Tested up to: 6.4.2
WC requires at least: 3.0.0
WC tested up to: 8.4.0
Last Updated Date: 17-December-2023
Requires PHP: 7.0


*/
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'NiWooER_Email_Report' ) ) {
	class NiWooER_Email_Report {
		function __construct() {
			$ni_core_settings = array(
			 "prefix" => "ni-"
			);
			include_once('include/ni-init.php'); 
			$ni_init = new ni_email_report_init($ni_core_settings);
			
			add_action( 'activated_plugin',  array(&$this,'niwooer_activation_redirect' ));
		}
		static   function niwooer_activation_redirect($plugin){
			 if( $plugin == plugin_basename( __FILE__ ) ) {
				exit( wp_redirect( admin_url( 'admin.php?page=niwooer-setting' ) ) );
			}
		}
		
	}
	$obj =  new  NiWooER_Email_Report();
}

?>