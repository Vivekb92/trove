<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'ni_email_dashboard' ) ) :
	include_once("ni-function.php");
	class ni_email_dashboard extends ni_sales_report_email_function{
		function test(){
			 $schedule 	=  $this->options['ni_schedule_email'];
			
		}
		function test2(){
			
		}
		function page_init(){
			//$this->test();
			$this->options = get_option( 'ni_email_report_option');
			//echo $schedule 	=  $this->options['ni_schedule_email']."dsad";
			//die;
		?>
		<div class="parent_content">
			<div class="content">
            	<div style="height:50px;">
				<div style="border-bottom:2px solid #e7eaec; padding-top:25px"></div>
			</div>
            	<div class="ni-pro-info">
            	<h3 style="text-align:center; font-size:20px; padding:0px; margin:10px; color:#78909C ">
            	Monitor your sales and grow your online business
                </h3>
				
				<h1 style="text-align:center; color:#2cc185">Buy Ni WooCommerce Sales Report Pro @ $24.00</h1>
				<div style="width:33%; float:left; padding:5px">
					<ul>
						<li>Dashboard order Summary</li>
						<li>Order List - Display order list</li>
						<li>Order Detail - Display Product information</li>
						<li>Customer Sales Report</li>
					</ul>
				</div>
				<div style="width:33%;padding:5px; float:left">
					<ul>
						<li>Payment Gateway Sales Report</li>
						<li>Country Sales Report</li>
						<li>Coupon Sales Report</li>
						<li>Order Status Sales Report</li>
					</ul>
				</div>
				<div>
					<ul>
						<li><span style="color:#26A69A">Email at: <a href="mailto:support@naziinfotech.com">support@naziinfotech.com</a></span></li>
						<li><a href="http://demo.naziinfotech.com/wp-admin/" target="_blank">View Demo</a>  </li>
						<li><a href="http://naziinfotech.com/?product=ni-woocommerce-sales-report-pro" target="_blank">Buy Now</a>  </li>
						<li>Coupon Code: <span style="color:#26A69A">ni10</span> Get 10% OFF</li>
						
					</ul>
				 </div>
				 
			   
				  <div style="clear:both"></div>
				  <div style="width:100%;padding:5px; float:left">
				  <b> For any WordPress or woocommerce customization, queries and support please email at : <strong><a href="mailto:support@naziinfotech.com">support@naziinfotech.com</a></strong></b>
				  </div>
				  <div style="clear:both"></div>
				  
			</div>
            	<div style="height:50px;">
				<div style="border-bottom:2px solid #e7eaec; padding-top:25px"></div>
			</div>
				<div class="box-title"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard - Sales Analysis</div>
				<div style="border-bottom:4px solid #2cc185;"></div>
				<div class="box-data">
					<div class="columns-box">
						<div class="columns-title">Total Sales</div>
						<div>
							<div class="columns-icon" style="color:#BA68C8"><i class="fa fa-cart-plus fa-4x"></i></div>
							<div class="columns-value" style="color:#BA68C8"><?php  echo wc_price( $this->get_total_sales("ALL")); ?></div>	
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">This Year Sales</div>
						<div>
							<div class="columns-icon"  style="color:#EF6C00"><i class="fa fa-cart-plus fa-4x"></i></div>
							<div class="columns-value"  style="color:#EF6C00"><?php  echo wc_price( $this->get_total_sales("YEAR")); ?></div>	
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">This Month Sales</div>
						<div>
							<div class="columns-icon"  style="color:#00897B"><i class="fa fa-cart-plus fa-4x"></i></div>
							<div class="columns-value" style="color:#00897B"><?php  echo wc_price( $this->get_total_sales("MONTH")); ?></div>	
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">This Week Sales</div>
						<div>
							<div class="columns-icon" style="color:#039BE5"><i class="fa fa-cart-plus fa-4x"></i></div>
							<div class="columns-value" style="color:#039BE5"><?php  echo wc_price( $this->get_total_sales("WEEK")); ?></div>	
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">Today Sales</div>
						<div>
							<div class="columns-icon"  style="color:#2cc185"><i class="fa fa-cart-plus fa-4x"></i></div>
							<div class="columns-value"  style="color:#2cc185"><?php  echo wc_price( $this->get_total_sales("DAY")); ?></div>	
						</div>
					</div>
					<div style="clear:both"></div>
				</div>
                <div class="box-data">
					<div class="columns-box">
						<div class="columns-title">Total Sales Count</div>
						<div>
							<div class="columns-icon" style="color:#BA68C8"><i class="fa fa-line-chart fa-3x"></i></div>
							<div class="columns-value" style="color:#BA68C8"><?php echo $this->get_total_sales_count("ALL"); ?></div>	
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">This Year Sales Count</div>
						<div>
							<div class="columns-icon" style="color:#EF6C00"><i class="fa fa-line-chart fa-3x"></i></div>
							<div class="columns-value" style="color:#EF6C00"><?php echo $this->get_total_sales_count("YEAR"); ?></div>	
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">This Month Sales Count</div>
						<div>
							<div class="columns-icon"  style="color:#00897B"><i class="fa fa-line-chart fa-3x"></i></div>
							<div class="columns-value"  style="color:#00897B"><?php echo $this->get_total_sales_count("MONTH"); ?></div>
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">This Week Sales Count</div>
						<div>
							<div class="columns-icon" style="color:#039BE5"><i class="fa fa-line-chart fa-3x"></i></div>
							<div class="columns-value" style="color:#039BE5"><?php echo $this->get_total_sales_count("WEEK"); ?></div>
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">Today Sales Count</div>
						<div>
							<div class="columns-icon" style="color:#2cc185"><i class="fa fa-line-chart fa-3x"></i></div>
							<div class="columns-value" style="color:#2cc185"><?php echo $this->get_total_sales_count("DAY"); ?></div>
						</div>
					</div>
					<div style="clear:both"></div>
				</div>
				
			</div>
			<div class="content">
				<div class="box-title"><i class="fa fa-pie-chart" aria-hidden="true"></i> recent orders</div>
				<div style="border-bottom:4px solid #2cc185;"></div>
				<div class="box-data">
					<?php $this->get_recent_order(); ?>
				</div>
			</div>
			<div style="height:50px;">
				<div style="border-bottom:2px solid #e7eaec; padding-top:25px"></div>
			</div>
			<div class="content">
				<div style="width:49%; float:right;">
					<div class="box-title"><i class="fa fa-credit-card" aria-hidden="true"></i> Order Status Pie Chart</div>
					<div style="border-bottom:4px solid #2cc185;"></div>
				    <div class="box-data">
                    <?php $data = array(); ?>
                    <?php $data = $this->get_order_status(); ?>	
                    <?php 
					$total  = 0;
					foreach($data as $k=>$v){
						$total = $total +  $v->order_total;
					}
					foreach($data as $k=>$v){
						$data[$k]->value =  ( $v->order_total /$total ) *100;
						
						$data[$k]->order_status =  ucfirst ( str_replace("wc-","", $v->order_status));
						$data[$k]->order_total = wc_price($v->order_total);
					} 
					?>
                    <script type="text/javascript">
                     var data  =  <?php echo  json_encode ($data )?>;
					 var chart = AmCharts.makeChart( "order_status2", {
						
						autoMargins: false,
						marginTop: 0,
						marginBottom: 0,
						marginLeft: 0,
						marginRight: 0,
						pullOutRadius: 0,
						"type": "pie",
						"theme": "light",
						"dataProvider": data,
						"valueField": "value",
						"titleField": "order_status",
						"outlineAlpha": 0.4,
						"depth3D": 15,
						"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[order_total]]</b> ([[percents]]%)</span>",
						"angle": 30,
						
						"maxLabelWidth": 100,
    					"innerRadius": "0%",
						
						"export": {
						"enabled": false
						}
					} );
                    </script>
					<div id="order_status2" style="width:100%; height:250px"></div>	
					</div>
				</div>
				<div style="width:49%;">
					<div class="box-title"><i class="fa fa-credit-card" aria-hidden="true"></i>  Order Status Report</div>
					<div style="border-bottom:4px solid #2cc185;"></div>
					<div class="box-data">
						<table class="ni-email-data-table">
							<tr>
                                <th>Order Status</th>
                                <th>Order Count</th>
                                <th>Order Total</th>
                            </tr>
                            <?php $results = $this->get_order_status();?>
                            <?php foreach($results as $key=>$value){ ?>
							<tr>
                                <td><?php echo  ucfirst ( str_replace("wc-","", $value->order_status)); ?></td>
                                <td style="text-align:right"><?php echo $value->order_count; ?></td>
                                <td style="text-align:right"><?php echo wc_price($value->order_total); ?></td>
                            </tr>
                            <?php }?>
						</table>
					</div>
				</div>
				<div style="clear:both"></div>
			</div>
            <div style="height:50px;">
				<div style="border-bottom:2px solid #e7eaec; padding-top:25px"></div>
			</div>
            <div class="content">
				<div style="width:49%; float:right;">
					<div class="box-title"><i class="fa fa-credit-card" aria-hidden="true"></i> payment gateway pie chart</div>
					<div style="border-bottom:4px solid #2cc185;"></div>
					<div class="box-data">
                    <?php $data = array(); ?>
                    <?php $data = $this->get_payment_gateway(); ?>	
                    <?php 
					$total  = 0;
					foreach($data as $k=>$v){
						$total = $total +  $v->order_total;
					}
					foreach($data as $k=>$v){
						$data[$k]->value =  ( $v->order_total /$total ) *100;
						//$data[$k]->payment_method_title =  "A";
						$data[$k]->order_total = wc_price($v->order_total);
						
					} 
					?>
                    <script type="text/javascript">
                     var data  =  <?php echo  json_encode ($data )?>;
					 var chart = AmCharts.makeChart( "_payment_gateway_pie_chart", {
						labelsEnabled: true,
						autoMargins: false,
						marginTop: 0,
						marginBottom: 0,
						marginLeft: 0,
						marginRight: 0,
						pullOutRadius: 0,
						"type": "pie",
						"theme": "light",
						"dataProvider": data,
						"valueField": "value",
						"titleField": "payment_method_title",
						"outlineAlpha": 0.4,
						"depth3D": 15,
						"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[order_total]]</b> ([[percents]]%)</span>",
						"angle": 30,
						
						"maxLabelWidth": 100,
    					"innerRadius": "0%",
						
						"export": {
						"enabled": false
						}
					} );
                    </script>
					<div id="_payment_gateway_pie_chart" style="width:100%; height:250px"></div>	
					</div>
				</div>
				<div style="width:49%;">
					<div class="box-title"><i class="fa fa-credit-card" aria-hidden="true"></i> payment gateway report</div>
					<div style="border-bottom:4px solid #2cc185;"></div>
					<div class="box-data">
						<table class="ni-email-data-table">
							<tr>
                                <th>Payment Method</th>
                                <th>Order Count</th>
                                <th>Order Total</th>
                            </tr>
                            <?php $data  = $this->get_payment_gateway(); ?>
                            <?php  foreach($data  as $k=>$v){ ?>
							<tr>
                                <td><?php echo $v->payment_method_title; ?></td>
                                <td><?php echo $v->order_count; ?></td>
                                <td><?php echo wc_price($v->order_total); ?></td>
                            </tr>
                           <?php } ?> 
						</table>
					</div>
				</div>
				<div style="clear:both"></div>
			</div>
            <div style="height:50px;">
				<div style="border-bottom:2px solid #e7eaec; padding-top:25px"></div>
			</div>
		</div>
		<?php
		}	
		
	
	}
endif;
?>