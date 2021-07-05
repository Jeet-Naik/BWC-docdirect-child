<?php
/**
 * Template Name: Thank You
 */

get_header();

$option_key = $option_value = '';

//Get token from query string
$user_token = ( !empty($_GET['token']) ? $_GET['token'] : '' );

$query = "
	SELECT *
	FROM " . $wpdb->prefix . "options
	Where option_value='" . $user_token . "'";

//Get token record
$result = $wpdb->get_row($query);

if (!empty($result)) {
  $option_name   = $result->option_name;
  $option_value = $result->option_value;
}
//Compare token
if (!empty($user_token) && !empty($option_value)  && $option_value == $user_token) {
  setcookie("popup", "set_value", time() + (86400 * 30), "/"); 
?>
  <div class=content>
    <div class="wrapper-1">
      <div class="wrapper-2">

        <h1 class="thnks">Thank you !</h1>
        <?php
        $file = get_field('pdf_upload', 'option'); ?>
        <p>
          <?php if ($file) :
            $url = wp_get_attachment_url($file);
          ?>
            <a href="<?php echo esc_html($url); ?>" target="_blank">
              Click here to download Your Next Level Checklist.
            </a><br />
          <?php endif; ?></a> <a href="<?php echo get_site_url(); ?>">Back To Home</a>
        </p>
        <!--  <p>you should receive a confirmation email soon  </p> -->
      </div>

    </div>
  </div>
  <br />
  <br />
<?php
  get_footer();
  //Delete token record after loading page
  delete_option( $option_name );
} else {
?>
  <div class=content>
    <div class="wrapper-1">
      <div class="wrapper-2">

        <h1 class="thnks">Activation link expired Or invalid</h1>
        </a> <a href="<?php echo get_site_url(); ?>">Back To Home</a></p>
        <!--  <p>you should receive a confirmation email soon  </p> -->
      </div>
    </div>
  </div>
  <br />
  <br />

<?php
  get_footer();
}
?>

<style type="text/css">
  .wrapper-1 {
    width: 100%;
    height: 100vh;
    display: flex;
    flex-direction: column;
  }

  .wrapper-2 {
    padding: 30px;
    text-align: center;
  }

  h1.thnks {
    /* font-family: 'Kaushan Script', cursive;*/
    font-size: 4em;
    letter-spacing: 3px;
    color: #5892FF;
    margin: 0;
    margin-bottom: 20px;
  }

  .wrapper-2 p {
    margin: 0;
    font-size: 1.3em;
    color: #aaa;
    /*font-family: 'Source Sans Pro', sans-serif;*/
    letter-spacing: 1px;
    line-height: 40px;
  }

  .go-home {
    color: #fff;
    background: #5892FF;
    border: none;
    padding: 10px 50px;
    margin: 30px 0;
    border-radius: 30px;
    text-transform: capitalize;
    box-shadow: 0 10px 16px 1px rgba(174, 199, 251, 1);
  }

  .footer-like {
    margin-top: auto;
    background: #D7E6FE;
    padding: 6px;
    text-align: center;
  }

  .footer-like p {
    margin: 0;
    padding: 4px;
    color: #5892FF;
    /*font-family: 'Source Sans Pro', sans-serif;*/
    letter-spacing: 1px;
  }

  .footer-like p a {
    text-decoration: none;
    color: #5892FF;
    font-weight: 600;
  }

  @media (min-width:360px) {
    h1 {
      font-size: 4.5em;
    }

    .go-home {
      margin-bottom: 20px;
    }
  }

  @media (min-width:600px) {
    .content {
      max-width: 1000px;
      margin: 0 auto;
    }

    .wrapper-1 {
      height: initial;
      max-width: 620px;
      margin: 0 auto;
      margin-top: 50px;
      box-shadow: 4px 8px 40px 8px rgba(88, 146, 255, 0.2);
    }

  }
</style>