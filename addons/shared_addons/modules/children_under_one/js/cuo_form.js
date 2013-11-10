(function($) {
	$(function(){
		
		// needed so that Keywords can return empty JSON
		$.ajaxSetup({
			allowEmpty: true
		});

		$("#complete").click(function(){

		// If checked
		if ($("#complete").is(":checked"))
		{
			//show the hidden div
			$(".hide-me").hide("fast");
		}
		else
		{
			//otherwise, hide it
			$(".hide-me").show("fast");
		}
	  });

		
	});
})(jQuery);  