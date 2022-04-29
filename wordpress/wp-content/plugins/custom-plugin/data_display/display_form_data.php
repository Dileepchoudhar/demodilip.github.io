
<?php   
 ?>
<table border="1" id="contact_form_table" style="text-align:center; width:100%" data-page-length='5' class="display responsive nowrap" class="display">
<thead>
  <tr>
	                        <th>your-name</th>
							<th>your-email</th>
							<th>your-subject</th>
							<th>your-message</th>
    </tr>
</thead>

      <?php

        global $wpdb;
        $result = $wpdb->get_results( "SELECT * FROM wp_contact_form");?>
       <?php   foreach ( $result as $print )   { ?>
        <tbody>
 <tr>
                  <td><?php echo $print->your_name; ?> </td>
                  <td> <?php echo $print->your_email ; ?> </td>
                  <td> <?php echo $print->your_subject; ?> </td>
                  <td><?php echo $print->your_message; ?> </td>
          </tr> </tbody>
            <?php }
      ?>
</table>

<script>
      jQuery(document).ready(function() {
    jQuery('#contact_form_table').DataTable();
} );
      </script>