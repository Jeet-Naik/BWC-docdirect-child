<?php

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



/*$payments_api = new PaymentsApi($api_client);
$request_body = array (
  "source_id" => $nonce,
  "amount_money" => array (
    "amount" => 100,
    "currency" => "USD"
  ),
  "idempotency_key" => uniqid()
);
try {
  $result = $payments_api->createPayment($request_body);
  echo "<pre>";
  print_r($result);
  echo "</pre>";
} catch (ApiException $e) {
  echo "Caught exception!<br/>";
  print_r("<strong>Response body:</strong><br/>");
  echo "<pre>"; var_dump($e->getResponseBody()); echo "</pre>";
  echo "<br/><strong>Response headers:</strong><br/>";
  echo "<pre>"; var_dump($e->getResponseHeaders()); echo "</pre>";
}
*/


/*$access_token = 'sandbox-sq0idb-f1n_uUTE9qwHTmYUYdhtUg';
# setup authorization
\SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($access_token);
# create an instance of the Transaction API class
$transactions_api = new \SquareConnect\Api\TransactionsApi();
$location_id = 'L7DS9V344P92B';
$nonce = $_POST['nonce'];

$request_body = array (
    "card_nonce" => $nonce,
    # Monetary amounts are specified in the smallest unit of the applicable currency.
    # This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.
    "amount_money" => array (
        "amount" => (int) $_POST['amount'],
        "currency" => "USD"
    ),
    # Every payment you process with the SDK must have a unique idempotency key.
    # If you're unsure whether a particular payment succeeded, you can reattempt
    # it with the same idempotency key without worrying about double charging
    # the buyer.
    "idempotency_key" => uniqid()
);

try {
    $result = $transactions_api->charge($location_id,  $request_body);
    // print_r($result);
  
  // echo '';
  if($result['transaction']['id']){
    echo 'Payment success!';
    echo "Transation ID: ".$result['transaction']['id']."";
  }
} catch (\SquareConnect\ApiException $e) {
    echo "Exception when calling TransactionApi->charge:";
    var_dump($e->getResponseBody());
}*/
?>