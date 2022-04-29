(function( $ ) {
	$( document ).ready(
		function(e) {
			
			var data_table_length = parseInt(contiom_bulk_content_params.data_table_length);
			
			function contiom_cats_tags_init(){
			
				if($('.contiom-tagsdiv').length){
					window.tagBox && window.tagBox.init();
				}
				
				// Handle categories.
				$('.contiom-categorydiv').each( function(){
					var this_id = $(this).attr('id'), catAddBefore, catAddAfter, taxonomyParts, taxonomy, settingName;
			
					taxonomyParts = this_id.split('-');
					taxonomyParts.shift();
					taxonomy = taxonomyParts.join('-');
					settingName = taxonomy + '_tab';
					
			
					if ( taxonomy == 'category' ) {
						settingName = 'cats';
					}
			
					// @todo Move to jQuery 1.3+, support for multiple hierarchical taxonomies, see wp-lists.js.
					$('a', '#' + taxonomy + '-tabs').on( 'click', function( e ) {
						e.preventDefault();
						var t = $(this).attr('href');
						$(this).parent().addClass('tabs').siblings('li').removeClass('tabs');
						$('#' + taxonomy + '-tabs').siblings('.tabs-panel').hide();
						$(t).show();
						if ( '#' + taxonomy + '-all' == t ) {
							deleteUserSetting( settingName );
						} else {
							setUserSetting( settingName, 'pop' );
						}
					});
			
					if ( getUserSetting( settingName ) )
						$('a[href="#' + taxonomy + '-pop"]', '#' + taxonomy + '-tabs').trigger( 'click' );
			
					// Add category button controls.
					$('#new' + taxonomy).one( 'focus', function() {
						$( this ).val( '' ).removeClass( 'form-input-tip' );
					});
			
					// On [Enter] submit the taxonomy.
					$('#new' + taxonomy).on( 'keypress', function(event){
						if( 13 === event.keyCode ) {
							event.preventDefault();
							$('#' + taxonomy + '-add-submit').trigger( 'click' );
						}
					});
			
					// After submitting a new taxonomy, re-focus the input field.
					$('#' + taxonomy + '-add-submit').on( 'click', function() {
						$('#new' + taxonomy).trigger( 'focus' );
					});
			
					/**
					 * Before adding a new taxonomy, disable submit button.
					 *
					 * @param {Object} s Taxonomy object which will be added.
					 *
					 * @return {Object}
					 */
					catAddBefore = function( s ) {
						if ( !$('#new'+taxonomy).val() ) {
							return false;
						}
			
						s.data += '&' + $( ':checked', '#'+taxonomy+'checklist' ).serialize();
						$( '#' + taxonomy + '-add-submit' ).prop( 'disabled', true );
						return s;
					};
			
					/**
					 * Re-enable submit button after a taxonomy has been added.
					 *
					 * Re-enable submit button.
					 * If the taxonomy has a parent place the taxonomy underneath the parent.
					 *
					 * @param {Object} r Response.
					 * @param {Object} s Taxonomy data.
					 *
					 * @return {void}
					 */
					catAddAfter = function( r, s ) {
						var sup, drop = $('#new'+taxonomy+'_parent');
			
						$( '#' + taxonomy + '-add-submit' ).prop( 'disabled', false );
						if ( 'undefined' != s.parsed.responses[0] && (sup = s.parsed.responses[0].supplemental.newcat_parent) ) {
							drop.before(sup);
							drop.remove();
						}
					};
			
					$('#' + taxonomy + 'checklist').wpList({
						alt: '',
						response: taxonomy + '-ajax-response',
						addBefore: catAddBefore,
						addAfter: catAddAfter
					});
			
					// Add new taxonomy button toggles input form visibility.
					$('#' + taxonomy + '-add-toggle').on( 'click', function( e ) {
						e.preventDefault();
						$('#' + taxonomy + '-adder').toggleClass( 'wp-hidden-children' );
						$('a[href="#' + taxonomy + '-all"]', '#' + taxonomy + '-tabs').trigger( 'click' );
						$('#new'+taxonomy).trigger( 'focus' );
					});
			
					// Sync checked items between "All {taxonomy}" and "Most used" lists.
					$('#' + taxonomy + 'checklist, #' + taxonomy + 'checklist-pop').on( 'click', 'li.popular-category > label input[type="checkbox"]', function() {
						var t = $(this), c = t.is(':checked'), id = t.val();
						if ( id && t.parents('#taxonomy-'+taxonomy).length )
							$('#in-' + taxonomy + '-' + id + ', #in-popular-' + taxonomy + '-' + id).prop( 'checked', c );
					});
			
				}); // End cats.
			}
			
			$(document).on('change', "#check_all_bulk", function(){
				if(this.checked){
					$(".contiom-bulk-content-article").each(function() {
						this.checked=true;
					});	
				}else{
					$(".contiom-bulk-content-article").each(function() {
						this.checked=false;
					});
				}
			});
			
			$(document).on('change', ".contiom-bulk-content-article", function(){
				if ($(this).is(":checked")) {
					var isAllChecked = 0;
		
					$(".contiom-bulk-content-article").each(function() {
						if (!this.checked)
							isAllChecked = 1;
					});
		
					if (isAllChecked == 0) {
						$("#check_all_bulk").prop("checked", true);
					}     
				}
				else {
					$("#check_all_bulk").prop("checked", false);
				}	
			});
			
			$(document).on('keyup', '.contiom-taxonomies-terms-filter', function(){
				var value = $(this).val().toLowerCase();
				$(this).parents('.contiom-categorydiv').find(".contiom-categorychecklist li").filter(function() {
				  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
			
			$(document).on('change', '.contiom-single-content-type select', function(){
				var $val = $(this).val();
				if($val == ''){
					$('.contiom-bulk-content-taxonomy').html('');	
				}else{
					$('.contiom-post-settings').addClass('loading');
					$.post(contiom_params.ajax_url, {'action':'load_bulk_content_taxonomies', 'post_type':$val}, function($data){
						$('.contiom-post-settings').removeClass('loading');
						$('.contiom-bulk-content-taxonomy').html($data.data);
						contiom_cats_tags_init();
						});	
				}
			});
			
			function contiom_field_validation($field){
				if($field.val() == ''){
					$field.addClass('error-field');
					return false;	
				}else{
					$field.removeClass('error-field');
					return true;	
				}
			}
			
			function is_valid_contiom_fields($type){
				if($type == 'normal'){
					var $err = 0;
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
					
					if(0 == $err){
						return true	
					}
					return false;
				}else{
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
			}
			
			$(document).on('click', '#contiom-get-bulk-content', function(event){
				event.preventDefault();
				
				if(!is_valid_contiom_fields('normal')){
					return;	
				}
				
				var $fields = ['project', 'language', 'template', 'story'];
				var $fields1 = ['filter-by'];	
				var $fields2 = ['filter-by-value'];
				
				var $values = {'action':'contiom-get-bulk-content'};
				$($fields).each(function(index, element) {
                    $values[$fields[index]] = $('.contiom-post-settings-body-content [name="contiom-single-'+$fields[index]+'"]').val();
                });
				
				$values['columns'] = $('.contiom-select2-columns').val();
				
				$($fields1).each(function(index, element) {
                    $values[$fields1[index]] = $('.contiom-post-settings-body-content .contiom-single-'+$fields1[index]+' select').serialize();
                });
				
				$($fields2).each(function(index, element) {
                    $values[$fields2[index]] = $('.contiom-post-settings-body-content .contiom-single-'+$fields2[index]+' input').serialize();
                });
				
				
				
				$('.contiom-post-settings').addClass('loading');
				$.post(contiom_params.ajax_url, $values, function($data){
					$('.contiom-post-settings').removeClass('loading');
					if($data.data.type == 'faild'){
						alert('No data found');		
					}else{
						$('.bulk_content_settings_form_left_body').html($data.data.table);
						$('.contiom-bulk-content-table').DataTable({
							"ordering": false,
							"info":     false,
							"lengthChange": false,
							//"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
							"pageLength": data_table_length,
							//"pageLength":10
						});
					}
				});
					
			});
			
			/*$(document).on( 'length.dt', ".contiom-bulk-content-table", function ( e, settings, len ) {
				data_table_length = len;
				console.log( 'New page length: '+len );
			} );*/
			
			$(document).on('click', '#contiom-get-bulk-content-advance', function(event){
				event.preventDefault();
				
				if(!is_valid_contiom_fields('advance')){
					return;	
				}
				
				var $fields = ['project', 'language', 'story'];
				var $fields1 = ['filter-by'];	
				var $fields2 = ['filter-by-value'];	
				
				var $values = {'action':'contiom-get-bulk-content-advance'};
				$($fields).each(function(index, element) {
                    $values[$fields[index]] = $('.contiom-post-settings-body-content [name="contiom-single-advance-'+$fields[index]+'"]').val();
                });
				$($fields1).each(function(index, element) {
                    $values[$fields1[index]] = $('.contiom-post-settings-body-content .contiom-single-advance-'+$fields1[index]+' select').serialize();
                });
				
				$($fields2).each(function(index, element) {
                    $values[$fields2[index]] = $('.contiom-post-settings-body-content .contiom-single-advance-'+$fields2[index]+' input').serialize();
                });
				
				$('.contiom-post-settings').addClass('loading');
				$.post(contiom_params.ajax_url, $values, function($data){
					$('.contiom-post-settings').removeClass('loading');
					if($data.data.type == 'faild'){
						alert('No data found');		
					}else{
						$('.bulk_content_settings_form_left_body').html($data.data.table);
						$('.contiom-bulk-content-table').DataTable({
							"ordering": false,
							"info":     false,
							"lengthChange": false,
							//"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
							"pageLength": data_table_length,
							//"pageLength":10
						});
					}
				});
					
			});
			
			$(document).on('click', '#contiom-bulk-content-add-to-draft', function(event){
				event.preventDefault();
				if($('input.contiom-bulk-content-article:checked').length <= 0){
					alert("You must check at least one article.");
					return false;	
				}
				
				if($('.contiom-single-content-type select').val() == ''){
					alert("You must select content type");
					return false;
				}
				
				var $v = $('#bulk-content-form').serialize();
				
				$('.bulk_content_settings_form_left').addClass('loading');
				$.post(contiom_params.ajax_url, $v, function($data){
					$('.bulk_content_settings_form_left').removeClass('loading');
					if($data.data.type == 'faild'){
						alert('No data found');		
					}else{
						$('.bulk_content_settings_form_left_body').html($data.data.table);
						
						$('.contiom-bulk-content-table').DataTable({
							"ordering": false,
							"info":     false,
							"lengthChange": false,
							//"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
							"pageLength": data_table_length,
							//"pageLength":10
						});
						alert('Selected articles are added');
					}	
				});
				
			});
			
			
			
		}
	);
})( jQuery );	