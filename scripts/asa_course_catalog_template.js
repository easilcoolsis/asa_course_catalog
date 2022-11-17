jQuery(document).ready(function($){

	var subject_id = $('#ee_filter_subject option:selected').val();
	var level_id = $('#ee_filter_level option:selected').val();
	if (subject_id != null && subject_id != "")
	{
		filter_subject(subject_id, level_id);
	}

	function updateUrlWithQueryParam($queryParamName, $queryParamValue )
	{
	   var queryParams = new URLSearchParams(window.location.search);
	   if ($queryParamValue == '' || $queryParamValue == null)
	       queryParams.delete($queryParamName);
	   else  
	   	   queryParams.set($queryParamName, $queryParamValue);
	   var replaceUrl = queryParams.toString() == "" ?  window.location.pathname : "?"+ queryParams.toString();		  
       history.replaceState (null, null, replaceUrl );
	}
	
	$('#search-filter').on('change', function () {
		var search_filter = $(this).val();
		updateUrlWithQueryParam('searchfilter', search_filter);
	});

	$('#ee_filter_subject').on('change', function () {
		var subject_id = $(this).val();
		filter_subject(subject_id);
		updateUrlWithQueryParam('subject', subject_id);
		updateUrlWithQueryParam('level', '');				
	});

	$('#ee_filter_level').on('change', function () {
		var level_id = $(this).val();
		updateUrlWithQueryParam('level', level_id);
	});


	function filter_subject(subject_id, level_id) {
		var data = {
			'action': 'get_levels',
			'subject': subject_id			
		};	

		$.post(level_ajax_object.ajax_url, data, function(response) {
			$('#ee_filter_level').html(response);
			$('#ee_filter_level').val(level_id).change(); ;
		});
	}
	
	if ( $.isFunction($.fn.footable) ) {
		$('.footable').footable();
	}
});
