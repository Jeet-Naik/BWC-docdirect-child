<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Doctor Directory
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">


<script type="text/javascript" src="https://js.squareupsandbox.com/v2/paymentform"></script>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/paid-memberships-pro/js/sqpaymentform.js">
    </script>

    <!-- link to the custom styles for SqPaymentForm -->
    
    <script>
     document.addEventListener("DOMContentLoaded", function(event) {
    if (SqPaymentForm.isSupportedBrowser()) {
      paymentForm.build();
      paymentForm.recalculateSize();

    }
  });
    </script>
<?php wp_head();?>
</head>
<style type="text/css">

.b-close {
    position: absolute;
    right: 0;
    top: 0;
    cursor: pointer;
    color: #fff;
    background: #ff0000;
    padding: 5px 10px;

}
.ttl{

    color: #DC46E6 !important;
    /*font-size: 20px !important;*/
	letter-spacing: 2px;
	text-align: center;
    text-transform: uppercase !important;

}
 .big_txt{
    font-size: 62px !important;
    line-height: 82px !important;
    font-weight: 400;
    text-align: center;
    padding: 30px 10px;
}
/* #popup_this {
	top: 60px !important;
	padding: 60px 0 0 0 !important;
	margin-top: 0 !important; 
	margin-left: 0 !important; 
	background: #fff;


} */

.small_txt{    text-align: center;
    font-size: 18px;
    line-height: 34px;}
/*.form-padding{
	padding: 60px;
}*/
input.wpcf7-form-control.wpcf7-submit.btn.btn-primary.popup-btn {
    /* padding: 0px 20px; */
    margin: 0 auto;
    height: 40px;
    width: 100%;
    line-height: 0;
    background: #cc8a67;
    color: #ffffff;
    font-weight: 700;
}
.popup-input > input, .popup-input > span > input {
	width: 80% !important;
}
.col-md-4.popup-input{
	text-align: center;
}
#wpcf7-f4182-o1 > form.wpcf7-form.init {
	padding: 40px 0;
    background: #214a50;
}
</style>
<script>
function getCookie(c_name) {
    var c_value = document.cookie,
        c_start = c_value.indexOf(" " + c_name + "=");
    if (c_start == -1) c_start = c_value.indexOf(c_name + "=");
    if (c_start == -1) {
        c_value = null;
    } else {
        c_start = c_value.indexOf("=", c_start) + 1;
        var c_end = c_value.indexOf(";", c_start);
        if (c_end == -1) {
            c_end = c_value.length;
        }
        c_value = unescape(c_value.substring(c_start, c_end));
    }
    return c_value;
}


var acookie = getCookie("popup");

jQuery( document ).ready(function() {
	if (!acookie) {
		jQuery('#popup_this').bPopup();
	}
});
</script>
</head>
<?php if (is_page('6140')) { ?>
<?php 
$class="";
if(isset($_COOKIE['popup'])) {
    // setcookie('lg', 'ro');
    $class="hide";
}
?>
<div id="popup_this" class="<?php echo $class; ?>">
    <span class="button b-close">
        <span>X</span>
    </span>
    <div class="model-popup search-form">
        <div class="search-form-area">
          <div class="search-form-content">
<!--             <h3 class="ttl">GET YOUR FREE WEBSITE CHECKLIST</h3> -->
            <h2 class="big_txt">It's time to redesign your life.</h2>
            <p class="small_txt">Fill out the form below, download your Digital Directory Brochure, and find a Black Woman Coach to help you reach your goals.</p>
          </div>
            <div class="search-form-input">
                <?php echo do_shortcode('[contact-form-7 id="8473" title="Welcome Page Popup"]'); ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<body <?php body_class()?>>
<?php do_action('docdirect_init_headers');?>