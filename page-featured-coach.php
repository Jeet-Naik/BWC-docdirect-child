<?php

get_header();

?>

<?php 
  $ids = get_users( array('role' => 'Professional' ,'fields' => 'ID') );
  $args = array(
      'Professional' => implode(',', $ids),
      'orderby' => 'date',
      'order' => 'DESC',
      );
  $user_query  = new WP_User_Query($args);
  $users = $user_query->results;

  //print_r($users[0]->data->ID);
  $user_membership=[];
  $Cnt = 0;
  $result=''; 
    foreach($users as $user){
        // print_r($user->data->ID);
        if($Cnt != 2){
            if(pmpro_getMembershipLevelForUser($user->data->ID)->name == "Featured Coach")
            {
                echo '<div class="featured-coache col-md-6">';
                    $avatar = apply_filters(
                          'docdirect_get_user_avatar_filter',
                           docdirect_get_user_avatar(array('width'=>275,'height'=>191), $user->data->ID),
                           array('width'=>275,'height'=>191) //size width,height
                        );
            
                    $first_name          = get_user_meta( $user->data->ID, 'first_name', true);
                    $last_name         = get_user_meta( $user->data->ID, 'last_name', true);
                    $display_name        = get_user_meta( $user->data->ID, 'display_name', true);
                    
                    if( !empty( $first_name ) || !empty( $last_name ) ){
                      $username = $first_name.' '.$last_name;
                    } else{
                      $username = $display_name;
                    }
            
                    $featured_string     = docdirect_get_user_featured_date($user->data->ID);
                    $current_date     = date('Y-m-d H:i:s');
                    $current_string = strtotime( $current_date );
                    $data = docdirect_get_everage_rating ( $user->data->ID );
                    $username = docdirect_get_username( $user->data->ID );
                    
                    //Social Link
                    $facebook = get_the_author_meta('facebook', $user->data->ID);
                    $twitter = get_the_author_meta('twitter', $user->data->ID);
                    $pinterest = get_the_author_meta('pinterest', $user->data->ID);
                    $linkedin = get_the_author_meta('linkedin', $user->data->ID);
                    $tumblr = get_the_author_meta('tumblr', $user->data->ID);
                    $google = get_the_author_meta('google', $user->data->ID);
                    $instagram = get_the_author_meta('instagram', $user->data->ID);
                    $skype = get_the_author_meta('skype', $user->data->ID);
                    $web_url = get_the_author_meta('url', $user->data->ID);
                    $company_name =  get_user_meta( $user->data->ID, 'billing_company', true );
                                                  
                    if (!empty($avatar)) {
                        echo '<a class="featured-coache-image" href="'.get_author_posts_url($user->data->ID).'" ><img src="'.esc_url( $avatar ).'" alt="'.$alt_user.'"></a>';
                    }
                    
                    echo '<div class="featured-coache-content"><h3 class="featured-coache-name"><a href="'.get_author_posts_url($user->data->ID).'">'.esc_attr( $username ).'</a></h3><span class="featured-coache-company-name">'.esc_attr( $company_name ).'</span><a class="featured-coache-web-link" target="_blank" href="'.esc_url($web_url).'">'.esc_attr( $web_url ).'</a>';
                    
                    if (!empty($facebook) || !empty($twitter) || !empty($pinterest) || !empty($linkedin) || !empty($tumblr) || !empty($google) || !empty($instagram) || !empty($skype)
                        ) {
                        echo  '<div class="featured-coache-social">
                              <ul class="tg-socialicons">';
                                  if (isset($facebook) && !empty($facebook)){
                                  echo  '
                                  <li class="tg-facebook"><a href="' . esc_url(get_the_author_meta('facebook', $user->data->ID)) .'" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                  ';
                                  } 
                                  if (isset($twitter) && !empty($twitter)){
                                  echo  '
                                  <li class="tg-twitter"><a href="' . esc_url(get_the_author_meta('twitter', $user->data->ID)) .'" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                  ';
                                  } 
                                  if (isset($pinterest) && !empty($pinterest)){
                                  echo  '
                                  <li class="tg-pinterest"><a href="' . esc_url(get_the_author_meta('pinterest', $user->data->ID)) .'" target="_blank"><i class="fa fa-pinterest"></i></a></li>
                                  ';
                                  } 
                                  if (isset($linkedin) && !empty($linkedin)){
                                  echo  '
                                  <li class="tg-linkedin"><a href="' . esc_url(get_the_author_meta('linkedin', $user->data->ID)) .'" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                  ';
                                  }
                                  if (isset($tumblr) && !empty($tumblr)){
                                  echo  '
                                  <li class="tg-tumblr"><a href="' . esc_url(get_the_author_meta('tumblr', $user->data->ID)) .'" target="_blank"><i class="fa fa-tumblr"></i></a></li>
                                  ';
                                  } 
                                  if (isset($google) && !empty($google)){
                                  echo  '
                                  <li class="tg-google"><a href="' . esc_url(get_the_author_meta('google', $user->data->ID)) .'" target="_blank"><i class="fa fa-google"></i></a></li>
                                  ';
                                  } 
                                  if (isset($instagram) && !empty($instagram)){
                                  echo  '
                                  <li class="tg-instagram"><a href="' . esc_url(get_the_author_meta('instagram', $user->data->ID)) .'" target="_blank"><i class="fa fa-instagram"></i></a></li>
                                  ';
                                  } 
                                  if (isset($skype) && !empty($skype)){
                                  echo  '
                                  <li class="tg-skype"><a href="' . esc_url(get_the_author_meta('skype', $user->data->ID)) .'" target="_blank"><i class="fa fa-skype"></i></a></li>
                                  ';
                                  } 
                                  echo   '
                               </ul>
                               </div>';
                               }
                               echo  '<a class="view_profile" href="' . get_author_posts_url($user->data->ID) .'">View Profile</a>';
                               echo '</div>';
                              echo  '</div>';
                      
                         $Cnt++;
                        }
                    } else {
                        break;
                    }
                    
            }
            
get_footer();



