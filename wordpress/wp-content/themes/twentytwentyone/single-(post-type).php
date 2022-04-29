<?php
 while ( have_posts() ) : the_post(); ?>

<h1><?php the_field('company_address_1'); ?></h1>
<h1><?php the_field('company_address_2'); ?></h1>
<h1><?php the_field('company_address_3'); ?></h1>
<h1><?php the_field('network_email'); ?></h1>
<h1><?php the_field('phone'); ?></h1>


<p><?php the_content(); ?></p>

<?php endwhile; // end of the loop. ?>


<html>  
<head>
</head>
    <body>
    <form id="test-form" action="">
<input type="text" name="company_address_1" value="<?php the_field('company_address_1'); ?>">
<input type="text" name="company_address_2" value="<?php the_field('company_address_2'); ?>">
<input type="text" name="company_address_3" value="<?php the_field('company_address_3'); ?>">
<input type="text" name="network_email" value="<?php the_field('network_email'); ?>">
<input type="text" name="phone" value="<?php the_field('phone'); ?>">
<input type="text" name="postcode" value="<?php the_field('postcode'); ?>">
<input type="text" name="website" value="<?php the_field('website'); ?>">
<input type="text" name="name" value="<?php the_field('name'); ?>">
<input type="text" name="job" value="<?php the_field('job'); ?>">
<input type="text" name="country" value="<?php the_field('country'); ?>">
<input type="text" name="other_interest" value="<?php the_field('other_interest'); ?>">
<input type="checkbox" name="interest" value="<?php the_field('interest'); ?>">

<input type="submit" value="Update">
</form>
</body>
</html>