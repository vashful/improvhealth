(function($) {
	$(function(){
		
		// needed so that Keywords can return empty JSON
		$.ajaxSetup({
			allowEmpty: true
		});
		
		$('#consultations-details-tab ul li:eq(9) a').colorbox({
			   srollable: false,
			innerWidth: 600,
			innerHeight: 100,
			href: SITE_URL + 'admin/consultations/diseases/create_ajax',
			onComplete: function() {
				$.colorbox.resize();
				$('form#diseases').removeAttr('action');
				$('form#diseases').live('submit', function(e) {
					
					var form_data = $(this).serialize();
					
					$.ajax({
						url: SITE_URL + 'admin/consultations/diseases/create_ajax',
						type: "POST",
					        data: form_data,
						success: function(obj) {
							
							if(obj.status == 'ok') {
								
								//succesfull db insert do this stuff
								var select = 'select[name="assessment_ids[]"]';
								var opt_val = obj.id;
								var opt_text = obj.title;
								var option = '<option value="'+opt_val+'" selected="selected">'+opt_text+'</option>';
								
								//append to dropdown the new option
								$(select).append(option);
																
								// TODO work this out? //uniform workaround
								//$('#client-address-tab ul li:eq(5) span').html(obj.title);
								$(select).trigger("liszt:updated");
								//close the colorbox
								$.colorbox.close();
							} else {
								//no dice
							
								//append the message to the dom
								$('#cboxLoadedContent').html(obj.message + obj.form);
								$('#cboxLoadedContent p:first').addClass('notification error').show();
							}
						}
						
						
					});
					e.preventDefault();
				});
				     
			}
		});
    		
		
    
    	$('#consultations-details-tab ul li:eq(10) a').colorbox({
			   srollable: false,
			innerWidth: 600,
			innerHeight: 150,
			href: SITE_URL + 'admin/consultations/referrer/create_ajax',
			onComplete: function() {
				$.colorbox.resize();
				$('form#referrer').removeAttr('action');
				$('form#referrer').live('submit', function(e) {
					
					var form_data = $(this).serialize();
					
					$.ajax({
						url: SITE_URL + 'admin/consultations/referrer/create_ajax',
						type: "POST",
					        data: form_data,
						success: function(obj) {
							
							if(obj.status == 'ok') {
								
								//succesfull db insert do this stuff
								var select = 'select[name=referrer_id]';
								var opt_val = obj.id;
								var opt_text = obj.title;
								var option = '<option value="'+opt_val+'" selected="selected">'+opt_text+'</option>';
								
								//append to dropdown the new option
								$(select).append(option);
																
								// TODO work this out? //uniform workaround
								//$('#client-address-tab ul li:eq(5) span').html(obj.title);
								$(select).trigger("liszt:updated");
								//close the colorbox
								$.colorbox.close();
							} else {
								//no dice
							
								//append the message to the dom
								$('#cboxLoadedContent').html(obj.message + obj.form);
								$('#cboxLoadedContent p:first').addClass('notification error').show();
							}
						}
						
						
					});
					e.preventDefault();
				});
				     
			}
		});
            
	});
})(jQuery);  