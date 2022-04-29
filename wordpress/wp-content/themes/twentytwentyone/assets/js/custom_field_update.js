jQuery(document).ready(function() {
    var ajaxurl = 'http://'+window.location.host+'/wp-admin/admin-ajax.php';
    var form = "#test-form";
    jQuery(form).submit(function(event) {
      event.preventDefault();
      jQuery.ajax({
        url: ajaxurl + "?action=test_function",
        type: 'post',
        data: jQuery(form).serialize(),
        success: function(data) {
          console.log("SUCCESS!");
          $('name="company_address_1"').val(data)
          $('name="company_address_2"').val(data)
          $('name="company_address_3"').val(data)
          $('name="network_email"').val(data)
          $('name="phone"').val(data)
          $('name="postcode"').val(data)
          $('name="website"').val(data)
          $('name="name"').val(data)
          $('name="job"').val(data)
          $('name="country"').val(data)
          $('name="other_interest"').val(data)
          $('name="interest"').val(data)
        },
        error: function(data) {
          console.log("FAILURE");
        }
      });
    });
  });