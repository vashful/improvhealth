(function($) {
	$(function(){
		
		// needed so that Keywords can return empty JSON
		$.ajaxSetup({
			allowEmpty: true
		});
		
		$('#client-details-tab ul li:eq(17) a').colorbox({
			   srollable: false,
			innerWidth: 600,
			innerHeight: 100,
			href: SITE_URL + 'admin/clients/barangay/create_ajax',
			onComplete: function() {
				$.colorbox.resize();
				$('form#barangay').removeAttr('action');
				$('form#barangay').live('submit', function(e) {
					
					var form_data = $(this).serialize();
					
					$.ajax({
						url: SITE_URL + 'admin/clients/barangay/create_ajax',
						type: "POST",
					        data: form_data,
						success: function(obj) {
							
							if(obj.status == 'ok') {
								
								//succesfull db insert do this stuff
								var select = 'select[name=barangay_id]';
								var opt_val = obj.id;
								var opt_text = obj.title;
								var option = '<option value="'+opt_val+'" selected="selected">'+opt_text+'</option>';
								
								//append to dropdown the new option
								$(select).append(option);
																
								// TODO work this out? //uniform workaround
								//$('#client-details-tab ul li:eq(5) span').html(obj.title);
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
		
		$('#client-details-tab ul li:eq(18) a').colorbox({
			   srollable: false,
			innerWidth: 600,
			innerHeight: 280,
			href: SITE_URL + 'admin/clients/city/create_ajax',
			onComplete: function() {
				$.colorbox.resize();
				$('form#city').removeAttr('action');
				$('form#city').live('submit', function(e) {
					
					var form_data = $(this).serialize();
					
					$.ajax({
						url: SITE_URL + 'admin/clients/city/create_ajax',
						type: "POST",
					        data: form_data,
						success: function(obj) {
							
							if(obj.status == 'ok') {
								
								//succesfull db insert do this stuff
								var select = 'select[name=city_id]';
								var opt_val = obj.id;
								var opt_text = obj.title;
								var option = '<option value="'+opt_val+'" selected="selected">'+opt_text+'</option>';
								
								//append to dropdown the new option
								$(select).append(option);
																
								// TODO work this out? //uniform workaround
								//$('#client-details-tab ul li:eq(5) span').html(obj.title);
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
		
				$('#client-details-tab ul li:eq(19) a').colorbox({
			   srollable: false,
			innerWidth: 600,
			innerHeight: 280,
			href: SITE_URL + 'admin/clients/province/create_ajax',
			onComplete: function() {
				$.colorbox.resize();
				$('form#province').removeAttr('action');
				$('form#province').live('submit', function(e) {
					
					var form_data = $(this).serialize();
					
					$.ajax({
						url: SITE_URL + 'admin/clients/province/create_ajax',
						type: "POST",
					        data: form_data,
						success: function(obj) {
							
							if(obj.status == 'ok') {
								
								//succesfull db insert do this stuff
								var select = 'select[name=province_id]';
								var opt_val = obj.id;
								var opt_text = obj.title;
								var option = '<option value="'+opt_val+'" selected="selected">'+opt_text+'</option>';
								
								//append to dropdown the new option
								$(select).append(option);
																
								// TODO work this out? //uniform workaround
								//$('#client-details-tab ul li:eq(5) span').html(obj.title);
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
		
		
				$('#client-details-tab ul li:eq(20) a').colorbox({
			   srollable: false,
			innerWidth: 600,
			innerHeight: 280,
			href: SITE_URL + 'admin/clients/region/create_ajax',
			onComplete: function() {
				$.colorbox.resize();
				$('form#region').removeAttr('action');
				$('form#region').live('submit', function(e) {
					
					var form_data = $(this).serialize();
					
					$.ajax({
						url: SITE_URL + 'admin/clients/region/create_ajax',
						type: "POST",
					        data: form_data,
						success: function(obj) {
							
							if(obj.status == 'ok') {
								
								//succesfull db insert do this stuff
								var select = 'select[name=region_id]';
								var opt_val = obj.id;
								var opt_text = obj.title;
								var option = '<option value="'+opt_val+'" selected="selected">'+opt_text+'</option>';
								
								//append to dropdown the new option
								$(select).append(option);
																
								// TODO work this out? //uniform workaround
								//$('#client-details-tab ul li:eq(5) span').html(obj.title);
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