<?php

/**

 * Theme functions file

 */



/**

 * Enqueue parent theme styles first

 * Replaces previous method using @import

 * <http://codex.wordpress.org/Child_Themes>

 */



function docdirect_child_theme_enqueue_styles()
{

    $parent_theme_version = wp_get_theme('docdirect');

    $child_theme_version  = wp_get_theme('docdirect-child');

    $parent_style  = 'docdirect_theme_style';

    wp_enqueue_style(
        'docdirect_child_style',

        get_stylesheet_directory_uri() . '/style.css',

        array($parent_style),

        $child_theme_version->get('Version')

    );

    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css', array('bootstrap.min', 'choosen'), $parent_theme_version->get('Version'));

    wp_enqueue_script('bpopup-js', get_stylesheet_directory_uri() . '/js/jquery.bpopup.min.js', array());

    wp_enqueue_style('owl-carousel', get_stylesheet_directory_uri() . '/css/owl.carousel.css');
    wp_enqueue_style('testimonial-slider-shortcode', get_stylesheet_directory_uri() . '/css/testimonial-slider-shortcode.css');
    wp_enqueue_script('owl-carousel', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js', array('jquery'), '', true);
}



add_action('wp_enqueue_scripts', 'docdirect_child_theme_enqueue_styles');


add_shortcode('directory_type_top_category', 'slidermy');
function slidermy($atts)
{
    ob_start();
    $query = new WP_Query(array(
        'post_type' => 'directory_type',
        'posts_per_page' => 6,
        'order' => 'DESC',

    ));
    if ($query->have_posts()) { ?>
        <div class="category_search_section haslayout  default">
            <div class="builder-items">
                <div class="col-xs-12 col-md-12 builder-column " style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;">

                    <div class="doc-topcategories">
                        <?php
                        while ($query->have_posts()) : $query->the_post();
                            $category_image = fw_get_db_post_option(get_the_ID(), 'category_image', true);
                        ?>
                            <div class="col-md-4 col-sm-4 col-xs-6">
                                <div class="doc-category">
                                    <figure class="doc-categoryimg">
                                        <div class="doc-hoverbg">

                                            <h3><?php the_title(); ?></h3>
                                        </div>

                                        <?php
                                        if (!empty($category_image['attachment_id'])) {
                                            $banner    = docdirect_get_image_source($category_image['attachment_id'], 470, 305);
                                        } else {
                                            $banner    = get_template_directory_uri() . '/images/user470x305.jpg';
                                        }
                                        ?>
                                        <img src="<?php echo esc_url($banner); ?>" alt="<?php the_title(); ?>">

                                        <figcaption class="doc-imghover">
                                            <div class="doc-categoryname">
                                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        </div>

    <?php $myvariable = ob_get_clean();
        return $myvariable;
    }
}

//Shortcode for Feature Coach
function featured_coach_listing_function()
{
    $ids = get_users(array('role' => 'Professional', 'fields' => 'ID'));
    $args = array(
        'Professional' => implode(',', $ids),
        'orderby' => 'date',
        'order' => 'DESC',
    );
    $user_query  = new WP_User_Query($args);
    $users = $user_query->results;

    //print_r($users[0]->data->ID);
    $user_membership = [];
    $Cnt = 0;
    $result = '';
    foreach ($users as $user) {
        // print_r($user->data->ID);
        if ($Cnt != 2) {
            // echo "<pre>";    
            $data = (array) pmpro_getMembershipLevelForUser($user->data->ID);
            // echo "<pre>";
            // print_r( $data['name']);die;
            if ( $data['name'] == "Featured Coach") {
            // if (!empty($data['name']) && $data['name'] == "Featured Coach") {
                $result .= '<div class="featured-coache col-md-6">';
                $avatar = apply_filters(
                    'docdirect_get_user_avatar_filter',
                    docdirect_get_user_avatar(array('width' => 275, 'height' => 191), $user->data->ID),
                    array('width' => 275, 'height' => 191) //size width,height
                );

                $first_name          = get_user_meta($user->data->ID, 'first_name', true);
                $last_name         = get_user_meta($user->data->ID, 'last_name', true);
                $display_name        = get_user_meta($user->data->ID, 'display_name', true);

                if (!empty($first_name) || !empty($last_name)) {
                    $username = $first_name . ' ' . $last_name;
                } else {
                    $username = $display_name;
                }

                $featured_string     = docdirect_get_user_featured_date($user->data->ID);
                $current_date     = date('Y-m-d H:i:s');
                $current_string = strtotime($current_date);
                $data = docdirect_get_everage_rating($user->data->ID);
                $username = docdirect_get_username($user->data->ID);

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
                $company_name =  get_user_meta($user->data->ID, 'billing_company', true);

                if (!empty($avatar)) {
                    $result .= '<a class="featured-coache-image" href="' . get_author_posts_url($user->data->ID) . '" ><img src="' . esc_url($avatar) . '" alt=""></a>';
                }

                $result .= '<div class="featured-coache-content"><h3 class="featured-coache-name"><a href="' . get_author_posts_url($user->data->ID) . '">' . esc_attr($username) . '</a></h3><span class="featured-coache-company-name">' . esc_attr($company_name) . '</span><a class="featured-coache-web-link" target="_blank" href="' . esc_url($web_url) . '">' . esc_attr($web_url) . '</a>';

                if (
                    !empty($facebook) || !empty($twitter) || !empty($pinterest) || !empty($linkedin) || !empty($tumblr) || !empty($google) || !empty($instagram) || !empty($skype)
                ) {
                    $result .= '<div class="featured-coache-social">
                                      <ul class="tg-socialicons">
                                          ';
                    if (isset($facebook) && !empty($facebook)) {
                        $result .= '
                                          <li class="tg-facebook"><a href="' . esc_url(get_the_author_meta('facebook', $user->data->ID)) . '" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                          ';
                    }
                    if (isset($twitter) && !empty($twitter)) {
                        $result .= '
                                          <li class="tg-twitter"><a href="' . esc_url(get_the_author_meta('twitter', $user->data->ID)) . '" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                          ';
                    }
                    if (isset($pinterest) && !empty($pinterest)) {
                        $result .= '
                                          <li class="tg-pinterest"><a href="' . esc_url(get_the_author_meta('pinterest', $user->data->ID)) . '" target="_blank"><i class="fa fa-pinterest"></i></a></li>
                                          ';
                    }
                    if (isset($linkedin) && !empty($linkedin)) {
                        $result .= '
                                          <li class="tg-linkedin"><a href="' . esc_url(get_the_author_meta('linkedin', $user->data->ID)) . '" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                          ';
                    }
                    if (isset($tumblr) && !empty($tumblr)) {
                        $result .= '
                                          <li class="tg-tumblr"><a href="' . esc_url(get_the_author_meta('tumblr', $user->data->ID)) . '" target="_blank"><i class="fa fa-tumblr"></i></a></li>
                                          ';
                    }
                    if (isset($google) && !empty($google)) {
                        $result .= '
                                          <li class="tg-google"><a href="' . esc_url(get_the_author_meta('google', $user->data->ID)) . '" target="_blank"><i class="fa fa-google"></i></a></li>
                                          ';
                    }
                    if (isset($instagram) && !empty($instagram)) {
                        $result .= '
                                          <li class="tg-instagram"><a href="' . esc_url(get_the_author_meta('instagram', $user->data->ID)) . '" target="_blank"><i class="fa fa-instagram"></i></a></li>
                                          ';
                    }
                    if (isset($skype) && !empty($skype)) {
                        $result .= '
                                          <li class="tg-skype"><a href="' . esc_url(get_the_author_meta('skype', $user->data->ID)) . '" target="_blank"><i class="fa fa-skype"></i></a></li>
                                          ';
                    }
                    $result .=  '
                                       </ul>
                                       </div>';
                }
                $result .= '<a class="view_profile" href="' . get_author_posts_url($user->data->ID) . '">View Profile</a>';
                $result .= '</div>';
                $result .= '</div>';

                $Cnt++;
            }
        } else {
            break;
        }
    }
    return $result;
}
add_shortcode('featured-coach-listing', 'featured_coach_listing_function');



//remove_filter('featured-coach-listing', 'wpautop');

require get_stylesheet_directory() . '/framework-customizations/theme/options/headers-settings.php';
require get_stylesheet_directory() . '/inc/headers/class-headers.php';



//Shortcode for bootstrap row 
function shortcode_bootstrap_row($atts, $content = null)
{
    $a = shortcode_atts(
        array('class' => ''),
        $atts
    );
    return '<div class="row ' . esc_attr($a['class']) . '">' . do_shortcode($content) . '</div>';
}
add_shortcode('row', 'shortcode_bootstrap_row');

//Shortcode for bootstrap column 
function shortcode_bootstrap_column($atts, $content = null)
{
    $a = shortcode_atts(
        array('class' => 'col-xs-12'),
        $atts
    );
    return '<div class="' . esc_attr($a['class']) . '">' . do_shortcode($content) . '</div>';
}
add_shortcode('col', 'shortcode_bootstrap_column');

//Change the priority of the wpautop action to prevent 
//extra <p> tags being added between tags


//Quick Search
function quick_search_search_shortcode()
{

    ob_start();
    $uni_flag = fw_unique_increment();

    $args = array(
        'posts_per_page' => '-1',

        'post_type' => 'directory_type',

        'post_status' => 'publish',

        'suppress_filters' => false

    );



    //By categories 

    if (!empty($atts['categories'])) {

        $posts_in['post__in'] = $atts['categories'];

        $args = array_merge($args, $posts_in);
    }



    $cust_query = get_posts($args);

    docdirect_init_dir_map(); //init Map

    docdirect_enque_map_library(); //init Map

    $dir_search_page = fw_get_db_settings_option('dir_search_page');

    if (isset($dir_search_page[0]) && !empty($dir_search_page[0])) {

        $search_page     = get_permalink((int)$dir_search_page[0]);
    } else {

        $search_page     = '';
    }



    if (function_exists('fw_get_db_settings_option')) {

        $dir_location = fw_get_db_settings_option('dir_location');

        $dir_keywords = fw_get_db_settings_option('dir_keywords');

        $dir_longitude = fw_get_db_settings_option('dir_longitude');

        $dir_latitude = fw_get_db_settings_option('dir_latitude');

        $dir_map_marker_default = fw_get_db_settings_option('dir_map_marker');

        $dir_longitude  = !empty($dir_longitude) ? $dir_longitude : '-0.1262362';

        $dir_latitude   = !empty($dir_latitude) ? $dir_latitude : '51.5001524';
    } else {

        $dir_location = '';

        $dir_keywords = '';

        $dir_longitude = '-0.1262362';

        $dir_latitude  = '51.5001524';
    }


    ?>
    <form class="form" action="<?php echo esc_url($search_page); ?>" method="get" id="directory-map">

        <?php if (isset($dir_keywords) && $dir_keywords === 'enable') { ?>



            <div class="form-group">
                <label for="byname">Keyword</label>

                <input type="text" name="by_name" placeholder="<?php esc_html_e('Type Keyword...', 'docdirect'); ?>" class="form-control">

            </div>



        <?php } ?>

        <?php if (isset($dir_location) && $dir_location === 'enable') { ?>

            <div class="form-group">

                <?php docdirect_locateme_snipt(); ?>

                <script>
                    jQuery(document).ready(function(e) {

                        //init

                        jQuery.docdirect_init_map(<?php echo esc_js($dir_latitude); ?>, <?php echo esc_js($dir_longitude); ?>);

                    });
                </script>

            </div>



        <?php } ?>



        <div class="form-group"> <span class="select">
                <label>Categories</label>

                <select class="directory_type chosen-select" name="directory_type" id="options">

                    <option value="">
                        <?php esc_html_e('Select Categories', 'docdirect'); ?>

                    </option>

                    <?php

                    $parent_categories  = array();

                    $direction  = docdirect_get_location_lat_long();

                    $parent_categories['categories']    = array();

                    $first_category = '';

                    $json             = array();

                    $flag   = false;



                    $directories_array  =  array();

                    $directories    =  array();

                    $directories['status']  = 'none';

                    $directories['lat']  = floatval($direction['lat']);

                    $directories['long'] = floatval($direction['long']);



                    if (isset($cust_query) && !empty($cust_query)) {

                        $counter  = 0;

                        foreach ($cust_query as $key => $dir) {

                            $counter++;

                            $title = get_the_title($dir->ID);

                            $dir_icon = fw_get_db_post_option($dir->ID, 'dir_icon', true);

                            $dir_map_marker = fw_get_db_post_option($dir->ID, 'dir_map_marker', true);

                            if (empty($dir_icon)) {

                                $dir_icon   = 'icon-Hospitalmedicalsignalofacrossinacircle';
                            }



                            $user_query = new WP_User_Query(

                                array(

                                    'role' => 'professional',

                                    'order' => 'ASC',

                                    'count_total' => 'false',

                                    'meta_query' => array(

                                        'relation' => 'AND',

                                        array(

                                            'key'     => 'directory_type',

                                            'value'   => $dir->ID,

                                            'compare' => '='

                                        ),

                                        array(

                                            'key'     => 'verify_user',

                                            'value'   => 'on',

                                            'compare' => '='

                                        ),

                                        array(

                                            'key'     => 'profile_status',

                                            'value'   => 'active',

                                            'compare' => '='

                                        ),

                                    )

                                )

                            );







                            $postdata = get_post($dir->ID);

                            $slug    = $postdata->post_name;



                            if (!empty($user_query->results)) {

                                $directories['status']  = 'found';

                                $flag   = true;





                                foreach ($user_query->results as $user) {

                                    $latitude      = get_user_meta($user->ID, 'latitude', true);

                                    $longitude    = get_user_meta($user->ID, 'longitude', true);

                                    $directory_type = get_user_meta($user->ID, 'directory_type', true);

                                    $dir_map_marker = fw_get_db_post_option($directory_type, 'dir_map_marker', true);

                                    $reviews_switch    = fw_get_db_post_option($directory_type, 'reviews', true);

                                    $featured_string     = docdirect_get_user_featured_date($user->ID);

                                    $current_date   = date('Y-m-d H:i:s');

                                    $avatar = apply_filters(

                                        'docdirect_get_user_avatar_filter',

                                        docdirect_get_user_avatar(array('width' => 270, 'height' => 270), $user->ID),

                                        array('width' => 270, 'height' => 270) //size width,height

                                    );



                                    $privacy        = docdirect_get_privacy_settings($user->ID); //Privacy settin



                                    $directories_array['latitude']   = $latitude;

                                    $directories_array['longitude'] = $longitude;

                                    $directories_array['fax']         = $user->fax;

                                    $directories_array['description']  = $user->description;

                                    $directories_array['title']     = $user->display_name;

                                    $directories_array['name']       = $user->first_name . ' ' . $user->last_name;

                                    $directories_array['email']     = $user->user_email;

                                    $directories_array['phone_number'] = $user->phone_number;

                                    $directories_array['address']     = $user->user_address;

                                    $directories_array['group']     = $slug;

                                    $current_string = strtotime($current_date);

                                    $review_data    = docdirect_get_everage_rating($user->ID);

                                    $get_username   = docdirect_get_username($user->ID);

                                    $get_username   = docdirect_get_username($user->ID);



                                    if (isset($dir_map_marker['url']) && !empty($dir_map_marker['url'])) {

                                        $directories_array['icon']   = $dir_map_marker['url'];
                                    } else {

                                        if (!empty($dir_map_marker_default['url'])) {

                                            $directories_array['icon']   = $dir_map_marker_default['url'];
                                        } else {

                                            $directories_array['icon']         = get_template_directory_uri() . '/images/map-marker.png';
                                        }
                                    }



                                    $infoBox    = '<div class="tg-map-marker">';

                                    $infoBox    .= '<figure class="tg-docimg"><a class="userlink" target="_blank" href="' . get_author_posts_url($user->ID) . '"><img src="' . esc_url($avatar) . '" alt="' . esc_attr($directories_array['name']) . '"></a>';

                                    $infoBox    .= docdirect_get_wishlist_button($user->ID, false);



                                    if (isset($featured_string['featured_till']) && $featured_string['featured_till'] > $current_string) {

                                        $infoBox    .= docdirect_get_featured_tag(false);
                                    }



                                    $infoBox    .= docdirect_get_verified_tag(false, $user->ID);



                                    if (isset($reviews_switch) && $reviews_switch === 'enable') {

                                        $infoBox    .= docdirect_get_rating_stars($review_data, 'return');
                                    }



                                    $infoBox    .= '</figure>';



                                    $infoBox    .= '<div class="tg-mapmarker-content">';

                                    $infoBox    .= '<div class="tg-heading-border tg-small">';

                                    $infoBox    .= '<h3><a class="userlink" target="_blank" href="' . get_author_posts_url($user->ID) . '">' . $directories_array['name'] . '</a></h3>';

                                    $infoBox    .= '</div>';

                                    $infoBox    .= '<ul class="tg-info">';







                                    if (
                                        !empty($directories_array['email'])

                                        &&

                                        !empty($privacy['email'])

                                        &&

                                        $privacy['email'] == 'on'

                                    ) {

                                        $infoBox    .= '<li> <i class="fa fa-envelope"></i> <em><a href="mailto:' . $directories_array['email'] . '?Subject=' . esc_html__('hello', 'docdirect') . '"  target="_top">' . $directories_array['email'] . '</a></em> </li>';
                                    }



                                    if (
                                        !empty($directories_array['phone_number'])

                                        &&

                                        !empty($privacy['phone'])

                                        &&

                                        $privacy['phone'] == 'on'

                                    ) {

                                        $infoBox    .= '<li> <i class="fa fa-phone"></i> <em><a href="tel:' . $directories_array['phone_number'] . '">' . $directories_array['phone_number'] . '</a></em> </li>';
                                    }



                                    if (!empty($directories_array['address'])) {

                                        $infoBox    .= '<li> <i class="fa fa-home"></i> <address>' . $directories_array['address'] . '</address> </li>';
                                    }



                                    $infoBox    .= '</ul>';

                                    $infoBox    .= '</div>';

                                    $infoBox    .= '</div>';



                                    $directories_array['html']['content']   = $infoBox;

                                    $directories['users_list'][]    = $directories_array;
                                }
                            } else if ($flag === false) {

                                $directories['status']  = 'empty';
                            }



                            $active = '';

                            if ($counter === 1) {

                                $active = 'active';

                                $first_category = $dir->ID;
                            }



                            //Prepare categories

                            if (isset($dir->ID)) {

                                $attached_specialities = get_post_meta($dir->ID, 'attached_specialities', true);

                                $subarray   = array();

                                if (isset($attached_specialities) && !empty($attached_specialities)) {

                                    foreach ($attached_specialities as $key => $speciality) {

                                        if (!empty($speciality)) {

                                            $term_data  = get_term_by('id', $speciality, 'specialities');

                                            if (!empty($term_data)) {

                                                $subarray[$term_data->slug] = $term_data->name;
                                            }
                                        }
                                    }
                                }



                                $json[$dir->ID] = $subarray;
                            }





                            $parent_categories['categories']    = $json;

                    ?>

                            <option id="<?php echo intval($dir->ID); ?>" data-dir_name="<?php echo esc_attr($title); ?>" value="<?php echo esc_attr($dir->post_name); ?>"><?php echo esc_attr(ucwords($title)); ?></option>

                        <?php } ?>

                    <?php } else {

                        $directories['status']    = 'empty';
                    } ?>

                </select>

            </span>

            <script>
                jQuery(document).ready(function() {

                    var Z_Editor = {};

                    Z_Editor.elements = {};

                    window.Z_Editor = Z_Editor;

                    Z_Editor.elements = jQuery.parseJSON('<?php echo addslashes(json_encode($parent_categories['categories'])); ?>');



                    /* Init Markers */

                    docdirect_init_map_script(<?php echo json_encode($directories); ?>);

                });
            </script>

            <script type="text/template" id="tmpl-load-subcategories">

                <option value="">{{data['parent']}} - <?php esc_html_e('Specialities', 'docdirect'); ?></option>

                    <#

                        var _option = '';

                        if( !_.isEmpty(data['childrens']) ) {

                            _.each( data['childrens'] , function(element, index, attr) { #>

                                 <option value="{{index}}">{{element}}</option>

                            <#  

                            });

                        }

                    #>

                </script>

        </div>

        <div class="form-group"> <span class="select">
                <label><?php esc_html_e('Specialities', 'docdirect'); ?></label>
                <select class="group subcats chosen-select" name="speciality[]" id="options">

                    <option value="">

                        <?php esc_html_e('Select Specialities', 'docdirect'); ?>

                    </option>

                    <?php

                    if (isset($first_category)) {

                        $attached_specialities = get_post_meta($first_category, 'attached_specialities', true);

                        if (isset($attached_specialities) && !empty($attached_specialities)) {

                            foreach ($attached_specialities as $key => $speciality) {

                                if (!empty($speciality)) {

                                    $term_data  = get_term_by('id', $speciality, 'specialities');

                                    if (!empty($term_data)) { ?>

                                        <option value="<?php echo esc_attr($term_data->slug); ?>"><?php echo esc_attr($term_data->name); ?></option>

                    <?php

                                    }
                                }
                            }
                        }
                    }

                    ?>

                </select>

            </span> </div>

        <div class="form-group"> <a href="javascript:;"></a>

            <input type="submit" id="search_banner" class="search_btn" value="<?php esc_html_e('Search', 'docdirect'); ?>" />

        </div>

    </form>

    <!-- <form class="form" action="<?php //echo site_url('/dir-search/'); 
                                    ?>" method="get" id="searchfiltr">
  <div class="form-group">
    <label for="byname">Keyword*</label>
    <input type="text" id="byname" name="by_name" placeholder="Type Keyword..." class="form-control">
  </div>----->
    <?php
    // $locations = get_categories('taxonomy=locations&hide_empty=0'); 
    ?>
    <!-- <div class="form-group"> 
      <label>Location</label>  
     <select class="form-control" name="basics[location]" id="options">
      <option value="">Select Location</option>
 -->


    <?php
    /* foreach($locations as $location)
      { 
      ?>
           <option value= <?php echo $location->slug ?> > 
            <?php echo $location->name; ?> (<?php echo $location->count; ?>)
           </option>";
           <?php
   }*/
    ?>
    <!--  </select>
    </div>  -->

    <?php
    // $specialities_cat = get_categories('taxonomy=specialities&hide_empty=0'); 
    ?>
    <!-- <div class="form-group">
    <label>Speciality</label>     
       <select class="form-control" name="speciality[]" id="options">
        <option value="">Select Specialities</option> -->
    <?php
    /*foreach($specialities_cat as $specialitie)
        { 
        ?>
             <option value= <?php echo $specialitie->slug ?> > 
              <?php echo $specialitie->name ?> (<?php echo $specialitie->count; ?>)
             </option>";
             <?php
     }*/
    ?>
    <!--  </select>
    </div>
    <div class="form-group">
      <input type="submit" id="search_banner" class="search_btn" value="search">
  </div>
</form>  -->



<?php $specialsearch = ob_get_clean();

    return $specialsearch;
}

add_shortcode('quick-search', 'quick_search_search_shortcode');


//ACF 

if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title'  => 'Theme General Settings',
        'menu_title'  => 'Theme Settings',
        'menu_slug'   => 'theme-general-settings',
        'capability'  => 'edit_posts',
        'redirect'    => false
    ));

    acf_add_options_sub_page(array(
        'page_title'  => 'PDF Upload Settings',
        'menu_title'  => 'PDF Upload',
        'parent_slug' => 'theme-general-settings',
    ));
}


