
<?php

	
	/*if ($_FILES["coachimg"]["error"] > 0)
      {
      echo "Error: " . $_FILES["coachimg"]["error"] . "<br />";
      }
    else
      {
      echo "Upload: " . $_FILES["coachimg"]["name"] . "<br />";
      echo "Type: " . $_FILES["coachimg"]["type"] . "<br />";
      echo "Size: " . ($_FILES["coachimg"]["size"] / 1024) . " Kb<br />";
      echo "Stored in: " . $_FILES["coachimg"]["tmp_name"];

      echo 'We will now save this document:';
      //Save document code
      }*/
require 'connect-php-sdk-master/vendor/autoload.php';



use Square\SquareClient;
use Square\LocationsApi;
use Square\Exceptions\ApiException;
use Square\Http\ApiResponse;
use Square\Models\ListLocationsResponse;
use Square\Environment;

// Initialize the Square client.
$client = new SquareClient([
  'accessToken' => "EAAAEDcnfj-ivTiU0OMSVxT52ZWDBVKautI5lOAzWKx-kE2xbZ8aYRkks7JcLuo8",
  'environment' => Environment::SANDBOX
]);



$amount_money = new \Square\Models\Money();
$amount_money->setAmount(100);
$amount_money->setCurrency('USD');
$nonce =  $_POST['nonce'];
$body = new \Square\Models\ChargeRequest(uniqid(), $amount_money);
$body->setCardNonce($nonce);

$api_response = $client->getTransactionsApi()->charge('L7DS9V344P92B', $body);

if ($api_response->isSuccess()) {
    $result = $api_response->getResult();
  echo "Transaction ID: " . $result->getTransaction()->getId();
} else {
    $errors = $api_response->getErrors();
}
      

      ?>

<div class="<?php echo pmpro_get_element_class( 'pmpro_confirmation_wrap' ); ?>">
<?php
	global $wpdb, $current_user, $pmpro_invoice, $pmpro_msg, $pmpro_msgt;



	

	if($pmpro_msg)
	{
	?>
		<div class="<?php echo pmpro_get_element_class( 'pmpro_message ' . $pmpro_msgt, $pmpro_msgt ); ?>"><?php echo wp_kses_post( $pmpro_msg );?></div>
	<?php
	}

	if(empty($current_user->membership_level))
		$confirmation_message = "<p>" . __('Your payment has been submitted. Your membership will be activated shortly.', 'paid-memberships-pro' ) . "</p>";
	else
		$confirmation_message = "<p>" . sprintf(__('Thank you for your membership to %s. Your %s membership is now active.', 'paid-memberships-pro' ), get_bloginfo("name"), $current_user->membership_level->name) . "</p>";

	//confirmation message for this level
	$level_message = $wpdb->get_var("SELECT l.confirmation FROM $wpdb->pmpro_membership_levels l LEFT JOIN $wpdb->pmpro_memberships_users mu ON l.id = mu.membership_id WHERE mu.status = 'active' AND mu.user_id = '" . $current_user->ID . "' LIMIT 1");
	if(!empty($level_message))
		$confirmation_message .= "\n" . stripslashes($level_message) . "\n";
?>

