<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'ni_email_setting' ) ) :
class ni_email_setting{
	function __construct() {
		add_action( 'admin_menu', array( $this, 'add_setting_page' ),110 );
		add_action( 'admin_init', array( $this, 'admin_init' ),110 );
		add_action( 'admin_init', array( $this, 'admin_init_save'),100 );
	}
	function add_setting_page(){
		add_submenu_page( "ni-email-dashboard", 'Setting', 'Setting', 'manage_options', 'ni-email-setting', array( $this, 'setting_page' ) );
	}
	function admin_init_save(){
		//echo '<pre>',print_r($_REQUEST,1),'</pre>';	
		//die;
		if (isset($_REQUEST["ni_email_report_option"])){
			$this->options = get_option( 'ni_email_report_option' );
			
			
		
			$option_value = $_REQUEST["ni_email_report_option"];
			
			$old_schedule = isset($this->options['ni_schedule_email'])? $this->options['ni_schedule_email']  :"every_24_hours";
		 	$new_schedule =	$option_value["ni_schedule_email"]; 
			
			
			
			if ($old_schedule != 	$new_schedule){
				wp_unschedule_event( time(), 'ni_do_email_report_cron_job', array() );
				wp_clear_scheduled_hook( 'ni_do_email_report_cron_job', array() );
				wp_schedule_event(time(), $new_schedule, 'ni_do_email_report_cron_job');
				
			}
			 //echo json_encode($_REQUEST["ni_email_report_option"]);
			update_option('ni_email_report_option',$_REQUEST["ni_email_report_option"]);
			//die;
		}
		
	}
	function setting_page(){
			// Set class property
		$this->options = get_option( 'ni_email_report_option' );
		//$this->options = get_option( 'invoice_setting_option' );
		
		echo "<pre>";
print_r($this->options);
echo "</pre>";
		
		?>
       <div class="wrap">
			<?php //screen_icon(); ?>
		  <!--  <h2>My Settings</h2>           -->
			<form method="post">
			<?php
				// This prints out all hidden setting fields
				settings_fields( 'ni_email_option_group' );   
				do_settings_sections( 'ni-email-setting' );
				submit_button(); 
			?>
			</form>
		</div>
		<?php
	}
	function admin_init(){
		register_setting(
			'ni_email_option_group', // Option group
			'ni_email_report_option', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);
		
		add_settings_section(
			'setting_section_id', // ID
			'Email Report Settings', // Title
			array( $this, 'print_section_info' ), // Callback
			'ni-email-setting' // Page
		);
		/*enable cron job*/ 
		add_settings_field(
			'enable_cron_job', 
			'Enable', 
			array( $this, 'enable_cron_job' ), 
			'ni-email-setting', 
			'setting_section_id'
		);    
		/*enable cron job*/ 
		add_settings_field(
			'ni_email_report_from_email', 
			'From Email Address', 
			array( $this, 'add_from_email' ), 
			'ni-email-setting', 
			'setting_section_id'
		); 
		/*enable cron job*/ 
		add_settings_field(
			'ni_email_report_to_email', 
			'To Email Address', 
			array( $this, 'add_to_email' ), 
			'ni-email-setting', 
			'setting_section_id'
		);
		/*enable cron job*/ 
		add_settings_field(
			'ni_email_report_subject_line', 
			'Subject Line', 
			array( $this, 'add_subject_line' ), 
			'ni-email-setting', 
			'setting_section_id'
		);
		/*enable cron job*/ 
		add_settings_field(
			'ni_schedule_email', 
			'Interval', 
			array( $this, 'add_schedule_email' ), 
			'ni-email-setting', 
			'setting_section_id'
		); 
		
		/*enable cron job*/ 
		add_settings_field(
			'ni_cron_url', 
			'Cron URL', 
			array( $this, 'add_cron_url'), 
			'ni-email-setting', 
			'setting_section_id'
		);          
		 
	}
	
	function add_cron_url(){
		$cron_url = site_url()."/wp-cron.php?niwoocr_email_report";		
		print_r("<a href=\"{$cron_url}\" target=\"_blank\">$cron_url</a>");
	}
	
	function add_subject_line(){
		printf(
			'<input style="width:300px" type="text" id="ni_email_report_subject_line" name="ni_email_report_option[ni_email_report_subject_line]" value="%s" />',
			isset( $this->options['ni_email_report_subject_line'] ) ? esc_attr( $this->options['ni_email_report_subject_line']) : 'Ni Sales Report Email '
		); 
	}
	
	function add_schedule_email() {
		//print_r( $this->options);
	    $intervals = array('every_fifteen_minute'=>'Every Fifteen Minute','every_hour'=>'Every Hour','every_6_hours'=>'6 Hours','every_12_hours'=>'12 Hours','every_24_hours'=>'24 Hours');
	   // $intervals = array('12 Hours','daily');
		$html = "";
		$html .= "<select  style=\"width:300px\" name='ni_email_report_option[ni_schedule_email]'>";
        foreach ($intervals as $k=>$v){
		   if (isset($this->options['ni_schedule_email'])) {
			   if ($this->options['ni_schedule_email']==$k)
			   $html .=  "<option value='{$k}' selected>{$v}</option>";
			   else
			   $html .= "<option value='{$k}'>{$v}</option>";	
		   }
		   else{
				$html .= "<option value='{$k}'>{$v}</option>";
		   }
        }
       $html .= "</select>";
	   echo $html;
	
	}
	function enable_cron_job() {
		$html = '<input  type="checkbox" id="enable_cron_job" name="ni_email_report_option[enable_cron_job]" value="1"' . checked(isset( $this->options['enable_cron_job'] ), true, false) . '/>';
		$html .= '<label for="enable_cron_job">enable cron job</label>';
		echo $html;
	
	}
	function add_from_email(){
		printf(
			'<input style="width:300px" type="text" id="ni_email_report_from_email" name="ni_email_report_option[ni_email_report_from_email]" value="%s" />',
			isset( $this->options['ni_email_report_from_email'] ) ? esc_attr( $this->options['ni_email_report_from_email']) : ''
		); 
	}
	function add_to_email(){
		printf(
			'<input style="width:300px" type="text" id="ni_email_report_to_email" name="ni_email_report_option[ni_email_report_to_email]" value="%s" />',
			isset( $this->options['ni_email_report_to_email'] ) ? esc_attr( $this->options['ni_email_report_to_email']) : ''
		); 
	}
	function print_section_info(){
		print 'Enter your settings below:';
	}
	function sanitize( $input ){
		if( !is_numeric( $input['id_number'] ) )
			$input['id_number'] = '';  
	
		if( !empty( $input['title'] ) )
			$input['title'] = sanitize_text_field( $input['title'] );
			
		if( !empty( $input['color'] ) )
			$input['color'] = sanitize_text_field( $input['color'] );
		return $input;
	}	
}
endif;
?>