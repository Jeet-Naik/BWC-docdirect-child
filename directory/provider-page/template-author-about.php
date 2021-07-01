<?php
/**
 *
 * Author Header Template.
 *
 * @package   Docdirect
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
/**
 * Get User Queried Object Data
 */
global $current_user;
$author_profile = $wp_query->get_queried_object();
$avatar = apply_filters(
				'docdirect_get_user_avatar_filter',
				 docdirect_get_user_avatar(array('width'=>365,'height'=>365), $author_profile->ID),
				 array('width'=>365,'height'=>365) //size width,height
			);
$username 		= docdirect_get_username($author_profile->ID);
$professional_statements	  	 = isset( $author_profile->professional_statements ) ? $author_profile->professional_statements : '';
$is_chat = docdirect_is_chat_enabled();
?>
<div class="coach-profile-info">
	<div class="coach-profile-image">
		<a href="<?php echo esc_url(get_author_posts_url($author_profile->ID));?>"><img src="<?php echo esc_attr( $avatar );?>" alt="<?php echo esc_attr( $author_profile->first_name.' '.$author_profile->last_name );?>"></a>
	</div>
	<div class="coach-profile-detail">
		<div class="coach-profile-location">
			
			<?php $coach_address = get_user_meta( $author_profile->ID, 'user_address', true); ?>
			
			<i class="fa fa-map-marker"></i> <h4>LICENSED IN:</h4><p><?php if($coach_address) { echo $coach_address; }else { echo "Not Provided";}?></p>
		</div>
		<div class="coach-profile-name">
			<?php echo esc_attr( $username );?>
		</div>
		<span class="coach-profile-tag">
			NCC
		</span>
		<?php $company_name =  get_user_meta( $author_profile->ID, 'billing_company', true ); ?>	
		<span class="business_name">
			<?php if($company_name) { echo $company_name; }else { echo "No Business Name";}?>
		</span>
	</div>
</div>
<div class="coach-profile-map">
	<?php get_template_part('directory/provider-page/template-author', 'map'); ?>
</div>
<div class="coach-profile-content">
	<?php echo wpautop( nl2br( $author_profile->description ) );?>
</div>
<div class="row">
	<!-- <div class="col-lg-6">
		<div class="coach-entity">
		<div class="coach-entity-list">
			<i class="fa-fw fas fa-user-plus"></i>
			<span>Accepting New Patients</span>
			<label class="entity-value">Yes</label>
		</div>
		<div class="coach-entity-list">
			<i class="fa-fw fas fa-shield-alt"></i>
			<span>Accepts Insurance</span>
			<label class="entity-value">Yes</label>
		</div>
		<div class="coach-entity-list">
			<i class="fa-fw fas fa-user-friends"></i>
			<span> In-Person Sessions</span>
			<label class="entity-value">Yes</label>
		</div>
		<div class="coach-entity-list">
			<i class="fa-fw fas fa-laptop"></i>
			<span> Virtual Sessions</span>
			<label class="entity-value">Yes</label>
		</div>
		<div class="coach-entity-list">
			<i class="fa-fw fas fa-wallet"></i>
			<span> Self Pay Available</span>
			<label class="entity-value">Yes</label>
		</div>
		<div class="coach-entity-list">
			<i class="fa-fw fas fa-exchange-alt"></i>
			<span> Sliding Scale Available</span>
			<label class="entity-value">Yes</label>
		</div>
		<div class="accepted-insurance">
			<h6>Insurance Accepted:</h6>
			<p>Blue Cross Blue Shield, Cigna, Humana, Self-Pay, Sliding Scale, United Healthcare</p>
		</div>
	</div>
