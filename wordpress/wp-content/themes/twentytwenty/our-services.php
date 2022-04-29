<?php /* Template Name: our-services
        Template Post Type: post, page, event

*/ ?>
 
<?php get_header(); ?>
<section class="futr-des">
	<div class="set-text">
	<h5> <?php echo get_field('case_heading');  ?> </h5>
	<div>  <?php echo get_field('case_subheading');  ?> </div>
	</div>
</section>

<section class="our-col-section">
	      <div class="flx">
                
                <?php 
                     $tabel = get_field('case_repeater');
                    foreach($tabel as $tabe) {
                        echo ' <div class="col25">';
						echo ' <div class="serv-box-2 s2">';
                       echo '<span class="nav-text">'.$tabe['nav_text'].'</span>';
										echo "</div>";

						echo "</div>";
            		} 
				?>		                            
	</div> </section>
<section class="our-col-section">
	      <div class="flx">
                
                <?php 
                     $tabel = get_field('case_repeater');
                    foreach($tabel as $tabe) {
                        echo ' <div class="col25">';
						echo ' <div class="serv-box-2 s2">';
   echo '<img class="choose-img" src="'.$tabe['service_image'].'">';
						echo '<h4 class="content-box">'.$tabe['service_heading'].'</h4>';
						echo '<div>'.$tabe['service_subheading'].'</div>';						
                        echo "</div>";
						echo "</div>";
            		} 
				?>		                            
	</div> </section>