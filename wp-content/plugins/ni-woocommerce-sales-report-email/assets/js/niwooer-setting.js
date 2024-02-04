"use strict";
jQuery(function($){
	/*$('._niwoocr_copy_url').click(function(event){
		  event.preventDefault();
	  alert("The paragraph was clicked.");
	 		var $url = $('._niwoocr_cron_url').attr('href');
			var $temp = $("<input>");
			$temp.val($url).select();
			  document.execCommand("copy");
			  $temp.remove();
			  $(".is_copy").text("URL copied!");
		
		 alert($url);
	});*/
	$("#frm_email_report_setting" ).submit(function( event ) {
		$.ajax({
			beforeSend: function(){
			 $("._please-wait-text","#frm_email_report_setting").html('Please wait..');
			 $("._please-wait","#frm_email_report_setting").show();
			 
		    },
			url:ajax_object_email.ajax_email_report_url,
			data:$( "#frm_email_report_setting" ).serialize() ,
			success:function(data) {
				//alert(JSON.stringify(data ));
				$(".ajax_content","#frm_email_report_setting").show();
				
				$('.ajax_content',"#frm_email_report_setting").delay(9000).fadeOut('slow');
				
				 $("._please-wait","#frm_email_report_setting").hide();
			},
			error: function(errorThrown){
				console.log(errorThrown);
				//alert("e");
			}
		}); 
		
		 event.preventDefault();
	});
	$("#frm_send_test_email" ).submit(function( event ) {
		$.ajax({
			beforeSend: function(){
			 $("._please-wait-text","#frm_send_test_email").html('Please wait..');
			 $("._please-wait","#frm_send_test_email").show();
			 
		    },
			url:ajax_object_email.ajax_email_report_url,
			data:$( "#frm_send_test_email" ).serialize() ,
			success:function(data) {
				//alert(JSON.stringify(data ));
				 $("._please-wait-text","#frm_send_test_email").html(data);
				$(".ajax_content","#frm_send_test_email").show();
				
				$('.ajax_content',"#frm_send_test_email").delay(9000).fadeOut('slow');
				
				 $("._please-wait","#frm_send_test_email").hide();
				 	
				 //
			},
			error: function(errorThrown){
				console.log(errorThrown);
				//alert("e");
			}
		}); 
		
		 event.preventDefault();
	});
	
});
