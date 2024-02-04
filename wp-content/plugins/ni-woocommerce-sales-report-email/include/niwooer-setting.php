<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'NiWooER_Setting' ) ) {
	include_once("ni-function.php");
	class NiWooER_Setting extends ni_sales_report_email_function{
		function __construct() {
			
		}
		function page_init(){
			 $intervals = array('every_fifteen_minute'=>'Every Fifteen Minute','every_hour'=>'Every Hour','every_6_hours'=>'6 Hours','every_12_hours'=>'12 Hours','every_24_hours'=>'24 Hours');
			 $ni_email_report_option = get_option('ni_email_report_option' );
			
			
			
		    $enable_cron_job = 		intval (isset($ni_email_report_option["enable_cron_job"])?$ni_email_report_option["enable_cron_job"]:0); 
		 	$from_email = 		sanitize_email (isset($ni_email_report_option["ni_email_report_from_email"])?$ni_email_report_option["ni_email_report_from_email"]:'');
			$to_email = 		isset($ni_email_report_option["ni_email_report_to_email"])?$ni_email_report_option["ni_email_report_to_email"]:'';
			$subject_line = 	sanitize_text_field (isset($ni_email_report_option["ni_email_report_subject_line"])?$ni_email_report_option["ni_email_report_subject_line"]:'');
			$schedule_email = 	sanitize_text_field (isset($ni_email_report_option["ni_schedule_email"])?$ni_email_report_option["ni_schedule_email"]:'');
			 
		 ?>
         <div class="container-fluid" id="niwooer">
		 	<div class="row">
				<div class="col-md-8"  style="padding:0px;">
					<div class="card" style="max-width:80% ">
						<div class="card-header niwooer-plugin-color">
							<?php _e('Email Report Setting', 'niwooer'); ?>
						</div>
						<div class="card-body">
							  <form id="frm_email_report_setting" name="frm_email_report_setting" method="post" >
								<div class="form-group row">
								<div class="col-sm-6">
									<label for="enable_cron_job"><?php _e('Enable cron job', 'niwooer'); ?></label>
								</div>
								<div class="col-sm-6">
									<input type="checkbox" id="enable_cron_job" name="enable_cron_job" <?php echo ($enable_cron_job ==1)?'checked':''; ?>>
                                    <span> Yes, enable cron job</span>
								</div>
								
								
							</div>
                            	<div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="ni_email_report_from_email"><?php _e('From Email Address', 'niwooer'); ?></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="ni_email_report_from_email" id="ni_email_report_from_email" placeholder="Enter from email address" value="<?php echo $from_email; ?>"> 
                                    </div>
								</div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="ni_email_report_to_email"><?php _e('To Email Address', 'niwooer'); ?></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="ni_email_report_to_email" id="ni_email_report_to_email" placeholder="Enter from to address" value="<?php echo $to_email; ?>"> 
                                    </div>
								</div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="ni_email_report_subject_line"><?php _e('Subject Line', 'niwooer'); ?></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="ni_email_report_subject_line" id="ni_email_report_subject_line" placeholder="Enter Subject Line" value="<?php echo $subject_line; ?>"> 
                                    </div>
								</div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="ni_schedule_email"><?php _e('Interval', 'niwooer'); ?></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="form-control" id="ni_schedule_email" name="ni_schedule_email">
                                        	<?php foreach($intervals as $key=>$value): ?>
                                            	<option value="<?php  echo esc_attr($key); ?>"  <?php echo  ($schedule_email ==$key)?'selected':''; ?>  > <?php  echo esc_attr( $value); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
								</div>
                                
                                
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="cron_job_url"><?php _e('Cron Job URL', 'niwooer'); ?></label>
                                    </div>
                                    <div class="col-sm-6">
                                     <?php 
									 $cron_url = site_url()."/wp-cron.php?niwoocr_email_report";?>
                                   	<a  class="_niwoocr_cron_url2" href="<?php echo $cron_url; ?>" target="_blank"><?php echo $cron_url; ?></a>
                                     <br>
                                      <br>
                                     
                                     <!--<a class="_niwoocr_copy_url" href="#" ><i class="fa fa-clipboard fa-2x" aria-hidden="true" title="So nice to see you!"></i></a>
                                      <span class="is_copy">Click to copy</span>
                                   -->
                                    </div>
								</div>   
                                  
                            
								
								<div class="form-group row">
								<div class="col-sm-6 text-left">
                                	<div class="ajax_content  color-rgba-brown-strong" style="display:none"> <i class="fa fa-floppy-o fa-2x" aria-hidden="true"><span> Setting Saved..</span></i></div>	
                                	<div class="_please-wait color-rgba-brown-strong" style="display:none"> <i class="fa fa-hourglass fa-2x" aria-hidden="true"> <span class="_please-wait-text" >Please wait..</span></i></div>
								</div>
                                <div class="col-sm-6 text-right">
									<input type="submit" class="btn btn-rgba-brown-strong" value="<?php _e('Save Setting', 'niwooer'); ?>">
								</div>
								
								
							</div>
								
								<input type="hidden" name="action" value="ni_email_report_action" />
                    			<input type="hidden" name="email_report_action" value="niwooer_save_setting" />
                 				<input type="hidden" name="page" id="page" value="<?php echo isset($_REQUEST["page"])?$_REQUEST["page"]:''; ?>" />		
                                <input type="hidden" name="call" value="save_setting">
							</form>
					
						</div>
					</div>
                </div>
                <div class="col-md-4"  style="padding:0px;">
                	<div class="card" style="max-width:80% ">
                      <div class="card-header niwooer-plugin-color">
							<?php _e('Send Email Report Now', 'niwooer'); ?>
						</div>
                      <div class="card-body">
                      	<form method="post" id="frm_send_test_email">
                        	<div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="ni_report_period"><?php _e('Report Period', 'niwooer'); ?></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="form-control" id="ni_report_period" name="ni_report_period">
                                        	<option value="DAY"><?php esc_html_e('DAY', 'textdomain'); ?></option>
                                            <option value="WEEK"><?php esc_html_e('WEEK', 'textdomain'); ?></option>
                                            <option value="MONTH"><?php esc_html_e('MONTH', 'textdomain'); ?></option>
                                            <option value="YEAR"><?php esc_html_e('YEAR', 'textdomain'); ?></option>
                                        </select>
                                    </div>
								</div>
                                <div class="form-group row">
								<div class="col-sm-6 text-left">
                                	<div class="ajax_content  color-rgba-brown-strong" style="display:none"> <i class="fa fa-envelope-o fa-2x" aria-hidden="true"><span class="_please-wait-text" > Email Sent</span></i></div>	
                                	<div class="_please-wait color-rgba-brown-strong" style="display:none"> <i class="fa fa-hourglass fa-2x" aria-hidden="true"> <span class="_please-wait-text" >Please wait..</span></i></div>
								</div>
                                <div class="col-sm-6 text-right">
									<input type="submit" class="btn btn-primary" value="<?php esc_html_e('Send Report','niwooer'); ?>">
								</div>
								
								
							</div>
                                <input type="hidden" name="action" value="ni_email_report_action" />
                    			<input type="hidden" name="email_report_action" value="niwooer_save_setting" />
                 		        <input type="hidden" name="call" value="send_test_email">
                        	
                        </form>

                      </div>
                    </div>
                </div>
			</div>
		 </div>
         <?php	
		}
		function ajax_init(){
			
			$call = isset($_REQUEST['call'])?$_REQUEST['call']:'';
			
			if ($call =='save_setting'){
				$this->save_setting();
			}
			if ($call =='send_test_email'){
				$this->send_test_email();
			}
			
		}
		function send_test_email(){
		//	echo json_encode($_REQUEST['ni_report_period']);
			
			$ni_report_period = sanitize_text_field (isset($_REQUEST['ni_report_period'])?$_REQUEST['ni_report_period']:'DAY');
			
				//$output =" every_fifteen_minute ";
			$this->options = get_option( 'ni_email_report_option');
			$ni_to_email 	=  isset($this->options['ni_email_report_to_email']) ? $this->options['ni_email_report_to_email'] : '';
			$ni_from_email 	=  $this->options['ni_email_report_from_email'];
			$ni_subject_line =  $this->options['ni_email_report_subject_line'];
			
			$ni_subject_line  = $ni_subject_line ." | ". date_i18n("D M d, Y G:i"); 
			
			ob_start();	
			$output = ob_get_contents();
			$this->get_daily_email_report($ni_report_period,date_i18n("Y-m-d"),date_i18n("Y-m-d"));
			$output = ob_get_contents();
			ob_end_clean();
			
			$ni_to_email  = isset($this->options['ni_email_report_to_email']) ? $this->options['ni_email_report_to_email'] : '';
			$ni_to_email  = explode(",",$ni_to_email);
			
			$to 		= $ni_to_email;
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
				echo " Report sent";
			}else{
				echo " Report not sent";
			}
		
			
			
			die;
		}
		
		function get_comma_separated($to_email){
			if(!empty($to_email)){
				$to_email = str_replace(array(" ", "|",";"),",",$to_email);
				$to_emails = explode(",",$to_email);
				$e = array();
				foreach($to_emails as $email){
					$email = trim($email);
					if(is_email($email)){
						$e[] = sanitize_email(trim($email));
					}
				}
				$e = array_unique($e);				
				$to_email = implode(",",$e);
			}
			return $to_email;
		}
		
		function save_setting(){
			$ni_email_report_option = array();
			$from_email = 		sanitize_email (isset($_REQUEST["ni_email_report_from_email"])?$_REQUEST["ni_email_report_from_email"]:'');
			$to_email = 		isset($_REQUEST["ni_email_report_to_email"])?$_REQUEST["ni_email_report_to_email"]:'';
			$subject_line = 	sanitize_text_field (isset($_REQUEST["ni_email_report_subject_line"])?$_REQUEST["ni_email_report_subject_line"]:'');
			$schedule_email = 	sanitize_text_field (isset($_REQUEST["ni_schedule_email"])?$_REQUEST["ni_schedule_email"]:'');
			
			//$enable_cron_job = 0;
			if (isset($_REQUEST['enable_cron_job'])){
				$ni_email_report_option ['enable_cron_job'] = 1;
			}
			
			$to_email = $this->get_comma_separated($to_email);
			
			$ni_email_report_option ['ni_email_report_from_email'] = $from_email ;
			$ni_email_report_option ['ni_email_report_to_email'] = $to_email ;
			$ni_email_report_option ['ni_email_report_subject_line'] = $subject_line ;
			$ni_email_report_option ['ni_schedule_email'] = $schedule_email ;
			update_option('ni_email_report_option',$ni_email_report_option );
			echo esc_html('Setting Saved','niwooer');
			die;
		}
	}
}