</div> -->
	<div class="col-lg-12">
		<div class="coach-profile-contact">
			<ul>
				<li>
					<div class="contact-info">
						<div class="contact-icon">
							<i class="fa-fw fas fa-phone "></i>
							<h3>Phone</h3>
						</div>
						<?php $phone = get_user_meta( $author_profile->ID, 'billing_phone', true); ?>
						
							<?php
								if($phone){ ?>
									<a href="tel:<?php echo $phone;?>">	<?php echo $phone;?></a>
								<?php }
									else{
										echo "Not Available";
									};
								?>
						</div>
				</li>
				<li>
					<div class="contact-info">
						<div class="contact-icon">
							<i class="fa-fw fas fa-envelope"></i>
							<h3>Email</h3>
						</div>
						<?php $email = get_user_meta( $author_profile->ID, 'billing_email', true); ?>
							<?php
								if($email){
								?> 
									<a href="mailto:<?php echo $email;?>" target="_blank"><?php echo $email;?></a>
								<?php }
									else{
										echo "Not Available";
									};
							?>
						</a>
					</div>
				</li>
				<li>
					<div class="contact-info">
						<div class="contact-icon">
							<i class="fa fa-globe"></i>
							<h3>Website</h3>
						</div>
							<?php $weburl = get_the_author_meta('url', $author_profile->ID);?>
								<?php 
								if($weburl){ ?>
									<a href="<?php echo $weburl; ?>" target="_blank">
										<?php echo $weburl; ?>
									</a>
									<?php } else{ 
										echo "Not Available";
						 			};
							?>
					</div>
				</li>
			</ul>
			<?php
				$facebook = get_the_author_meta('facebook', $author_profile->ID);
		        $twitter = get_the_author_meta('twitter', $author_profile->ID);
		        $pinterest = get_the_author_meta('pinterest', $author_profile->ID);
		        $linkedin = get_the_author_meta('linkedin', $author_profile->ID);
		        $tumblr = get_the_author_meta('tumblr', $author_profile->ID);
		        $google = get_the_author_meta('google', $author_profile->ID);
		        $instagram = get_the_author_meta('instagram', $author_profile->ID);
		        $skype = get_the_author_meta('skype', $author_profile->ID);

			 ?>
			<div class="social-icon-list">
				<h6>Social Media</h6>
				<?php if ($facebook) { ?>
					<a href="<?php echo $facebook ?>" target="_blank"><i class="fa fa-facebook-square fa-lg"></i></a>
				<?php } ?>
				<?php if ($twitter) { ?>
					<a href="<?php echo $twitter ?>" target="_blank"><i class="fa fa-twitter-square fa-lg"></i></a>
				<?php } ?>
				<?php if ($instagram) { ?>
					<a href="<?php echo $instagram ?>" target="_blank"><i class="fa fa-instagram fa-lg"></i></a>
				<?php } ?>
				<?php if ($pinterest) { ?>
					<a href="<?php echo $pinterest ?>" target="_blank"><i class="fa fa-pinterest fa-lg"></i></a>
				<?php } ?>
				<?php if ($linkedin) { ?>
					<a href="<?php echo $linkedin ?>" target="_blank"><i class="fa fa-linkedin fa-lg"></i></a>
				<?php } ?>
				<?php if ($tumblr) { ?>
					<a href="<?php echo $tumblr ?>" target="_blank"><i class="fa fa-tumblr fa-lg"></i></a>
				<?php } ?>
				<?php if ($google) { ?>
					<a href="<?php echo $google ?>" target="_blank"><i class="fa fa-google fa-lg"></i></a>
				<?php } ?>
				<?php if ($skype) { ?>
					<a href="<?php echo $skype ?>" target="_blank"><i class="fa fa-skype fa-lg"></i></a>
				<?php } ?>
			</div>
			<?php
				if ( is_user_logged_in() ) {
			        if ($current_user->ID != $author_profile->ID) {
			            if (!empty($is_chat) && $is_chat === 'yes') {
			                ?>
							<a class="btn send-msg send-message" data-toggle="modal" data-target="#chatmodal" href="javascript:;"><i class="lnr lnr-bubble"></i><?php esc_html_e('&nbsp;Send Me a Message', 'docdirect'); ?></a>
				<?php
			        	}
					} 
				}
				?>
			<!-- <a href="#" class="btn send-msg">Send Me a Message</a> -->
		</div>
	</div>
</div>	

<!-- <div class="tg-aboutuser">
	<div class="tg-userheading">
	  <h2><?php // esc_html_e('About','docdirect');?>&nbsp;<?php // echo esc_attr( $username );?></h2>
	</div>
	<a href="<?php //echo esc_url(get_author_posts_url($author_profile->ID));?>"><img src="<?php //echo esc_attr( $avatar );?>" alt="<?php //echo esc_attr( $author_profile->first_name.' '.$author_profile->last_name );?>"></a>
	<?php //if( !empty( $author_profile->description ) ) {?>
	  <div class="tg-description">
		<p><?php //echo wpautop( nl2br( $author_profile->description ) );?></p>
	  </div>
	<?php //}?>
	<?php //if( !empty( $professional_statements ) ){?>
		<div class="professional-statements">
			<?php //echo do_shortcode( nl2br( $professional_statements));?>
		</div>
	<?php //}?>
</div> -->