/*// Remove unwanted HTML comments
add_filter('the_content', 'remove_html_comments', 20, 1);
function remove_html_comments($content = '') {
    return preg_replace('/<!--(.|\s)*?-->/', '', $content);
}*/


//Start Tastimonial Carousol

add_action('init', 'testimonials_post_type');
function testimonials_post_type()
{
    $labels = array(
        'name' => 'Testimonials',
        'singular_name' => 'Testimonial',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Testimonial',
        'edit_item' => 'Edit Testimonial',
        'new_item' => 'New Testimonial',
        'view_item' => 'View Testimonial',
        'search_items' => 'Search Testimonials',
        'not_found' =>  'No Testimonials found',
        'not_found_in_trash' => 'No Testimonials in the trash',
        'parent_item_colon' => '',
    );

    register_post_type('testimonials', array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'exclude_from_search' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 10,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'register_meta_box_cb' => 'testimonials_meta_boxes', // Callback function for custom metaboxes
    ));
}



add_action('save_post', 'testimonials_save_post');
function testimonials_save_post($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!empty($_POST['testimonials']) && !wp_verify_nonce($_POST['testimonials'], 'testimonials'))
        return;

    if (!empty($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return;
    } else {
        if (!current_user_can('edit_post', $post_id))
            return;
    }

    if (!wp_is_post_revision($post_id) && 'testimonials' == get_post_type($post_id)) {
        remove_action('save_post', 'testimonials_save_post');

        wp_update_post(array('ID' => $post_id, 'post_title' => $_POST['post_title']));

        add_action('save_post', 'testimonials_save_post');
    }
}

