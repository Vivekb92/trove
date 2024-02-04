<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'ni_sales_report_email_function' ) ) :
	class ni_sales_report_email_function{
		function __construct(){
		}
		function get_request($request, $default = NULL)
		{
			//$v = $_REQUEST[$request];
			$v = isset($_REQUEST[$request]) ? $_REQUEST[$request] : $default;
			$r = isset($v) ? $v : $default;
		 return $r;
		}
		function get_total_sales($period="CUSTOM",$start_date=NULL,$end_date=NULL){
				global $wpdb;	
				$query = "SELECT
						SUM(order_total.meta_value)as 'total_sales'
						FROM {$wpdb->prefix}posts as posts			
						LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID 
						
						WHERE 1=1
						AND posts.post_type ='shop_order' 
						AND order_total.meta_key='_order_total' ";
						
				$query .= " AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed')
							
							";
				if ($period =="DAY"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) "; 
				}
				if ($period =="WEEK"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 WEEK) "; 
				}
				if ($period =="MONTH"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 MONTH) "; 
				}
				if ($period =="YEAR"){		
					$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
				}
				if ($period =="CUSTOM"){
					$query .=" AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
				}
				
				
				//echo $query;		
						
				//$query .=" AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
						
				$results = $wpdb->get_var($query);
				$results = isset($results) ? $results : "0";
				return $results;
			}
			function get_total_sales_count($period="CUSTOM",$start_date=NULL,$end_date=NULL){
				global $wpdb;	
				$query = "SELECT
						count(order_total.meta_value)as 'sales_count'
						FROM {$wpdb->prefix}posts as posts			
						LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID 
						
						WHERE  1 = 1
						AND posts.post_type ='shop_order' 
						AND order_total.meta_key='_order_total' ";
						//if ($start_date!=NULL && $end_date!=NULL)
						//$query .=" AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
				$query .= " AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed') ";
				
				if ($period =="DAY"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) "; 
				}
				if ($period =="WEEK"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 WEEK) "; 
				}
				if ($period =="MONTH"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 MONTH) "; 
				}
				if ($period =="YEAR"){		
					$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
				}
				if ($period =="CUSTOM"){
					$query .=" AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
				}
				//echo $query;
				$results = $wpdb->get_var($query);	
				$results = isset($results) ? $results : "0";	
				return $results;
			}
			function get_recent_order_list(){
				global $wpdb;
				$query = "SELECT
					posts.ID as order_id
					,posts.post_status as order_status
					
					, date_format( posts.post_date, '%Y-%m-%d') as order_date 
					
					FROM {$wpdb->prefix}posts as posts			
					
					WHERE 
							posts.post_type ='shop_order' 
							
							AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed' ,'wc-cancelled' ,  'wc-refunded' ,'wc-failed')
							
							";
				$query .= " order by posts.post_date DESC";	
				$query .= " LIMIT 10 ";
				$order_data = $wpdb->get_results( $query);	
				if(count($order_data)> 0){
					foreach($order_data as $k => $v){
						
						/*Order Data*/
						$order_id =$v->order_id;
						$order_detail = $this->get_order_detail($order_id);
						foreach($order_detail as $dkey => $dvalue)
						{
								$order_data[$k]->$dkey =$dvalue;
							
						}
						
					}
				}
				else
				{
					echo "No Record Found";
				}
				return $order_data;
			}
			function get_order_status($period="CUSTOM",$start_date=NULL,$end_date=NULL){
				global $wpdb;
				$query = "
					SELECT 
					posts.ID as order_id
					,posts.post_status as order_status
					,date_format( posts.post_date, '%Y-%m-%d') as order_date 
					,SUM(postmeta.meta_value) as 'order_total'
					,count(posts.post_status) as order_count
					FROM {$wpdb->prefix}posts as posts	";		
					
				$query .=
					"	LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id=posts.ID ";
				
				$query .= " WHERE 1=1 ";
				
				//if ($start_date && $end_date)	
				
				//$query .= " AND date_format( posts.post_date, '%Y-%m-%d') BETWEEN  '{$start_date}' AND '{$end_date}'";	
				
				if ($period =="DAY"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) "; 
				}
				if ($period =="WEEK"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 WEEK) "; 
				}
				if ($period =="MONTH"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 MONTH) "; 
				}
				if ($period =="YEAR"){		
					$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
				}
				if ($period =="CUSTOM"){
					$query .=" AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
				}
				
				
				$query .= " AND postmeta.meta_key ='_order_total' ";
				$query .= " AND posts.post_type ='shop_order' ";
				
				$query .= " GROUP BY posts.post_status ";
				
				
				$results = $wpdb->get_results( $query);	
				return $results;
			}
			function get_payment_gateway2($start_date = NULL, $end_date= NULL){
				global $wpdb;	
				$query = "
					SELECT 
					payment_method_title.meta_value as 'payment_method_title'
					
					,SUM(order_total.meta_value) as 'order_total'
					,count(*) as order_count
					FROM {$wpdb->prefix}posts as posts	";		
					
			
					
				$query .= "	LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID ";
				
				$query .= "	LEFT JOIN  {$wpdb->prefix}postmeta as payment_method_title ON payment_method_title.post_id=posts.ID ";
				
				
				$query .=	"WHERE 1=1 ";
				$query .= " AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed') ";
				$query .= " AND posts.post_type ='shop_order' ";
				$query .= " AND order_total.meta_key ='_order_total' ";
				$query .= " AND payment_method_title.meta_key ='_payment_method_title' ";
				$query .= " GROUP BY payment_method_title.meta_value";
				
				$data = $wpdb->get_results($query);	
				
				return $data;	
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
			function get_total_product($period="CUSTOM",$start_date=NULL,$end_date=NULL){
				global $wpdb;
				
				$query = " 	SELECT COUNT(*) as count FROM  {$wpdb->prefix}posts as posts ";
				
				$query .= "	LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as order_items ON order_items.order_id=posts.ID ";
				
				$query .= " WHERE 1=1 ";
				$query .= " AND posts.post_type='shop_order'";
				$query .= " AND order_items.order_item_type='line_item'";
				$query .= " AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed') ";
				
				if ($period =="DAY"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) "; 
				}
				if ($period =="WEEK"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 WEEK) "; 
				}
				if ($period =="MONTH"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 MONTH) "; 
				}
				if ($period =="YEAR"){		
					$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
				}
				if ($period =="CUSTOM"){
					$query .=" AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
				}
				$results = $wpdb->get_var($query);	
					
				//echo $count =  count( $results); 
				//$this->print_array($results);
				//echo '<pre>',print_r($results,1),'</pre>';
				return $results;	
		}
		 function get_order_refund($period="CUSTOM",$start_date=NULL,$end_date=NULL){
		 	global $wpdb;
		
			
			$query = '';
			$query .= "SELECT  ROUND(SUM( order_total.meta_value ),2) as meta_value FROM {$wpdb->prefix}posts as posts";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID"; 
			
			$query .= " WHERE 1 = 1";
			$query .= " AND posts.post_type ='shop_order_refund'";
			
			$query .= " AND order_total.meta_key  IN  ('_order_total')";
			
				$query .= " AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed') ";
				$query .= " AND posts.post_status NOT IN ('auto-draft','inherit')";
				
			if ($period =="DAY"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') = DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) "; 
				}
				if ($period =="WEEK"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 WEEK) "; 
				}
				if ($period =="MONTH"){		
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 MONTH) "; 
				}
				if ($period =="YEAR"){		
					$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
				}
				if ($period =="CUSTOM"){
					$query .=" AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
				}
				
				
			//$query .= " GROUP BY  posts.post_status   ";
			
			error_log($query);
		
			$rows = $wpdb->get_var($query);	
			//$this->prettyPrint($rows);
			return $rows;
		 }
		function get_total_coupon($period="CUSTOM",$start_date=NULL,$end_date=NULL){
			global $wpdb;
			
			$query = " 	SELECT SUM(discount_amount.meta_value) as coupon_total    FROM  {$wpdb->prefix}posts as posts ";	
			
			$query .= "	LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as order_items ON order_items.order_id=posts.ID ";
			
			$query .= "	LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as discount_amount ON discount_amount.order_item_id=order_items.order_item_id ";
			$query .= "	LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as discount_amount_tax ON discount_amount_tax.order_item_id=order_items.order_item_id ";
			
			$query .= " WHERE 1=1 ";
			$query .= " AND posts.post_type='shop_order'";
			$query .= " AND order_items.order_item_type='coupon'";
			
			$query .= " AND discount_amount.meta_key='discount_amount'";
			$query .= " AND discount_amount_tax.meta_key='discount_amount_tax'";
			
			//if ($start_date && $end_date)
				//$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}' ";
			
			
			$query .= " AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed') ";
				
			if ($period =="DAY"){		
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) "; 
			}
			if ($period =="WEEK"){		
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 WEEK) "; 
			}
			if ($period =="MONTH"){		
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 MONTH) "; 
			}
			if ($period =="YEAR"){		
				$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
			}
			if ($period =="CUSTOM"){
				$query .=" AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
			}
			
			$results = $wpdb->get_var( $query);	
				
			
			
			return $results ;
			//echo $count =  count( $results); 
			//$this->print_array($results);
			
		}
		function get_sales_product($period="CUSTOM",$start_date=NULL,$end_date=NULL){
			global $wpdb;
			$row = array();
			$today = date_i18n("Y-m-d") ;
			$query = "SELECT ";
			
			$query .= "	woocommerce_order_items.order_item_name as order_item_name ";
			$query .= ",	SUM(qty.meta_value) as qty ";
			$query .= ", date_format( posts.post_date, '%Y-%m-%d') as order_date";
			$query .= ", variation_id.meta_value as variation_id  ";
			$query .= "	FROM  {$wpdb->prefix}posts as posts  ";
			
		$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as woocommerce_order_items ON woocommerce_order_items.order_id=posts.ID ";
		
		$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as qty ON qty.order_item_id=woocommerce_order_items.order_item_id ";
		
		$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as product_id ON product_id.order_item_id=woocommerce_order_items.order_item_id ";
		
		$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as variation_id ON variation_id.order_item_id=woocommerce_order_items.order_item_id ";
		
		
		
		
			
			
			$query .= " WHERE 1=1 ";
			$query .= " AND posts.post_type ='shop_order' ";
			$query .= " AND woocommerce_order_items.order_item_type ='line_item' "; 
			
			$query .= " AND qty.meta_key ='_qty' "; 
			$query .= " AND product_id.meta_key ='_product_id' "; 
			$query .= " AND variation_id.meta_key ='_variation_id' "; 
			
			$query .= "	AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed' ,'wc-cancelled' ,  'wc-refunded' ,'wc-failed')";
			
			if ($period =="DAY"){		
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) "; 
			}
			if ($period =="WEEK"){		
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 WEEK) "; 
			}
			if ($period =="MONTH"){		
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 MONTH) "; 
			}
			if ($period =="YEAR"){		
				$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
			}
			if ($period =="CUSTOM"){
				$query .=" AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
			}
			
			$query .= " GROUP BY product_id.meta_value, variation_id.meta_value   ";
			$row = $wpdb->get_results( $query);
			$row = $this->get_products_variation($row,'variation_id');
			//$this->print_data($row );
			return $row;	
		}
		function get_product_variations($order_id_string = array()){			
			global $wpdb;
			
			if(is_array($order_id_string)){
				$order_id_string = implode(",",$order_id_string);
			}
				
			$sql = "SELECT meta_key, REPLACE(REPLACE(meta_key, 'attribute_', ''),'pa_','') AS attributes, meta_value, post_id as variation_id
					FROM  {$wpdb->prefix}postmeta as postmeta WHERE 
					meta_key LIKE '%attribute_%'";
			
			if(strlen($order_id_string) > 0){
				$sql .= " AND post_id IN ({$order_id_string})";
				//$sql .= " AND post_id IN (23)";
			}
			
			$order_items 		= $wpdb->get_results($sql);
			
			$product_variation  = array(); 
			if(count($order_items)>0){
				foreach ( $order_items as $key => $order_item ) {
					$variation_label	=	ucfirst($order_item->meta_value);
					$variation_key		=	$order_item->attributes;
					$variation_id		=	$order_item->variation_id;
					$product_variation[$variation_id][$variation_key] =  $variation_label;
				}
			}
			return $product_variation;
		}
		function get_items_id_list($order_items = array(),$field_key = 'order_id', $return_default = '-1' , $return_formate = 'string'){
				$list 	= array();
				$string = $return_default;
				if(count($order_items) > 0){
					foreach ($order_items as $key => $order_item) {
						if(isset($order_item->$field_key)){
							if(!empty($order_item->$field_key))
								$list[] = $order_item->$field_key;
						}
					}
					
					$list = array_unique($list);
					
					if($return_formate == "string"){
						$string = implode(",",$list);
					}else{
						$string = $list;
					}
				}
				return $string;
			}//End Functio
			function get_products_variation($items = array(), $variation_field = 'variation_id'){
			
			$variation_ids			= $this->get_items_id_list($items,$variation_field);		
			$product_variations 	= $this->get_product_variations($variation_ids);
			
			if($product_variations != '-1' and count($product_variations) >0){
				foreach ($items as $key => $value){
					
					$variation_id = $items[$key]->{$variation_field};
					$items[$key]->product_variation  = '';
					if($variation_id > 0){
						$product_variation = isset($product_variations[$variation_id]) ? $product_variations[$variation_id] : array();
						
						$product_variation = implode(", ",$product_variation);
						
						//$items[$key]->product_name  = $value->product_name ." - ". $product_variation;
						$items[$key]->product_variation  = $product_variation;
					}
					
				}
			}
			
			return $items;
		}
		function get_order_product_columns(){
			$columns =  array();
			$columns["order_id"] = __("ID");
			$columns["order_date"] = __("Date");
			$columns["order_status"] = __("Status");
			$columns["billing_first_name"] = __("First Name");
			$columns["billing_email"] = __("Billing Email");
			$columns["order_item_name"] = __("Product Name");
			$columns["price"] = __("Price");
			$columns["qty"] = __("Quantity");	
			$columns["line_total"] = __("line Total");
			
			
			return  apply_filters('ni_email_report_order_product_columns', $columns );	
			
		}
		function get_order_product($period="CUSTOM",$start_date=NULL,$end_date=NULL){
			global $wpdb;
			$row = array();
			$today = date_i18n("Y-m-d") ;
			
			$date_heading = '';
			
			if ($period =='CUSTOM'){
				$date_heading  =  $start_date  ." To ". $end_date	;
			}else{
				$date_heading = $period;
			}
			
			$columns = $this->get_order_product_columns();
			
			$query = "SELECT ";
			
			$query .= "	woocommerce_order_items.order_item_name as order_item_name ";
			$query .= ", line_total.meta_value as line_total ";
			$query .= ", billing_first_name.meta_value as billing_first_name ";
			$query .= ", billing_last_name.meta_value as billing_last_name ";
			
			$query .= ", billing_email.meta_value as billing_email ";
			
			$query .= ", qty.meta_value as qty ";
			$query .= ", posts.ID as order_id ";
			
			
			
			$query .= ", posts.post_status as order_status ";
			$query .= ", date_format( posts.post_date, '%Y-%m-%d') as order_date";
			
			$query .= "	FROM  {$wpdb->prefix}posts as posts  ";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as woocommerce_order_items ON woocommerce_order_items.order_id=posts.ID ";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as qty ON qty.order_item_id=woocommerce_order_items.order_item_id ";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as  line_total ON line_total.order_item_id=woocommerce_order_items.order_item_id ";
			
			$query .= "  LEFT JOIN  {$wpdb->prefix}postmeta as billing_first_name ON billing_first_name.post_id=posts.ID ";
			
			$query .= "  LEFT JOIN  {$wpdb->prefix}postmeta as billing_last_name ON billing_last_name.post_id=posts.ID ";
			
			$query .= " LEFT JOIN {$wpdb->prefix}postmeta as billing_email ON billing_email.post_id = posts.ID ";
			
	
			$query .= " WHERE 1=1 ";
			$query .= " AND posts.post_type ='shop_order' ";
			$query .= " AND woocommerce_order_items.order_item_type ='line_item' "; 
			
			$query .= " AND qty.meta_key ='_qty' "; 
			
			
			$query .= " AND line_total.meta_key ='_line_total' "; 
			
			$query .= " AND	billing_first_name.meta_key ='_billing_first_name' ";
			$query .= " AND billing_email.meta_key = '_billing_email'";	 
			$query .= " AND	billing_last_name.meta_key ='_billing_last_name' ";
			
			
			$query .= "	AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed' ,'wc-cancelled' ,  'wc-refunded' ,'wc-failed')";
			
			if ($period =="DAY"){		
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) "; 
			}
			if ($period =="WEEK"){		
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 WEEK) "; 
			}
			if ($period =="MONTH"){		
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 MONTH) "; 
			}
			if ($period =="YEAR"){		
				$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
			}
			if ($period =="CUSTOM"){
				$query .=" AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
			}
			
		
			$row = $wpdb->get_results( $query);
			
			//$this->print_data($row );
			?>
            <table style="width:100%;  border-collapse: collapse;">
            	<thead>
                	<tr>
						<td colspan="<?php echo count($columns); ?>" style="background-color:rgba(121, 85, 72, 0.7);height:30px;  font-weight:bold; font-size:16px; color:#FFF">Order Product Report  (<?php echo $date_heading ?>)</td>
					</tr>
                	<tr>
                    	<?php foreach($columns  as $key=>$value): ?>
                        	<th style="font-weight:bold; text-align:left;border: 1px solid rgba(121, 85, 72, 0.7);"><?php echo $value; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                	<?php foreach($row  as $row_key=>$row_value): ?>
                    	<tr>
                        	<?php foreach($columns  as $col_key=>$col_value): ?>
                            	<?php switch($col_key): case 1: break; ?>
                                	<?php case "price": ?>
                                    <?php $td_vale = $row_value->line_total/$row_value->qty;   ?>
                                    <?php break; ?>
                                    
                                    <?php case "order_status": ?>
                                    <?php $td_vale =  ucfirst ( str_replace("wc-","", $row_value->order_status));   ?>
                                    <?php break; ?>
                                    
                                    <?php default; ?>
                                     <?php $td_vale = isset($row_value->$col_key)?$row_value->$col_key:""; ?>
                                <?php endswitch; ?>
                           	 <td  style="font-weight:bold; text-align:left;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;"><?php echo $td_vale ;  ?></td>
                            <?php endforeach; ?>
                           
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php
			
			return $row;	
		
			
			
		}
		function get_daily_email_report($period="CUSTOM",$start_date=NULL,$end_date=NULL){
			$date_heading = '';
			
			if ($period =='CUSTOM'){
				$date_heading  =  $start_date  ." To ". $end_date	;
			}else{
				$date_heading = $period;
			}
		?>
		<table style="width:90%;  border-collapse: collapse;">
					<tr>
						<td colspan="2" style="background-color:rgba(121, 85, 72, 0.7);height:30px;  font-weight:bold; font-size:16px; color:#FFF">Sale summary for the period of <?php echo $date_heading ?></td>
					</tr>
					<tr>
						<td style="font-weight:bold; text-align:left; width:50%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;">Orders placed</td>
						<td style=" font-weight:bold; text-align:right; width:50%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;"><?php echo $this->get_total_sales_count($period);  ?></td>
					</tr>
					<tr>
						<td style="font-weight:bold; text-align:left; width:50%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;">Order Total</td>
						<td style=" font-weight:bold; text-align:right; width:50%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;"><?php  echo wc_price( $this->get_total_sales($period)); ?></td>
					</tr>
					<tr>
						<td style="font-weight:bold; text-align:left; width:50%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;">Discounts in total</td>
						<td style=" font-weight:bold; text-align:right; width:50%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;"><?php echo wc_price($this->get_total_coupon($period));  ?></td>
					</tr>
					<tr>
						<td style="font-weight:bold; text-align:left; width:50%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;">Total Product Sold</td>
						<td style=" font-weight:bold; text-align:right; width:50%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;"><?php echo $this->get_total_product($period);  ?></td>
					</tr>
                    
					<tr>
						<td colspan="2" style="height:50px;"></td>
					</tr>
                    <tr>
                    	<td colspan="2" style="width:100%">
                        	<table style="width:100%;  border-collapse: collapse;">
                            	<tr>
						<td colspan="2" style="background-color:rgba(121, 85, 72, 0.7);height:30px;  font-weight:bold; font-size:16px; color:#FFF">Total Product Sold    (<?php echo $date_heading ?>)</td>
					</tr>
                        		<tr>
                                	<th  style="font-weight:bold; text-align:left; width:33%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;">Product Name</th>							  						    
                                    <th  style="font-weight:bold; text-align:right; width:33%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;">Quantity</th>
                                </tr>
                                <?php $row =  $this->get_sales_product($period,$start_date,$end_date); ?>
                                <?php if (count($row)>0 ) { ?>
                                <?php foreach($row as $k=>$v){ ?>
                                <tr>
                                	<td style="font-weight:bold; text-align:left; width:33%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;"><?php  echo $v->order_item_name; ?></td>
                                   
                                    <td style="font-weight:bold; text-align:left; width:33%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;text-align:right"><?php  echo $v->qty; ?></td>
                                </tr>
                                <?php } ?>
                                <?php }else {?>
                                <tr>
                                	<td style="font-weight:bold; text-align:left; width:33%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;" colspan="3"> No Product Found</td>
                                </tr>	
                                <?php }?>
                            </table>
                        </td>
                    </tr>
                    <tr>
						<td colspan="2" style="height:50px;"></td>
					</tr>
					<tr>
						<td colspan="2" style="width:100%">
						   <table style="width:100%;  border-collapse: collapse;">
								<tr>
						<td colspan="3" style="background-color:rgba(121, 85, 72, 0.7);height:30px;  font-weight:bold; font-size:16px; color:#FFF">Order Status (<?php echo $date_heading ?>)</td>
					</tr>
								<tr>
									<th style="font-weight:bold; text-align:left; width:33%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;">Order Status</th>
									<th style="font-weight:bold; text-align:left; width:33%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;">Order Count</th>
									<th style="font-weight:bold; text-align:right;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;">Order Total</th>
								</tr>
								<?php $order_status = $this->get_order_status($period,$start_date,$end_date);?>
								<?php foreach($order_status as $key=>$value){ ?>
								<tr>
									<td style="font-weight:bold; text-align:left; width:33%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;"><?php echo  ucfirst ( str_replace("wc-","", $value->order_status)); ?></td>
									<td style="font-weight:bold; text-align:left; width:33%;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px;text-align:right"><?php echo $value->order_count; ?></td>
									<td style="font-weight:bold; text-align:left;border: 1px solid rgba(121, 85, 72, 0.7); padding:5px; text-align:right"><?php echo wc_price($value->order_total); ?></td>
								</tr>
								<?php }?>
							</table>	
						</td>
					</tr>
                     <tr>
						<td colspan="2" style="height:50px;"></td>
					</tr>
                    <tr>
                    	<td colspan="2" style="width:100%"><?php $this->get_order_product($period,$start_date,$end_date); ?></td>
                    </tr>
                    <tr>
						<td colspan="2" style="height:50px;"></td>
					</tr>
				</table>
		<?php 
		}
		function print_data($r){
			echo '<pre>',print_r($r,1),'</pre>';	
		}
		function get_recent_order_columns(){
			$columns["order_id"] =__("Order ID");
			$columns["order_date"] =__("Order Date");
			$columns["billing_first_name"] =__("First Name");
			$columns["billing_email"] =__("Email");
			$columns["billing_country"] =__("Country");
			$columns["order_status"] =__("Order Status");
			$columns["order_currency"] =__("Currency");
			$columns["order_total"] =__("Order Total");
			
			return  apply_filters('ni_email_report_recent_order_columns', $columns );
		}
		function get_order_list_columns(){
			$columns["order_id"] =__("Order ID");
			$columns["order_date"] =__("Order Date");
			$columns["billing_first_name"] =__("First Name");
			$columns["billing_email"] =__("Email");
			$columns["billing_country"] =__("Country");
			$columns["order_status"] =__("Order Status");
			$columns["order_currency"] =__("Currency");
			$columns["cart_discount"] =__("Cart Discount");
			$columns["cart_discount_tax"] =__("Cart Discount Tax");
			$columns["order_shipping"] =__("Order Shipping");
			$columns["order_shipping_tax"] =__("order Shipping Tax");
			
			$columns["order_tax"] =__("Order Tax");
			
			$columns["order_total"] =__("Order Total");
			
			return  apply_filters('ni_email_report_order_list_columns', $columns );
		}
		function get_order_list_price_columns(){
			$columns = array();
			$columns["order_total"] =__("Order Total");
			return $columns;
			
		}
		function get_recent_order(){
			$columns = 	$this->get_recent_order_columns();
			$row = $this->get_recent_order_list();  		
			?>
            <table class="ni-email-data-table">
            	<tr>
               	 <?php foreach($columns as $key=>$value): ?>
               		<th><?php echo $value; ?></th>
              	 <?php endforeach; ?>
            	</tr>
                <?php foreach($row as $key=>$value): ?>
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
            </table>
            <?php
		}		
		
	}
endif;	
?>