<?php if(!empty($pmpro_invoice) && !empty($pmpro_invoice->id)) { ?>

	<?php
		$pmpro_invoice->getUser();
		$pmpro_invoice->getMembershipLevel();

		$confirmation_message .= "<p>" . sprintf(__('Below are details about your membership account and a receipt for your initial membership invoice. A welcome email with a copy of your initial membership invoice has been sent to %s.', 'paid-memberships-pro' ), $pmpro_invoice->user->user_email) . "</p>";

		// Check instructions
		if ( $pmpro_invoice->gateway == "check" && ! pmpro_isLevelFree( $pmpro_invoice->membership_level ) ) {
			$confirmation_message .= '<div class="' . pmpro_get_element_class( 'pmpro_payment_instructions' ) . '">' . wpautop( wp_unslash( pmpro_getOption("instructions") ) ) . '</div>';
		}

		/**
		 * All devs to filter the confirmation message.
		 * We also have a function in includes/filters.php that applies the the_content filters to this message.
		 * @param string $confirmation_message The confirmation message.
		 * @param object $pmpro_invoice The PMPro Invoice/Order object.
		 */
		$confirmation_message = apply_filters("pmpro_confirmation_message", $confirmation_message, $pmpro_invoice);

		echo wp_kses_post( $confirmation_message );
	?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="invoice-header">
               <!--  <i class="fa fa-search-plus pull-left icon"></i> -->
                <h2><?php printf(__('Invoice #%s on %s', 'paid-memberships-pro' ), $pmpro_invoice->code, date_i18n(get_option('date_format'), $pmpro_invoice->getTimestamp()));?></h2><div class="print-btn"><a class="<?php echo pmpro_get_element_class( 'pmpro_a-print' ); ?>" href="javascript:window.print()"><?php _e('Print', 'paid-memberships-pro' );?></a></div>
            </div>
            <hr>
            <div class="row">
				<div class="col-xs-12 col-md-3 col-lg-3 pull-left">
            		<?php do_action("pmpro_invoice_bullets_top", $pmpro_invoice); ?>
                    <div class="panel panel-default height">
                        <div class="panel-heading">Account Details</div>
                        <div class="panel-body">
                            <strong><?php echo esc_html( $current_user->display_name );?></strong><br> 
                            <strong>Email:</strong> <?php echo esc_html( $current_user->user_email );?><br>
                            <strong><?php _e('Membership Level', 'paid-memberships-pro' );?>:</strong> <?php echo esc_html( $current_user->membership_level->name);?><br>
                            <?php if($current_user->membership_level->enddate) { ?>
							<strong><?php _e('Membership Expires', 'paid-memberships-pro' );?>:</strong> <?php echo date_i18n(get_option('date_format'), $current_user->membership_level->enddate)?>
							<?php } ?>
							<?php if($pmpro_invoice->getDiscountCode()) { ?>
								<strong><?php _e('Discount Code', 'paid-memberships-pro' );?>:</strong> <?php echo esc_html( $pmpro_invoice->discount_code->code );?>
							<?php } ?>
							<?php do_action("pmpro_invoice_bullets_bottom", $pmpro_invoice); ?>
                        </div>
                    </div>
                </div>
            	<div class="col-xs-12 col-md-3 col-lg-3">
            	<?php if(!empty($pmpro_invoice->billing->street)) { ?>	
                    <div class="panel panel-default height">
                        <div class="panel-heading"><?php _e('Billing Address', 'paid-memberships-pro' );?></div>
                        <div class="panel-body">
                            <strong>Billing Name:</strong> <?php echo $pmpro_invoice->billing->name; ?><br>
                            <strong>Street:</strong> <?php echo $pmpro_invoice->billing->street; ?><br>
                            <strong>City:</strong> <?php echo $pmpro_invoice->billing->city; ?><br>
                            <strong>State:</strong> <?php echo $pmpro_invoice->billing->state; ?><br>
                            <strong>State:</strong> <?php echo $pmpro_invoice->billing->state; ?><br>
                            <strong>Zipcode:</strong> <?php echo $pmpro_invoice->billing->zip; ?><br>
                            <strong>Country:</strong> <?php echo $pmpro_invoice->billing->country; ?><br>
                            <strong>Phone:</strong> <?php echo formatPhone($pmpro_invoice->billing->phone); ?><br>
                        </div>
                    </div>
                 <?php } ?>  
                </div>

               <div class="col-xs-12 col-md-3 col-lg-3">
                    <div class="panel panel-default height">
                       <?php if ( ! empty( $pmpro_invoice->accountnumber ) || ! empty( $pmpro_invoice->payment_type ) ) { ?>
                        <div class="panel-heading"><?php _e('Payment Information', 'paid-memberships-pro' );?></div>
                        <div class="panel-body">
						<?php if($pmpro_invoice->accountnumber) { ?>
                            <?php //if (!empty($pmpro_invoice->gateway)) { ?>
							<strong><?php _e('Payment Method:', 'paid-memberships-pro' );?></strong> <?php echo esc_html( ucwords( $pmpro_invoice->gateway ) ); ?><br/>
                            <?php //} ?>
                            <strong><?php _e('Transaction ID:', 'paid-memberships-pro' );?></strong> <?php echo esc_html( ucwords( $pmpro_invoice->payment_transaction_id ) ); ?><br/>
                            <strong><?php _e('Payment Type:', 'paid-memberships-pro' );?></strong> <?php echo esc_html( $pmpro_invoice->payment_type ); ?><br/>
                            <strong><?php _e('Card Name:', 'paid-memberships-pro' );?></strong> <?php echo esc_html( ucwords( $pmpro_invoice->cardtype ) ); ?><br/>
							 <strong><?php _e('Card Number:', 'paid-memberships-pro' );?></strong> <?php echo esc_html( last4($pmpro_invoice->accountnumber ) );?><br />
							<strong><?php _e('Expiration', 'paid-memberships-pro' );?>:</strong> <?php echo esc_html( $pmpro_invoice->expirationmonth );?>/<?php echo esc_html( $pmpro_invoice->expirationyear );?>
						<?php } else { ?>
							<p><?php echo esc_html( $pmpro_invoice->payment_type ); ?></p>
							<?php } ?>
                        </div>
						<?php } ?>
                    </div>
                </div>
                
                <div class="col-xs-12 col-md-3 col-lg-3 pull-right">
                    <div class="panel panel-default height">
                        <div class="panel-heading"><?php _e('Total Billed', 'paid-memberships-pro' );?></div>
                        <div class="panel-body">
                    	<?php
								if ( (float)$pmpro_invoice->total > 0 ) {
									echo pmpro_get_price_parts( $pmpro_invoice, 'span' );
								} else {
									echo pmpro_escape_price( pmpro_formatPrice(0) );
								}
							?>
						</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="text-center"><strong>Order summary</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <td><strong>Business Name</strong></td>
                                    <td class="text-center"><strong>Parent Corporate</strong></td>
                                    <td class="text-center"><strong>Business Owner</strong></td>
                                    <td class="text-center"><strong>Primary Contcat Name</strong></td>
                                    <td class="text-center"><strong>Mobile Directory</strong></td>
                                </tr>
							</thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $pmpro_invoice->business_name; ?></td>
                                    <td class="text-center"><?php echo $pmpro_invoice->parentcorporate;?></td>
                                    <td class="text-center"><?php echo $pmpro_invoice->businessowner;?> </td>
                                    <td class="text-center"><?php echo $pmpro_invoice->primarycontcatname;?>  </td>
                                   	<td class="text-center"><?php echo $pmpro_invoice->mobiledirectory;?> </td>
								</tr>
                            </tbody>
                        </table>
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                   <td class="text-center"><strong>Company Mission Statement</strong></td>
                                    <td class="text-center"><strong>Coaching Company</strong></td>
                                    <td class="text-center"><strong>Category Type Coach</strong></td>
                                    <td class="text-center"><strong>Coaching Specilities</strong></td>
                                    <td class="text-center"><strong>Year founded</strong></td>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                   <td class="text-center"><?php echo $pmpro_invoice->companymissionstatement;?> </td>
                                    <td class="text-center"><?php echo $pmpro_invoice->coachingcompany;?> </td>
                                    <td class="text-center"><?php echo $pmpro_invoice->categorytypecoach;?> </td>
                                    <td class="text-center"><?php echo $pmpro_invoice->coachingspecilities;?> </td>
                                    <td class="text-center"><?php echo $pmpro_invoice->yearfounded;?> </td>
                                    
                                </tr>
                                
                            </tbody>
                        </table>

                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <td class="text-center"><strong>Professional Associations</strong></td>
                                    <td class="text-center"><strong>What is your why</strong></td>
                                    <td class="text-center"><strong>Company Website</strong></td>
                                    <td class="text-center"><strong>Linkedin</strong></td>
                                    <td class="text-center"><strong>Facebook</strong></td>
                                    
                                </tr>
							</thead>
                            <tbody>
                                <tr>
                                   <td class="text-center"><?php echo $pmpro_invoice->professionalassociations;?> </td>
									<td class="text-center"><?php echo $pmpro_invoice->whatisyourwhy;?> </td>
									<td class="text-center"><?php echo $pmpro_invoice->companywebsite;?> </td>
									<td class="text-center"><?php echo $pmpro_invoice->sociallinkedin;?> </td>
									<td class="text-center"><?php echo $pmpro_invoice->socialfacebook;?> </td>
								</tr>
                                
                            </tbody>
                        </table>
                         <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <td class="text-center"><strong>Instagram</strong></td>
                                    <td class="text-center"><strong>Youtube</strong></td>
                                    <td class="text-center"><strong>Other</strong></td>
                                    <td class="text-center"><strong>Positive Review</strong></td>
                                    <td class="text-center"><strong>Title</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><?php echo $pmpro_invoice->socialinstagram;?> </td>
                                    <td class="text-center"><?php echo $pmpro_invoice->socialyoutube;?> </td>
                                    <td class="text-center"><?php echo $pmpro_invoice->socialother;?> </td>
                                    <td class="text-center"><?php echo $pmpro_invoice->positivereview;?> </td>
                                    <td class="text-center"><?php echo $pmpro_invoice->bustitle;?> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







	
	
	<!-- <hr />
	<div class="<?php echo pmpro_get_element_class( 'pmpro_invoice_details' ); ?>">
		
			<div class="<?php echo pmpro_get_element_class( 'pmpro_invoice-business_name' ); ?>">
				<strong><?php _e('Coach Details', 'paid-memberships-pro' );?></strong>
				<p>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-business_name' ); ?>"><?php echo $pmpro_invoice->business_name; ?></span>

					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-parentcorporate'); ?>"><?php echo $pmpro_invoice->parentcorporate;?> </span>

					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-businessowner'); ?>"><?php echo $pmpro_invoice->businessowner;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-primarycontcatname'); ?>"><?php echo $pmpro_invoice->primarycontcatname;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-mobiledirectory'); ?>"><?php echo $pmpro_invoice->mobiledirectory;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-companymissionstatement'); ?>"><?php echo $pmpro_invoice->companymissionstatement;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-coachingcompany'); ?>"><?php echo $pmpro_invoice->coachingcompany;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-categorytypecoach'); ?>"><?php echo $pmpro_invoice->categorytypecoach;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-coachingspecilities'); ?>"><?php echo $pmpro_invoice->coachingspecilities;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-yearfounded'); ?>"><?php echo $pmpro_invoice->yearfounded;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-professionalassociations'); ?>"><?php echo $pmpro_invoice->professionalassociations;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-whatisyourwhy'); ?>"><?php echo $pmpro_invoice->whatisyourwhy;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-companywebsite'); ?>"><?php echo $pmpro_invoice->companywebsite;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-sociallinkedin'); ?>"><?php echo $pmpro_invoice->sociallinkedin;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-socialfacebook'); ?>"><?php echo $pmpro_invoice->socialfacebook;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-socialinstagram'); ?>"><?php echo $pmpro_invoice->socialinstagram;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-socialyoutube'); ?>"><?php echo $pmpro_invoice->socialyoutube;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-socialother'); ?>"><?php echo $pmpro_invoice->socialother;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-positivereview'); ?>"><?php echo $pmpro_invoice->positivereview;?> </span>
					<span class="<?php echo pmpro_get_element_class( 'pmpro_invoice-field-bustitle'); ?>"><?php echo $pmpro_invoice->bustitle;?> </span>
			</p>
			</div>end pmpro_invoice-billing-address 
		

	<hr />

	<hr /> -->
