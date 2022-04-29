(function( $ ) {
	$( document ).ready(
		function(e) {
			
			$('.contiom-select2').select2();
			$('.contiom-select2-columns').select2({
				placeholder: "Select columns",
    			allowClear: true,
				maximumSelectionLength: 4
			});
			
			$(document).on('change', '.contiom-single-project select', function(){
				var project = $(this).val();
				reset_selectors(['language', 'template', 'story', 'filter-by', 'filter-by-value', 'search-by', 'search-by-value', 'article']);
				if(project != ""){
					$(this).removeClass('error-field');
					contiom_load_languages(project, $('.contiom-single-language select'));	
				}
			});
			$(document).on('change', '.contiom-single-language select', function(){
				var project = $('.contiom-single-project select').val();
				var language = $(this).val();
				reset_selectors(['template', 'story', 'filter-by', 'filter-by-value', 'search-by', 'search-by-value', 'article']);
				if(language != ""){
					$(this).removeClass('error-field');
					contiom_load_templates(project, language, $('.contiom-single-template select'));	
				}
			});
			$(document).on('change', '.contiom-single-template select', function(){
				var project = $('.contiom-single-project select').val();
				var language = $('.contiom-single-language select').val();
				var template = $(this).val();
				reset_selectors(['story', 'filter-by', 'filter-by-value', 'search-by', 'search-by-value', 'article']);
				if(template != ""){
					$(this).removeClass('error-field');
					contiom_load_stories(project, language, template, $('.contiom-single-story select'));	
					contiom_load_template_columns(project, language, template);	
					
					if($('#contiom-single-get-content').length > 0){
						$('#contiom-single-get-content').removeClass('disabled');
					}
					
				}
			});
			
			$(document).on('change', '.contiom-single-advance-project select', function(){
				var project = $(this).val();
				reset_advance_selectors(['language', 'story', 'filter-by', 'filter-by-value', 'article']);
				if(project != ""){
					$(this).removeClass('error-field');
					contiom_load_advance_languages(project, $('.contiom-single-advance-language select'));	
				}
			});
			
			$(document).on('change', '.contiom-single-advance-language select', function(){
				var project = $('.contiom-single-advance-project select').val();
				var language = $(this).val();
				reset_advance_selectors(['story', 'filter-by', 'filter-by-value', 'article']);
				if(language != ""){
					$(this).removeClass('error-field');
					contiom_load_advance_stories(project, language, $('.contiom-single-advance-story select'));	
				}
			});
			
			$(document).on('click', '#contiom-single-refresh-content', function(){
				$('.contiom-post-settings').addClass('loading');
				var post_id = $('#post_ID').val();
				$.post(contiom_params.ajax_url, {'action':'contiom-post-refresh-content', 'post_id':post_id}, function($data){
					if($data.data.type == 'article'){
						window.location.replace($data.data.url);	
					}else{
						alert('No data found');		
					}
						
				});
			});
			
			$(document).on('click', '#contiom-single-advance-refresh-content', function(){
				$('.contiom-post-settings').addClass('loading');
				var post_id = $('#post_ID').val();
				$.post(contiom_params.ajax_url, {'action':'contiom-post-refresh-advance-content', 'post_id':post_id}, function($data){
					if($data.data.type == 'article'){
						window.location.replace($data.data.url);	
					}else{
						alert('No data found');		
					}
						
				});
			});
			
			$(document).on('click', '#contiom-single-get-content', function(){
				if($(this).hasClass('disabled')){
					return;	
				}
				var $fields = ['project', 'language', 'template', 'story', 'article'];
				var $fields1 = ['filter-by'];	
				var $fields2 = ['filter-by-value'];	
				
				
				if(!is_valid_contiom_fields()){
					return;	
				}
				
				
				var post_id = $('#post_ID').val();
				var $values = {'action':'contiom-post-get-content', "post_id":post_id};
				$($fields).each(function(index, element) {
                    $values[$fields[index]] = $('.contiom-post-settings-body-content [name="contiom-single-'+$fields[index]+'"]').val();
                });
				
				
				$($fields1).each(function(index, element) {
                    $values[$fields1[index]] = $('.contiom-post-settings-body-content .contiom-single-'+$fields1[index]+' select').serialize();
                });
				
				$($fields2).each(function(index, element) {
                    $values[$fields2[index]] = $('.contiom-post-settings-body-content .contiom-single-'+$fields2[index]+' input').serialize();
                });
				
				
				$('.contiom-post-settings').addClass('loading');
				$.post(contiom_params.ajax_url, $values, function($data){
					if($data.data.type == 'article'){
						window.location.replace($data.data.url);	
					}else if($data.data.type == 'select_article'){
						$('.contiom-post-settings').removeClass('loading');
						$('.contiom-single-article').removeClass('hide');
						$('.contiom-single-article select').empty();	
						for(i=0; i<$data.data.selection.length; i++) {
							var $item = '<option value="'+$data.data.selection[i]['value']+'">'+$data.data.selection[i]['label']+'</option>';
							$('.contiom-single-article select').append($item);
						}
						$('.contiom-single-article select').val('').trigger('change');	
					}else{
						$('.contiom-post-settings').removeClass('loading');
						alert('No data found');	
					}
					console.log($data);	
				});
					
			});
			
			$(document).on('change', '.contiom-single-auto-update-settings input', function(){
				if($(this).is(':checked')){
					$('.contiom-single-auto-update-settings-type, .contiom-single-auto-update-settings-date-time').removeClass('hide');	
				}else{
					$('.contiom-single-auto-update-settings-type, .contiom-single-auto-update-settings-date-time').addClass('hide');	
				}
			});
			
			$(document).on('change', '.contiom-single-advance-auto-update-settings input', function(){
				if($(this).is(':checked')){
					$('.contiom-single-advance-auto-update-settings-type, .contiom-single-advance-auto-update-settings-date-time').removeClass('hide');	
				}else{
					$('.contiom-single-advance-auto-update-settings-type, .contiom-single-advance-auto-update-settings-date-time').addClass('hide');	
				}
			});
			
			$(document).on('change', '.contiom-single-auto-update-settings-type select', function(){
				if($(this).val() == 'daily'){
					$('.contiom-data-row-field-date').hide();
				}
				else if($(this).val() == 'weekly'){
					$('.contiom-data-row-field-date').show();
					var $values = {'sunday':'Sunday', 'monday':'Monday', 'tuesday':'Tuesday', 'wednesday':'Wednesday', 'thursday':'Thursday', 'friday':'Friday', 'saturday':'Saturday'};
					$('.contiom-single-auto-update-settings-date-time select').empty();	
					$.each($values, function(index, element) {
						var $item = '<option value="'+index+'">'+element+'</option>';
						$('.contiom-single-auto-update-settings-date-time select').append($item);
					});
				}else{
					$('.contiom-data-row-field-date').show();
					$('.contiom-single-auto-update-settings-date-time select').empty();	
					for(i=1; i<=31; i++) {
						var $item = '<option value="'+i+'">'+i+'</option>';
						$('.contiom-single-auto-update-settings-date-time select').append($item);
					}
				}
			});
			
			$(document).on('change', '.contiom-single-advance-auto-update-settings-type select', function(){
				if($(this).val() == 'daily'){
					$('.contiom-data-row-field-advance-date').hide();
				}
				else if($(this).val() == 'weekly'){
					$('.contiom-data-row-field-advance-date').show();
					var $values = {'sunday':'Sunday', 'monday':'Monday', 'tuesday':'Tuesday', 'wednesday':'Wednesday', 'thursday':'Thursday', 'friday':'Friday', 'saturday':'Saturday'};
					$('.contiom-single-advance-auto-update-settings-date-time select').empty();	
					$.each($values, function(index, element) {
						var $item = '<option value="'+index+'">'+element+'</option>';
						$('.contiom-single-advance-auto-update-settings-date-time select').append($item);
					});
				}else{
					$('.contiom-data-row-field-advance-date').show();
					$('.contiom-single-advance-auto-update-settings-date-time select').empty();	
					for(i=1; i<=31; i++) {
						var $item = '<option value="'+i+'">'+i+'</option>';
						$('.contiom-single-advance-auto-update-settings-date-time select').append($item);
					}
				}
			});
			
			$(document).on('click', '#contiom-single-get-content-advance', function(){
				if($(this).hasClass('disabled')){
					return;	
				}
				
				if(!is_valid_contiom_advance_fields()){
					return;	
				}
				
				var $fields = ['project', 'language', 'story'];
				var $fields1 = ['filter-by'];	
				var $fields2 = ['filter-by-value'];	
				
				var post_id = $('#post_ID').val();
				var $values = {'action':'contiom-post-get-advanced-content', "post_id":post_id};
				$($fields).each(function(index, element) {
                    $values[$fields[index]] = $('.contiom-post-settings-body-content [name="contiom-single-advance-'+$fields[index]+'"]').val();
                });
				$($fields1).each(function(index, element) {
                    $values[$fields1[index]] = $('.contiom-post-settings-body-content .contiom-single-advance-'+$fields1[index]+' select').serialize();
                });
				
				$($fields2).each(function(index, element) {
                    $values[$fields2[index]] = $('.contiom-post-settings-body-content .contiom-single-advance-'+$fields2[index]+' input').serialize();
                });
				
				console.log($values);
				$('.contiom-post-settings').addClass('loading');
				$.post(contiom_params.ajax_url, $values, function($data){
					//$('.contiom-post-settings').removeClass('loading');
					if($data.data.type == 'article'){
						window.location.replace($data.data.url);	
					}else{
						$('.contiom-post-settings').removeClass('loading');
						alert('No data found');	
					}
				});
				
			});
			
			$(document).on('change', '.contiom-single-advance-story select', function(){
				var project = $('.contiom-single-advance-project select').val();
				var language = $('.contiom-single-advance-language select').val();
				var story = $(this).val();
				if(story != ""){
					$(this).removeClass('error-field');
					contiom_load_advance_columns(project, language, story);	
					if($('#contiom-single-get-content-advance').length > 0){
						$('#contiom-single-get-content-advance').removeClass('disabled');
					}
				}
			});
			
			function is_valid_contiom_fields(){
				var $err = 0;
				if(false == contiom_field_validation($('.contiom-single-project select'))){
					$err++;	
				}
				
				if(false == contiom_field_validation($('.contiom-single-language select'))){
					$err++;	
				}
				
				if(false == contiom_field_validation($('.contiom-single-template select'))){
					$err++;	
				}
				
				if(false == contiom_field_validation($('.contiom-single-story select'))){
					$err++;	
				}
				
				/*$filter_by_err = 0;
				$search_by_err = 0;
				if(false == contiom_field_validation($('.contiom-single-filter-by select'))){
					if(false == contiom_field_validation($('.contiom-single-filter-by-value input'))){
						$filter_by_err=1;
					}
				}				
				
				if($filter_by_err != 0){
					if(false == contiom_field_validation($('.contiom-single-search-by select'))){
					if(false == contiom_field_validation($('.contiom-single-search-by-value input'))){
							$search_by_err=1;
						}
					}	
				}
				
				if($search_by_err == 0){
					$('.contiom-single-filter-by select, .contiom-single-filter-by-value input').removeClass('error-field');
				}else{
					$err++;
					$('.contiom-single-search-by select, .contiom-single-search-by-value input').removeClass('error-field');	
				}
				
				if($('.contiom-single-filter-by select').val() != ''){
					if($('.contiom-single-filter-by-value input').val() == ''){
						$('.contiom-single-filter-by-value input').addClass('error-field');
						$err++;	
					}else{
						$('.contiom-single-filter-by-value input').removeClass('error-field');
					}
				}else{
					$('.contiom-single-filter-by-value input').removeClass('error-field');
				}
				
				if($('.contiom-single-search-by select').val() != ''){
					if($('.contiom-single-search-by-value input').val() == ''){
						$('.contiom-single-search-by-value input').addClass('error-field');
						$err++;	
					}else{
						$('.contiom-single-search-by-value input').removeClass('error-field');
					}
				}else{
					$('.contiom-single-search-by-value input').removeClass('error-field');
				}*/
				
				
				if(0 == $err){
					return true	
				}
				return false;
			}
			
			function is_valid_contiom_advance_fields(){
				var $err = 0;
				if(false == contiom_field_validation($('.contiom-single-advance-project select'))){
					$err++;	
				}
				
				if(false == contiom_field_validation($('.contiom-single-advance-language select'))){
					$err++;	
				}
								
				if(false == contiom_field_validation($('.contiom-single-advance-story select'))){
					$err++;	
				}
				
				if(0 == $err){
					return true	
				}
				return false;
			}
			
			function contiom_field_validation($field){
				if($field.val() == ''){
					$field.addClass('error-field');
					return false;	
				}else{
					$field.removeClass('error-field');
					return true;	
				}
			}
			
			function reset_selectors($selectors){
				$.each($selectors, function(index,selector){
					if($('.contiom-single-'+selector).length){
						if($('.contiom-single-'+selector).find('select').length > 0){
							$('.contiom-single-'+selector).find('select').empty();
							var $item = '<option value="">'+contiom_params.default_options[selector]+'</option>';
							$('.contiom-single-'+selector).find('select').append($item);	
						}
						if($('.contiom-single-'+selector).find('input').length > 0){
							$('.contiom-single-'+selector).find('input').val('');	
						}
					}
				});
			}
			
			function reset_advance_selectors($selectors){
				$.each($selectors, function(index,selector){
					if($('.contiom-single-advance-'+selector).length){
						if($('.contiom-single-advance-'+selector).find('select').length > 0){
							$('.contiom-single-'+selector).find('select').empty();
							var $item = '<option value="">'+contiom_params.default_options[selector]+'</option>';
							$('.contiom-single-advance-'+selector).find('select').append($item);	
						}
						if($('.contiom-single-advance-'+selector).find('input').length > 0){
							$('.contiom-single-advance-'+selector).find('input').val('');	
						}
					}
				});
			}
			
			function contiom_load_languages(project, language_selector){
				$('.contiom-post-settings').addClass('loading');
				$.post(contiom_params.ajax_url, {'action':'contiom-load-languages', project:project}, function(data){
					$('.contiom-post-settings').removeClass('loading');
					if(true	== data.success){
						language_selector.empty();	
						for(i=0; i<data.data.length; i++) {
							var $item = '<option value="'+data.data[i]['value']+'">'+data.data[i]['label']+'</option>';
							language_selector.append($item);
						}
						language_selector.val('').trigger('change');
					}
				});
			}
			
			function contiom_load_advance_languages(project, language_advance_selector){
				$('.contiom-post-settings').addClass('loading');
				$.post(contiom_params.ajax_url, {'action':'contiom-load-advance-languages', project:project}, function(data){
					$('.contiom-post-settings').removeClass('loading');
					if(true	== data.success){
						language_advance_selector.empty();	
						for(i=0; i<data.data.length; i++) {
							var $item = '<option value="'+data.data[i]['value']+'">'+data.data[i]['label']+'</option>';
							language_advance_selector.append($item);
						}
						language_advance_selector.val('').trigger('change');
					}
				});
			}
			
			function contiom_load_templates(project, language, template_selector){
				$('.contiom-post-settings').addClass('loading');
				$.post(contiom_params.ajax_url, {'action':'contiom-load-templates', project:project, language:language}, function(data){
					$('.contiom-post-settings').removeClass('loading');
					if(true	== data.success){
						template_selector.empty();	
						for(i=0; i<data.data.length; i++) {
							var $item = '<option value="'+data.data[i]['value']+'">'+data.data[i]['label']+'</option>';
							template_selector.append($item);
						}
						template_selector.val('').trigger('change');
					}
				});
			}
			
			function contiom_load_stories(project, language, template, story_selector){
				$('.contiom-post-settings').addClass('loading');
				$.post(contiom_params.ajax_url, {'action':'contiom-load-stories', project:project, language:language, template:template}, function(data){
					$('.contiom-post-settings').removeClass('loading');
					if(true	== data.success){
						story_selector.empty();	
						for(i=0; i<data.data.length; i++) {
							var $item = '<option value="'+data.data[i]['value']+'">'+data.data[i]['label']+'</option>';
							story_selector.append($item);
						}
						story_selector.val('').trigger('change');
					}
				});
			}
			
			function contiom_load_advance_stories(project, language, story_advance_selector){
				$('.contiom-post-settings').addClass('loading');
				$.post(contiom_params.ajax_url, {'action':'contiom-load-advance-stories', project:project, language:language}, function(data){
					$('.contiom-post-settings').removeClass('loading');
					if(true	== data.success){
						story_advance_selector.empty();	
						for(i=0; i<data.data.length; i++) {
							var $item = '<option value="'+data.data[i]['value']+'">'+data.data[i]['label']+'</option>';
							story_advance_selector.append($item);
						}
						story_advance_selector.val('').trigger('change');
					}
				});
			}
			
			function contiom_load_template_columns(project, language, template){
				$('.contiom-post-settings').addClass('loading');
				$.post(contiom_params.ajax_url, {'action':'contiom-load-template-columns', project:project, language:language, template:template}, function(data){
					$('.contiom-post-settings').removeClass('loading');
					if(true	== data.success){
						$('.contiom-single-filter-by select').empty();	
						if($('.contiom-single-columns select').length > 0) $('.contiom-single-columns select').empty();
						for(i=0; i<data.data.length; i++) {
							var $item = '<option value="'+data.data[i]['value']+'">'+data.data[i]['label']+'</option>';
							$('.contiom-single-filter-by select').append($item);
							if($('.contiom-single-columns select').length > 0 && '' != data.data[i]['value']) $('.contiom-single-columns select').append($item);
						}
					}
				});
			}
			
			function contiom_load_advance_columns(project, language, story){
				$('.contiom-post-settings').addClass('loading');
				$.post(contiom_params.ajax_url, {'action':'contiom-load-advance-columns', project:project, language:language, story:story}, function(data){
					$('.contiom-post-settings').removeClass('loading');
					if(true	== data.success){
						$('.contiom-single-advance-filter-by select').empty();	
						for(i=0; i<data.data.length; i++) {
							var $item = '<option value="'+data.data[i]['value']+'">'+data.data[i]['label']+'</option>';
							$('.contiom-single-advance-filter-by select').append($item);
						}
					}
				});
			}
			
			
			$(document).on('click', 'a.single-advance-filter-remove', function(event){
				event.preventDefault();
				var $this = $(this).parents('.contiom-single-advance-filter-by-value');
				var $prev = $this.prev('.contiom-single-advance-filter-by');
				$prev.remove();
				$this.remove();
			});
			
			$(document).on('click', '.contiom-single-advance-filter-add-more a', function(){
				var $clone_filter = $('.contiom-data-row.contiom-single-advance-filter-by:first').clone();
				var $clone_filter_value = $('.contiom-data-row.contiom-single-advance-filter-by-value:first').clone();	
				
				$clone_filter.find('select').val('').trigger('change');
				$clone_filter_value.find('input').val('');
				
				$clone_filter.insertBefore($(this).parents('.contiom-single-advance-filter-add-more'));
				$clone_filter_value.insertBefore($(this).parents('.contiom-single-advance-filter-add-more'));
				
			});
			
			$(document).on('click', '.contiom-single-filter-add-more a', function(){
				var $clone_filter = $('.contiom-data-row.contiom-single-filter-by:first').clone();
				var $clone_filter_value = $('.contiom-data-row.contiom-single-filter-by-value:first').clone();	
				
				$clone_filter.find('select').val('').trigger('change');
				$clone_filter_value.find('input').val('');
				
				$clone_filter.insertBefore($(this).parents('.contiom-single-filter-add-more'));
				$clone_filter_value.insertBefore($(this).parents('.contiom-single-filter-add-more'));
				
			});
			
			$(document).on('click', 'a.single-filter-remove', function(event){
				event.preventDefault();
				var $this = $(this).parents('.contiom-single-filter-by-value');
				var $prev = $this.prev('.contiom-single-filter-by');
				$prev.remove();
				$this.remove();
			})
			
			if($('.contiom-post-settings-head').length > 0){
				$('.contiom-post-settings-head li').click(
					function(){
						$('.contiom-post-settings-body-content').removeClass('active');
						$('.contiom-post-settings-head li').removeClass('active');
						$(this).addClass('active');
						var activeTab = $( this ).find( 'a' ).attr( 'data-href' );
						$( activeTab ).addClass('active');
					}
				);	
			}
			
			if ($( '.contiom-admin-tabs' ).length > 0) {
				$( '.contiom-admin-tabs-nav li:first-child' ).addClass( 'active' );
				$( '.contiom-admin-tab-content' ).hide();
				$( '.contiom-admin-tab-content:first' ).show();

				$( '.contiom-admin-tabs-nav li' ).click(
					function(){
						$( '.contiom-admin-tabs-nav li' ).removeClass( 'active' );
						$( this ).addClass( 'active' );
						$( '.contiom-admin-tab-content' ).hide();

						var activeTab = $( this ).find( 'a' ).attr( 'href' );
						$( activeTab ).fadeIn();
						return false;
					}
				);
			}

			if ($( '.contiom-admin-accordion' ).length > 0) {
				$( '.contiom-admin-accordion' ).each(
					function(index, element) {
						var $accordion = $( this );

						$accordion.find( 'h3:first-child' ).addClass( 'active' );
						$accordion.find( '.contiom-admin-accordion-content' ).hide();
						$accordion.find( '.contiom-admin-accordion-content:first' ).show();

						$accordion.find( 'h3' ).click(
							function(){
								$accordion.find( 'h3' ).removeClass( 'active' );
								$( this ).addClass( 'active' );
								$accordion.find( '.contiom-admin-accordion-content' ).hide();

								var activeTab = $( this ).next( '.contiom-admin-accordion-content' );
								$( activeTab ).fadeIn();
								return false;
							}
						);
					}
				);
			}

		}
	);
})( jQuery );
