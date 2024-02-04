<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'ni_email_order_list' ) ) :
include_once("ni-function.php");
class ni_email_order_list extends ni_sales_report_email_function{
	function __construct(){
		}	
	
	function page_init(){
	?>
    <div class="container-fluid" id="niwooer">
		 <div class="row">
				
				<div class="col-md-12"  style="padding:0px;">
					<div class="card" style="max-width:70% ">
						<div class="card-header niwooer-plugin-color">
							<?php _e('ORDER REPORT', 'niwooer'); ?>
						</div>
						<div class="card-body">
							  <form id="frm_email_report" name="frm_email_report" method="post" >
								<div class="form-group row">
								<div class="col-sm-2">
									<label for="select_order"><?php _e('Select order period', 'niwooer'); ?></label>
								</div>
								<div class="col-sm-4">
									<select name="select_order"  id="select_order" class="form-control">
										  <option value="today"><?php _e('Today', 'niwooer'); ?></option>
										  <option value="yesterday"><?php _e('Yesterday', 'niwooer'); ?></option>
										  <option value="last_7_days"><?php _e('Last 7 days', 'niwooer'); ?></option>
										  <option value="last_10_days"><?php _e('Last 10 days', 'niwooer'); ?></option>
										  <option value="last_30_days"><?php _e('Last 30 days', 'niwooer'); ?></option>
										  <option value="last_60_days"><?php _e('Last 60 days', 'niwooer'); ?></option>
										  <option value="this_year"><?php _e('This year', 'niwooer'); ?></option>
									</select>
								</div>
								<div class="col-sm-2">
									<label for="order_no"><?php _e('Order No.', 'niwooer'); ?></label>
								</div>
								<div class="col-sm-4">
									 <input id="order_no" name="order_no" type="text"  class="form-control" >
								</div>
								
							</div>
								<div class="form-group row">
								<div class="col-sm-2">
									<label for="billing_first_name"><?php _e('Billing First Name', 'niwooer'); ?></label>
								</div>
								<div class="col-sm-4">
									<input id="billing_first_name" name="billing_first_name" type="text" class="form-control" >
								</div>
								<div class="col-sm-2">
									<label for="billing_email"><?php _e('Billing Email', 'niwooer'); ?></label>
								</div>
								<div class="col-sm-4">
									 <input id="billing_email" name="billing_email" type="text" class="form-control">
								</div>
								
							</div>
								<div class="form-group row">
								<div class="col-sm-6 text-left">
                                	<div class="_please-wait color-rgba-brown-strong" style="display:none"> <i class="fa fa-hourglass fa-2x" aria-hidden="true"> <span class="_please-wait-text" >Please wait..</span></i></div>
								</div>
                                <div class="col-sm-6 text-right">
									<input type="submit" class="btn btn-rgba-brown-strong" value="Search">
								</div>
								
								
							</div>
								
								<input type="hidden" name="action" value="ni_email_report_action" />
                    			<input type="hidden" name="email_report_action" value="ni_email_report_list" />
                 				<input type="hidden" name="page" id="page" value="<?php echo isset($_REQUEST["page"])?$_REQUEST["page"]:''; ?>" />		
							</form>
					
						</div>
					</div>
				</div>
				

			</div>
		 <div class="row" >
            	<div class="col-md-12"  style="padding:0px;">
         			<div class="card">
                      
                      <div class="card-body "> 
                        <div class="row">
                        	<div class="table-responsive niwoosr-table">
								<div class="ajax_content"></div>
                            </div>
                           
                        </div>
						</div>
                      
                    </div>       	
                </div>
            </div> 
			 
	 </div>
    	
    <?php
	}
	function get_email_report_list() {
		$this->get_order_grid();	
	}
	function get_order_data(){
		$order_query = $this->query("DEFAULT");
		
		if(count($order_query)> 0){
			foreach($order_query as $k => $v){
				/*Order Data*/
				$order_id =$v->order_id;
				$order_detail = $this->get_order_detail($order_id);
				foreach($order_detail as $dkey => $dvalue){
						$order_query[$k]->$dkey =$dvalue;
				}
			}
		}
		else{
			echo "No Record Found";
		}
		return $order_query;
	}
	function get_order_grid($r="DEFAULT"){
		$order_total = 0;
		$rows 			= $this->get_order_data();
		$columns 		= $this->get_order_list_columns();
		$price_columns  = $this->get_order_list_price_columns();
		
		//$this->print_data ($order_data);
		
		if(count($rows)> 0)
		{
			?>
            <?php if ($r=="DEFAULT"): ?>
			<div style="text-align:right;margin-bottom:10px">
			<form id="ni_frm_order_export" action="" method="post">
				<!--<input type="submit" value="Excel" name="btn_excel_export" id="btn_excel_export" />
				<input type="submit" value="Print" name="btn_print" id="btn_print" />-->
                <input type="submit" value="Email" class="btn btn-rgba-brown-strong" name="btn_email" id="btn_email" />
				<input type="hidden" name="select_order" value="<?php echo $_REQUEST["select_order"];  ?>" />
			</form>
			</div>
             <?php endif; ?>
			<div>
            <div class="table-responsive niwooer-table">
            	<table class="table table-striped table-hover">
            	 <thead class="shadow-sm p-3 mb-5 bg-white rounded">
              		<tr>
					 <?php foreach($columns as $key=>$value): ?>
                        <th><?php echo $value; ?></th>
                     <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                 <?php foreach($rows as $key=>$value): ?>
                	<?php $order_total += isset($value->order_total)?$value->order_total:0; ?>
                	<tr>
                    	<?php foreach($columns as $k=>$v): ?>
                        	<?php switch($k) : case "a": break;?>
                             <?php case "order_total": ?>
                             	<td style="text-align:right"><?php echo  wc_price($value->$k); ?></td>
                             <?php break; ?>
                              <?php case "order_status": ?>
                             	<td style="text-align:right"><?php echo ucfirst ( str_replace("wc-","", $value->$k)); ?></td>
                             <?php break; ?>
                             <?php default; ?> 
                             	 <td><?php echo $value->$k; ?></td>  		
                             <?php endswitch;?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
               
                <tfoot>
                	<tr>
                    	<td colspan="<?php echo count($columns); ?>" style="text-align:right">
                		<?php   echo wc_price($order_total); ?></b>
                		</td>
                    </tr>
                </tfoot>
            </table>
            </div>
            
            </div>
			<?php
			//$this->print_data(	$order_data );
		}
	}
	function get_country_name($code){
			$new_name = "";
			if ($code){		
				$new_name= WC()->countries->countries[ $code];	
				$new_name  = isset($new_name) ? $new_name : $code;
			}
			return $new_name;
	}
	function get_order_detail($order_id){
		$order_detail	= get_post_meta($order_id);
		$order_detail_array = array();
		foreach($order_detail as $k => $v)
		{
			$k =substr($k,1);
			$order_detail_array[$k] =$v[0];
		}
		return 	$order_detail_array;
	}
	function query($type="DEFAULT"){
		global $wpdb;	
		
		$today = date_i18n("Y-m-d");
		$order_no 			 = $this->get_request("order_no");
		$billing_first_name  = $this->get_request("billing_first_name",'',true);
		$billing_email		 = $this->get_request("billing_email",'',true);
		
		if (isset($_REQUEST["select_order"])) 
			$select_order = $_REQUEST["select_order"];
		else
			$select_order = "today";
		
		$query = "SELECT 
			posts.ID as order_id
			,post_status as order_status
			, date_format( posts.post_date, '%Y-%m-%d') as order_date 
			FROM {$wpdb->prefix}posts as posts ";
			if (strlen($billing_first_name)>0 && $billing_first_name!="" ){
					$query .= "  LEFT JOIN  {$wpdb->prefix}postmeta as billing_first_name ON billing_first_name.post_id=posts.ID ";
				}
				if (strlen($billing_email)>0 && $billing_email!="" ){
						$query .= " LEFT JOIN {$wpdb->prefix}postmeta as billing_email ON billing_email.post_id = posts.ID ";
				}
						
			$query .= " WHERE 
					posts.post_type ='shop_order' 
					";
			if (strlen($billing_first_name)>0 && $billing_first_name!="" ){
					$query .= " AND	billing_first_name.meta_key ='_billing_first_name' ";
					$query .= " AND billing_first_name.meta_value LIKE '%{$billing_first_name}%'";	
				}
				if (strlen($billing_email)>0 && $billing_email!="" ){
					$query .= " AND billing_email.meta_key = '_billing_email'";	 
					$query .= " AND billing_email.meta_value LIKE '%{$billing_email}%'";	
				}
				$query .= "		AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed' ,'wc-cancelled' ,  'wc-refunded' ,'wc-failed')";
						
				if ($order_no){
					$query .= " AND   posts.ID = '{$order_no}'";
				}				
			 switch ($select_order) {
				case "today":
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$today}' AND '{$today}'";
					break;
				case "yesterday":
					$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') = date_format( DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d')";
					break;
				case "last_7_days":
					$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 7 DAY), '%Y-%m-%d') AND   '{$today}' ";
					break;
				case "last_30_days":
						$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 30 DAY), '%Y-%m-%d') AND   '{$today}' ";
					break;	
				case "last_60_days":
						$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 60 DAY), '%Y-%m-%d') AND   '{$today}' ";
					break;		
				case "this_year":
					$query .= " AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(CURDATE(), '%Y-%m-%d'))";			
					break;		
				default:
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$today}' AND '{$today}'";
			}
		$query .= "order by posts.post_date DESC";	
		
	 if ($type=="ARRAY_A") /*Export*/
		$results = $wpdb->get_results( $query, ARRAY_A );
	 if($type=="DEFAULT") /*default*/
		$results = $wpdb->get_results( $query);	
	 if($type=="COUNT") /*Count only*/	
		$results = $wpdb->get_var($query);		
		//echo $query;
		//echo mysql_error();
	//	$this->print_data($results);
		//echo '<pre>',print_r($results,1),'</pre>';	
		return $results;	
	}
	function ni_send_email_report(){
		
		$this->options 		= get_option( 'ni_email_report_option');
		$ni_to_email 		=  $this->options['ni_email_report_to_email'];
		$ni_from_email 		=  $this->options['ni_email_report_from_email'];
		$ni_subject_line 	=  $this->options['ni_email_report_subject_line'];
		
		
		ob_start();	
		$output = ob_get_contents();
		$this->get_email_content();
		$output = ob_get_contents();
		ob_end_clean();
		
		$to 		= $ni_to_email ; // <– replace with your address here
		$subject 	= $ni_subject_line;
		$message 	= $output;
		$from 		= $ni_from_email ;
		//$headers = "MIME-Version: 1.0" . "\r\n";
		//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		//$headers .= "From:" . $from;
		//mail($to,$subject,$output,$headers);
		//$this->send_email($to,$subject,$message,$headers);
		//if (@mail($to,$subject,$message,$headers)){
			//echo "Message has been sent";
		//}else{
			//echo "Message was not sent";
		//}
		
		//echo "send ..";
		$headers =  array();
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		if ($from)
		$headers[] = 'From:  <' .$from. '>';
		$status = wp_mail($to, $subject, $message, $headers);
		 if ($status){
			echo "Message has been sent";
		 }else{
		  	echo "Message was not sent";
		 }
		die;
	}
	function get_email_content(){	
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Email Report</title>
		
        </head>
		<body>
        <?php 
		$order_total = 0;
		$rows = $this->get_order_data();
		$columns = $this->get_order_list_columns();
		if(count($rows)> 0){
		?>
        <table style="border: 1px solid #ccc; width: 100%; margin:0;  padding:0; border-collapse: collapse;border-spacing: 0;">
            	<thead>
              		<tr  style=" border: 1px solid #ddd; padding: 5px;">
					 <?php foreach($columns as $key=>$value): ?>
                        <th  style="  padding: 10px;text-align: center; text-transform: uppercase; font-size: 14px;letter-spacing: 1px;background-color: #00BCD4; color: white;"><?php echo $value; ?></th>
                     <?php endforeach; ?>
                    </tr>
                </thead>
                <?php foreach($rows as $key=>$value): ?>
                	<?php $order_total += isset($value->order_total)?$value->order_total:0; ?>
                	<tr  style=" border: 1px solid #ddd; padding: 5px;">
                    	<?php foreach($columns as $k=>$v): ?>
                        	<?php switch($k) : case "a": break;?>
                             <?php case "order_total": ?>
                             	<td style="text-align:right;padding: 10px; text-align: center;background-color: #FDFFE5;"><?php echo  wc_price($value->$k); ?></td>
                             <?php break; ?>
                              <?php case "order_status": ?>
                             	<td style="text-align:right;padding: 10px; text-align: center;background-color: #FDFFE5;"><?php echo ucfirst ( str_replace("wc-","", $value->$k)); ?></td>
                             <?php break; ?>
                             <?php default; ?> 
                             	 <td style="padding: 10px; text-align: center;background-color: #FDFFE5;"><?php echo $value->$k; ?></td>  		
                             <?php endswitch;?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                <tfoot>
                	<tfoot>
                	<tr>
                    	<td colspan="<?php echo count($columns); ?>" style="text-align:right">
                		<?php   echo wc_price($order_total); ?></b>
                		</td>
                    </tr>
                </tfoot>
                </tfoot>
            </table>
        <?php	
		}
		?>
        </body>
		</html>

	<?php
	}
	function get_email_content2(){	
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Email Report</title>
		
        </head>
		<body>
        <?php 
		$order_total = 0;
		$order_data = $this->get_order_data();
		if(count($order_data)> 0){
		?>
        <table style="border: 1px solid #ccc; width: 100%; margin:0;  padding:0; border-collapse: collapse;border-spacing: 0;">
            	<thead>
				<tr style=" border: 1px solid #ddd; padding: 5px;">
					<th style="  padding: 10px;text-align: center; text-transform: uppercase; font-size: 14px;letter-spacing: 1px;background-color: #00BCD4; color: white;">#ID</th>
					<th style="  padding: 10px;text-align: center; text-transform: uppercase; font-size: 14px;letter-spacing: 1px;background-color: #00BCD4; color: white;">Order Date</th>
					<th style="  padding: 10px;text-align: center; text-transform: uppercase; font-size: 14px;letter-spacing: 1px;background-color: #00BCD4; color: white;">Billing First Name</th> 
					<th style="  padding: 10px;text-align: center; text-transform: uppercase; font-size: 14px;letter-spacing: 1px;background-color: #00BCD4; color: white;">Billing Email</th> 
					<th style="  padding: 10px;text-align: center; text-transform: uppercase; font-size: 14px;letter-spacing: 1px;background-color: #00BCD4; color: white;">Billing Country</th> 
					<th style="  padding: 10px;text-align: center; text-transform: uppercase; font-size: 14px;letter-spacing: 1px;background-color: #00BCD4; color: white;">Status</th>
					<th style="  padding: 10px;text-align: center; text-transform: uppercase; font-size: 14px;letter-spacing: 1px;background-color: #00BCD4; color: white;">Order Total</th>
				</tr>
				</thead>
			<?php
			foreach($order_data as $k => $v){
				$order_total += isset($v->order_total)?$v->order_total:0;
			?>
				<tr style=" border: 1px solid #ddd; padding: 5px;">
					<td style="padding: 10px; text-align: center;background-color: #FDFFE5;"> <?php echo $v->order_id;?> </td>
					<td style="padding: 10px; text-align: center;background-color: #FDFFE5;"> <?php echo $v->order_date;?> </td>
					<td style="padding: 10px; text-align: center;background-color: #FDFFE5;"> <?php echo $v->billing_first_name;?> </td>
					<td style="padding: 10px; text-align: center;background-color: #FDFFE5;"> <?php echo $v->billing_email;?> </td>
					<td style="padding: 10px; text-align: center;background-color: #FDFFE5;"> <?php echo $this->get_country_name($v->billing_country);?> </td>
					<td style="padding: 10px; text-align: center;background-color: #FDFFE5;"> <?php echo ucfirst ( str_replace("wc-","", $v->order_status));?> </td>
					<td style="text-align:right;background-color: #FDFFE5;"> <?php echo wc_price($v->order_total);?> </td>
				</tr>	
			<?php }?>
            <tr>
            	<td colspan="8" style="text-align:right;height:30px; background-color:#E9FEFE">
                	<b>	<?php   echo wc_price($order_total); ?></b>
                </td>
            </tr>
			</table>
        <?php	
		}
		?>
        </body>
		</html>

	<?php
	}
}
endif;
?>