add_filter('manage_edit-testimonials_columns', 'testimonials_edit_columns');
function testimonials_edit_columns($columns)
{
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Title',
        'testimonial' => 'Testimonial',
        'author' => 'Posted by',
        'date' => 'Date'
    );

    return $columns;
}

add_action('manage_posts_custom_column', 'testimonials_columns', 10, 2);
function testimonials_columns($column, $post_id)
{
    $testimonial_data = get_post_meta($post_id, '_testimonial', true);
    switch ($column) {
        case 'testimonial':
            the_content();
            break;
    }
}


function get_testimonial($posts_per_page = 1, $orderby = 'none', $testimonial_id = null)
{
    $args = array(
        'posts_per_page' => (int) $posts_per_page,
        'post_type' => 'testimonials',
        'orderby' => $orderby,
        'no_found_rows' => true,
    );
    if ($testimonial_id)
        $args['post__in'] = array($testimonial_id);

    $query = new WP_Query($args);

    $testimonials = '';
    if ($query->have_posts()) {
        while ($query->have_posts()) : $query->the_post();
            $post_id = get_the_ID();
            $testimonial_data = get_post_meta($post_id, '_testimonial', true);



            $testimonials .= '<aside class="testimonial">';
            $testimonials .= '<span class="quote">&ldquo;</span>';
            $testimonials .= '<div class="entry-content">';
            $testimonials .= '<p class="testimonial-text">' . get_the_content() . '<span></span></p>';
            $testimonials .= '<p class="testimonial-client-name"><cite>' . get_the_title() . '</cite>';
            $testimonials .= '</div>';
            $testimonials .= '</aside>';

        endwhile;
        wp_reset_postdata();
    }

    return $testimonials;
}


