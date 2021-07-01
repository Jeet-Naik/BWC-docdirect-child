<?php

/**

 * The template for displaying the footer.

 *

 * Contains the closing of the #content div and all content after

 *

 * @package Doctor Directory

 */



?>

<?php do_action('docdirect_prepare_footers');?>
<script type="text/javascript">
	jQuery('p > br').remove();

  jQuery(document).ready(function() {
    //remove old asterisks
    jQuery('span.pmpro_asterisk').remove();
    
    //add new ones
    jQuery('.pmpro_required').each(function(index, field) {
      var label = jQuery(field).siblings('label');
      label.html(label.html() + ' *');
    });
  });
</script>
<?php  wp_footer(); ?>

</body></html>