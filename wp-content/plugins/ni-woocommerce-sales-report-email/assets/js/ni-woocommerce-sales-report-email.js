// JavaScript Document
// JavaScript Document
jQuery(function($){

	
	//_please_wait

	//alert(ajax_object.ajax_email_report_url);
	$("#frm_email_report" ).submit(function( event ) {
		$.ajax({
			beforeSend: function(){
			 $("._please-wait-text").html('Please wait..');
			 $("._please-wait").show();
			 
		    },
			url:ajax_object_email.ajax_email_report_url,
			data:$( "#frm_email_report" ).serialize() ,
			success:function(data) {
				$(".ajax_content").html(data);
				 $("._please-wait").hide();
			},
			error: function(errorThrown){
				console.log(errorThrown);
				//alert("e");
			}
		}); 
		
		return false;
	});
	/*Form Submit*/
	$("#frm_email_report").trigger("submit");
	
	
	
});

jQuery(document).on('click','#btn_email',function() {
  	jQuery(".ajax_email_message").html("please wait email sending...");
//	alert(jQuery("#select_order").val());
	var data = {
    	action				: 'ni_email_report_action',
    	email_report_action	: 'ni_send_email',
		select_order	:jQuery("#select_order").val()
	};
	jQuery.ajax({
			beforeSend: function(){
			 jQuery("._please-wait-text").html('Please wait..');
			 jQuery("._please-wait").show();
			 
		    },
			url:ajax_object_email.ajax_email_report_url,
			data: data ,
			success:function(data) {
				//alert(data);
				jQuery("._please-wait-text").html(data);
				 
				 setTimeout(function() { jQuery("._please-wait").hide(); 
				 
				 }, 4000);
			},
			error: function(errorThrown){
				console.log(errorThrown);
				alert("e");
			}
		}); 
	return false;
});