add_shortcode('testimonial', 'testimonial_shortcode');
/**
 * Shortcode to display testimonials
 *
 * [testimonial posts_per_page="1" orderby="none" testimonial_id=""]
 */
function testimonial_shortcode($atts)
{
    extract(shortcode_atts(array(
        'posts_per_page' => '1',
        'orderby' => 'none',
        'testimonial_id' => '',
    ), $atts));

    return get_testimonial($posts_per_page, $orderby, $testimonial_id);
}


add_shortcode('custom_testimonial_slider', 'testiSliderShort_shortcode');
function testiSliderShort_shortcode($atts, $content = null)
{
    $settings = shortcode_atts(array(
        'loop' => '1',
        'autoplay' => '1',
        'dots' => '1',
        'nav' => '1',
        'class' => '',
    ), $atts);

    $output = '';

    ob_start();
    $uid = 'tss_testimonial_slider_' . rand();
    $loop = ($settings['loop'] == '1') ? 'true' : 'false';
    $autoplay = ($settings['autoplay'] == '1') ? 'true' : 'false';
    $dots = ($settings['dots'] == '1') ? 'true' : 'false';
    $nav = ($settings['nav'] == '1') ? 'true' : 'false';
    $class = $settings['class'];

?>

    <div class="tss_testimonial_slider dots_<?php echo $dots; ?>">
        <div class="owl-carousel <?php echo $uid; ?>">
            <?php echo testiSliderShort_content_helper($content, true, true);
            query_posts(array('post_type' => 'testimonials', 'orderby' => 'rand'));
            if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="tss_item">
                        <div class="tss_item_in">
                            <p class="testimonial-content"><?php the_content(); ?></p><span class="author_name"><?php the_title(); ?></span>
                        </div>
                    </div>
            <?php endwhile;
            endif;
            wp_reset_query();
            ?>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".<?php echo $uid; ?>").owlCarousel({
                loop: <?php echo $loop; ?>,
                dots: <?php echo $dots; ?>,
                nav: <?php echo $nav; ?>,
                navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
                autoplay: <?php echo $autoplay; ?>,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 1
                    },
                    1000: {
                        items: 1
                    }
                }
            });
        });
    </script>
    <style type="text/css">
        .owl-prev {
            left: 0;
            position: absolute;
        }

        .owl-next {
            position: absolute;
            right: 0;
        }
    </style>
