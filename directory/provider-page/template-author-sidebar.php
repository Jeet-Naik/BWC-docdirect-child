<?php
/**
 *
 * Author Sidebar Template.
 *
 * @package   Docdirect
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
global $wp_query, $current_user;
/**
 * Get User Queried Object Data
 */
$author_profile = $wp_query->get_queried_object();
?>
		<?php 

if( !empty( $author_profile->user_profile_specialities ) ) {?>
  
<div class="col-md-4">
	<div class="coach-category-list">
	
	<h4><?php esc_html_e('My Specialties','docdirect');?></h4>
	
	
		  <ul class="tg-tags">
			  <?php
				do_action('enqueue_unyson_icon_css');														
				foreach( $author_profile->user_profile_specialities as $key => $value ){
					$get_speciality_term = get_term_by('slug', $key, 'specialities');
					$speciality_title = '';
					$term_id = '';
					if (!empty($get_speciality_term)) {
						$speciality_title = $get_speciality_term->name;
						$term_id = $get_speciality_term->term_id;
					}

					$speciality_meta = array();
					if (function_exists('fw_get_db_term_option')) {
						$speciality_meta = fw_get_db_term_option($term_id, 'specialities');
					}

					$speciality_icon = array();
					if (!empty($speciality_meta['icon']['icon-class'])) {
						$speciality_icon = $speciality_meta['icon']['icon-class'];
					}
				 ?>
				<li>
					
							<?php 
							if ( isset($speciality_meta['icon']['type']) && $speciality_meta['icon']['type'] === 'icon-font') {
								if (!empty($speciality_icon)) { ?>
									<i class="<?php echo esc_attr($speciality_icon); ?>"></i>
								<?php 
								}
							} else if ( isset($speciality_meta['icon']['type']) && $speciality_meta['icon']['type'] === 'custom-upload') {
								if (!empty($speciality_meta['icon']['url'])) {
								?>
								<img src="<?php echo esc_url($speciality_meta['icon']['url']);?>">
							<?php }}?>
						<a href="<?php echo get_term_link( $term_id, 'specialities' ); ?>"><?php echo esc_attr( $value );?></a>
					
				</li>
			  <?php }?>
		  </ul>
	  </div>
  </div>
<?php } ?>
		
	
