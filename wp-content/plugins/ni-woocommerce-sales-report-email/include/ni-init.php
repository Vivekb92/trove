<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'ni_email_report_init' ) ) :
include_once("ni-function.php");
class ni_email_report_init extends ni_sales_report_email_function{
	function __construct(){
		
		$page = "";
		add_action('admin_init', array( &$this, 'admin_init' ) );
		add_action( 'admin_menu',  array(&$this,'admin_menu' ),101);
		add_action( 'wp_ajax_ni_email_report_action',  array(&$this,'ni_email_report_action' )); /*used in form field name="action" value="my_action"*/
		add_action( 'admin_enqueue_scripts',  array(&$this,'admin_enqueue_scripts' ));
		
		//$this->add_setting_page();
		
		/*Cron job*/
		add_action( 'wp',   array(&$this,'ni_wp_init_cron'));
		add_filter( 'cron_schedules', array($this, 'add_custom_cron_schedule' ));
		add_action( 'ni_do_email_report_cron_job',  array(&$this,'ni_email_report_cron_job') );	
		
		add_action( 'init',  array(&$this,'init') );	
		//add_action( 'do_every_12_hours',  array(&$this,'ni_email_report_cron_job') );	
		/*End Cron job*/
		
				
	}
	
	function init(){
		if(isset($_REQUEST['niwoocr_email_report'])){
			if(defined('DOING_CRON')){
				$this->ni_email_report_cron_job();
			}
		}
	}
	/*add menu*/
	function admin_menu(){
		add_menu_page('Ni Email Report','Ni Email Report','manage_options','ni-email-dashboard',array(&$this,'add_menu_page'),plugins_url( 'images/icon.png', __FILE__ ),59.36);
    	//add_submenu_page($this->constant_variable['plugin_menu'], 'Summary', 'Sales Summary', 'manage_options',$this->constant_variable['plugin_menu'] , array(&$this,'add_menu_page'));
    	add_submenu_page('ni-email-dashboard', 'Dashboard', 'Dashboard', 'manage_options', 'ni-email-dashboard' , array(&$this,'add_menu_page'));
		add_submenu_page('ni-email-dashboard', 'Order List', 'Order List', 'manage_options', 'ni-email-order-list' , array(&$this,'add_menu_page'));
		
		add_submenu_page('ni-email-dashboard', 'Setting', 'Setting', 'manage_options', 'niwooer-setting' , array(&$this,'add_menu_page'));
		
		add_submenu_page('ni-email-dashboard', 'Add-ons', 'Add-ons', 'manage_options', 'ni-add-ons-email-report' , array(&$this,'add_menu_page'));
	}
	function get_menu_page_name(){
		$admin_page  =  array();
		$admin_page[] = 'ni-email-dashboard';
		$admin_page[] = 'ni-email-order-list';
		$admin_page[] = 'ni-add-ons-email-report';
		$admin_page[] = 'ni-email-setting';
		$admin_page[] = 'niwooer-setting';
		
		return $admin_page;
		
	}
	function admin_enqueue_scripts(){
		
		 $admin_page = $this->get_menu_page_name();
		 $page = sanitize_text_field(isset($_REQUEST["page"])?$_REQUEST["page"]:'');
		 if (!in_array($page,  $admin_page)){
		 	return ;
		 }
		 wp_register_style( 'niwooer-dashboard-css', plugins_url( '../assets/css/ni-email-dashboard.css', __FILE__ ));
		 wp_enqueue_style( 'niwooer-dashboard-css' );
	
	
		wp_register_style( 'niwooer-font-awesome-css', plugins_url( '../assets/css/font-awesome.css', __FILE__ ));
		wp_enqueue_style( 'niwooer-font-awesome-css' );
		
		wp_register_script( 'niwooer-amcharts-script', plugins_url( '../assets/js/amcharts/amcharts.js', __FILE__ ) );
		wp_enqueue_script('niwooer-amcharts-script');
	

		wp_register_script( 'niwooer-light-script', plugins_url( '../assets/js/amcharts/light.js', __FILE__ ) );
		wp_enqueue_script('niwooer-light-script');
	
		wp_register_script( 'niwooer-pie-script', plugins_url( '../assets/js/amcharts/pie.js', __FILE__ ) );
		wp_enqueue_script('niwooer-pie-script');
		
		
		wp_register_style('niwooer-bootstrap-css', plugins_url('../assets/css/lib/bootstrap.min.css', __FILE__ ));
		wp_enqueue_style('niwooer-bootstrap-css' );
	
		wp_enqueue_script('niwooer-bootstrap-script', plugins_url( '../assets/js/lib/bootstrap.min.js', __FILE__ ));
		wp_enqueue_script('niwooer-popper-script', plugins_url( '../assets/js/lib/popper.min.js', __FILE__ ));
	
		if ($page =='ni-email-order-list'){
			wp_register_script( 'niwooer-email-order-list-script', plugins_url( '../assets/js/ni-woocommerce-sales-report-email.js', __FILE__ ) );
			wp_enqueue_script('niwooer-email-order-list-script');
		}
		if ($page =='ni-email-setting' ||$page =='niwooer-setting'){
			wp_register_script( 'niwooer-setting-script', plugins_url( '../assets/js/niwooer-setting.js', __FILE__ ) );
			wp_enqueue_script('niwooer-setting-script');
		}
	
		wp_enqueue_script( 'ajax-script-email', plugins_url( '../assets/js/script.js', __FILE__ ), array('jquery') );
		wp_localize_script( 'ajax-script-email', 'ajax_object_email',array( 'ajax_email_report_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );		 
		 
		
	}
	function add_menu_page(){
		
			
		   	$page = sanitize_text_field(isset($_REQUEST["page"])?$_REQUEST["page"]:'');
			if ($page =="ni-email-order-list") {
				include_once("ni-email-order-list.php");
				$obj =  new ni_email_order_list();
				$obj->page_init();
				die;
				
			}
			
			if ($page =="ni-email-dashboard") {
				include_once("ni-email-dashboard.php");
				$obj =  new ni_email_dashboard();
				$obj->page_init();
				die;
			}
			if ($page=="ni-add-ons-email-report"){
				include_once("ni-addons.php");
				$obj =  new email_report_ni_addons();
				$obj->page_init();
				die;
			}
			if ($page=="niwooer-setting"){
				include_once("niwooer-setting.php");
				$obj =  new NiWooER_Setting();
				$obj->page_init();
				die;
			}
		
	}
	function admin_init(){
		if (isset($_REQUEST["call"])){
			if ($_REQUEST["call"] ==1){
				$this->get_daily_email_report("DAY",date_i18n("Y-m-d"),date_i18n("Y-m-d"));
				die;
			}
		}
	}
	function add_setting_page(){
		include_once("ni-email-setting.php");	
		$obj = new ni_email_setting();
	}
	function ni_email_report_action(){
			$email_report_action = sanitize_text_field(isset($_REQUEST["email_report_action"])?$_REQUEST["email_report_action"]:'');
			if ($email_report_action  =="ni_email_report_list") {
				
				include_once("ni-email-order-list.php");
				$obj =  new ni_email_order_list();
				$obj->get_email_report_list();	
			}
			if ($email_report_action  =="ni_send_email") {
				include_once("ni-email-order-list.php");
				$obj =  new ni_email_order_list();
				$obj->ni_send_email_report();
			}
			if ($email_report_action  =="niwooer_save_setting") {
				include_once("niwooer-setting.php");
				
				$obj =  new NiWooER_Setting();
				$obj->ajax_init();
			}
		
		die;
	}
	/*Cron function Start*/
	function ni_wp_init_cron(){
		
		$this->options = get_option( 'ni_email_report_option');
		
		//$enable 	= "yes";
		
		$enable 	= isset( $this->options['enable_cron_job'])?"yes" : "no";
		$schedule 	=  isset($this->options['ni_schedule_email'])?"yes":"no";
		
		
		if ($enable =="yes"){
			if (!wp_next_scheduled('ni_do_email_report_cron_job')) {
				wp_schedule_event(time(), $schedule, 'ni_do_email_report_cron_job');
			}
		}
			
	}
	function add_custom_cron_schedule(){
		
		 $schedules['every_fifteen_minute'] = array(
			'interval' => (15*60),
			'display' => __( 'Every 15 Minute', 'cr' )
		  );
		  
		  $schedules['every_hour'] = array(
			'interval' => (3600),
			'display' => __( 'Every Hour', 'cr' )
		  );
		  
		  $schedules['every_12_hours'] = array(
			'interval' => (43200),
			'display' => __( 'Every 12 Hours', 'cr' )
		  );
		  
		  $schedules['every_6_hours'] = array(
			'interval' => (216000),
			'display' => __( 'Every 6 Hours', 'cr' )
		  );
		  
		  $schedules['every_24_hours'] = array(
			'interval' => (86400),
			'display' => __( 'Every 24 Hours', 'cr' )
		  );
		  
	  	return $schedules;
	}
	function ni_email_report_cron_job(){
		
		//$output =" every_fifteen_minute ";
		$this->options = get_option( 'ni_email_report_option');
		$ni_to_email 	=  $this->options['ni_email_report_to_email'];
		$ni_from_email 	=  $this->options['ni_email_report_from_email'];
		$ni_subject_line =  $this->options['ni_email_report_subject_line'];
		
		$ni_subject_line  = $ni_subject_line ." | ". date_i18n("D M d, Y G:i"); 
		
		ob_start();	
		$output = ob_get_contents();
		//include_once("ni-email-dashboard.php");
		//$obj =  new ni_email_dashboard();
		//$obj->cron_email(date_i18n("Y-m-d"),date_i18n("Y-m-d"));
		$this->get_daily_email_report("DAY",date_i18n("Y-m-d"),date_i18n("Y-m-d"));
		$output = ob_get_contents();
		ob_end_clean();
		
		$ni_to_email  = isset($this->options['ni_email_report_to_email']) ? $this->options['ni_email_report_to_email'] : '';
		$ni_to_email  = explode(",",$ni_to_email);
		
		$to 		= $ni_to_email ; // <– replace with your address here
		$subject 	= $ni_subject_line;
		$message 	= $output;
		$from 		= $ni_from_email ;
		
		$headers =  array();
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		if ($from)
		$headers[] = 'From:  <' .$from. '>';
		//if ($add_cc_email)
		//$headers[] = 'Cc: <' .$add_cc_email. '>';
		
		
		 $status = wp_mail($to, $subject, $message, $headers);
		 if ( $status){
			echo "Message has been sent";
		}else{
			echo "Message was not sent ";
		}
		
	
	}
	/*Cron function End*/
}
endif;
?>