<?php
    $output .= ob_get_contents();
    ob_end_clean();

    return $output;
}


function testiSliderShort_content_helper($content, $paragraph_tag = false, $br_tag = false)
{
    $content = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);
    if ($br_tag) {
        $content = preg_replace('#<br \/>#', '', $content);
    }
    if ($paragraph_tag) {
        $content = preg_replace('#<p>|</p>#', '', $content);
    }
    return do_shortcode(shortcode_unautop(trim($content)));
}
/*--------End Custom Testimonial slider-----------*/

function comment_form_change_cookies_consent($fields)
{
    $commenter = wp_get_current_commenter();
    $consent   = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';
    $fields['cookies'] = '<div class="col-xs-12"><p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
        '<label for="wp-comment-cookies-consent">Save my name, email, and website in this browser for the next time I comment.</label></p></div>';
    return $fields;
}
add_filter('comment_form_default_fields', 'comment_form_change_cookies_consent');



remove_filter('the_content', 'wpautop');

/**
 * Send thank you link mail to user when subscribed
 */
function skip_mails_cf7($skip_mail, $contact_form)
{
    if (!session_id()) {
        session_start();
    }
    // get the contact form object
    $wpcf = WPCF7_ContactForm::get_current();
    $contact_form_id = $wpcf->id;

    //Skip mail sending if form is welcome popup contact form and send custom mail
    if ( $contact_form_id == 8473 )  //Get CF7 form id from admin dashboard
    {
        // skip mailing the data.  
        $skip_mail = true;

        $submission = WPCF7_Submission::get_instance();
        if ( $submission ) {
            $posted_data = $submission->get_posted_data();

            $token=md5( $posted_data['email-319'] );
            add_option( $posted_data['email-319'], $token );

            //Custom mail
            $headers = "Content-Type: text/html";
            $subject = "Thank You for Subscribing to Black Woman coach";
            $body    = "<h3>Hello ".$posted_data['text-909'].", Welcome to black woman coach</h3>";
            $body   .= '<br/><b><a href="' . site_url() . '/bwc/thank-you?token='.$token.'" target="__blank">Click here to download Your Next Level Checklist.</a></b> ';

            $sent = wp_mail( $posted_data['email-319'], $subject, $body, $headers );//send mail
        }  
    }

    //Skip mail sending if form is Grab your copy form and send custom mail
    if ( $contact_form_id == 10128 )  //Get CF7 form id from admin dashboard
    {
        // skip mailing the data.  
        $skip_mail = true;

        $submission = WPCF7_Submission::get_instance();
        if ( $submission ) {
            $posted_data = $submission->get_posted_data();

            $token=md5( $posted_data['email-102'] );
            add_option( $posted_data['email-102'], $token );

            //Custom mail
            $headers = "Content-Type: text/html";
            $subject = "Thank You for Subscribing to Black Woman coach";
            $body    = "<h3>Hello ".$posted_data['name-101'].", Welcome to black woman coach</h3>";
            $body   .= '<br/><b><a href="' . site_url() . '/bwc/thank-you?token='.$token.'" target="__blank">Click here to download Your Next Level Checklist.</a></b> ';

            $sent = wp_mail( $posted_data['email-102'], $subject, $body, $headers );//send mail
        }  
    }
    return $skip_mail;
}
add_filter( 'wpcf7_skip_mail', 'skip_mails_cf7', 10, 2 );


//test
function test()
{
    if (!session_id()) {
        session_start();
    }

    $data = $_SESSION['form'];
    echo "<pre>";
    print_r($data);
    die;
}
// add_action('init', 'test');