<?php
	}
	else
	{
		$confirmation_message .= "<p>" . sprintf(__('Below are details about your membership account. A welcome email has been sent to %s.', 'paid-memberships-pro' ), $current_user->user_email) . "</p>";

		/**
		 * All devs to filter the confirmation message.
		 * Documented above.
		 * We also have a function in includes/filters.php that applies the the_content filters to this message.
		 */
		$confirmation_message = apply_filters("pmpro_confirmation_message", $confirmation_message, false);

		echo wp_kses_post( $confirmation_message );
	?>
	<ul>
		<li><strong><?php _e('Account', 'paid-memberships-pro' );?>:</strong> <?php echo esc_html( $current_user->display_name );?> (<?php echo esc_html( $current_user->user_email );?>)</li>
		<li><strong><?php _e('Membership Level', 'paid-memberships-pro' );?>:</strong> <?php if(!empty($current_user->membership_level)) echo esc_html( $current_user->membership_level->name ); else _e("Pending", 'paid-memberships-pro' );?></li>
	</ul>
<?php
	}
?>
<p class="<?php echo pmpro_get_element_class( 'pmpro_actions_nav' ); ?>">
	<?php if ( ! empty( $current_user->membership_level ) ) { ?>
		<a href="<?php echo pmpro_url( 'account' ); ?>"><?php _e( 'View Your Membership Account &rarr;', 'paid-memberships-pro' ); ?></a>
	<?php } else { ?>
		<?php _e( 'If your account is not activated within a few minutes, please contact the site owner.', 'paid-memberships-pro' ); ?>
	<?php } ?>
</p> <!-- end pmpro_actions_nav -->
</div> <!-- end pmpro_confirmation_wrap -->


<style>
.height {
    min-height: 200px;
}

.icon {
    font-size: 47px;
    color: #5CB85C;
}

.iconbig {
    font-size: 77px;
    color: #5CB85C;
}

.table > tbody > tr > .emptyrow {
    border-top: none;
}

.table > thead > tr > .emptyrow {
    border-bottom: none;
}

.table > tbody > tr > .highrow {
    border-top: 3px solid;
}
</style>
