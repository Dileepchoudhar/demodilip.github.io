<?php /* Template Name: display form data */ ?>
<?php get_header(); ?>
<table style="width:70%" border="1" >
    <tr>
	                        <th>your-name</th>
							<th>your-email</th>
							<th>your-subject</th>
							<th>your-message</th>
    </tr>

      <?php

        global $wpdb;
        $result = $wpdb->get_results( "SELECT * FROM wp_contact_form");
        foreach ( $result as $print )   { ?>
          <tr>
                  <td><?php echo $print->your_name; ?> </td>
                  <td> <?php echo $print->your_email ; ?> </td>
                  <td> <?php echo $print->your_subject; ?> </td>
                  <td><?php echo $print->your_message; ?> </td>
          </tr>
            <?php }
      ?>

</table>
<?php get_header(); ?>