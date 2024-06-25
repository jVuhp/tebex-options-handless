<?php
$json = file_get_contents('php://input');

if(empty($json)) {
    http_response_code(400);
    die("Fatal error.");
}

$data = json_decode($json, true);

if($data['type'] === 'validation.webhook') {
	require_once('../../config.php');
	$secret = TEBEX_WEBHOOK_SECRET;
	$calculated_signature = hash_hmac('sha256', $json, $secret);
	$signature = $_SERVER['HTTP_X_SIGNATURE'];
	
	echo $json;
} else if($data['type'] === 'payment.completed') {
	require_once('../../config.php');
	$pay_type = explode('.', $data['type']);
	$transaction_id = $data['subject']['transaction_id'];
	$status = $data['subject']['status']['id'];
	$price = $data['subject']['price']['amount'];
	$payment_method = $data['subject']['payment_method']['name'];
	$user_custom = $data['subject']['custom']['user_id'];
	
	$basketSQL = $connx->prepare("SELECT * FROM `$dbb_basket` WHERE `user` = ?;");
	$basketSQL->execute([$user_custom]);
	if ($basketSQL->RowCount() > 0) {
		$basket = $basketSQL->fetch(PDO::FETCH_ASSOC);
		
		// Once the payment is completed, the trans-id will be sent to you and you can send it to your site through mysql or any request.
	
	}
		$products = $data['subject']['products'];
		foreach ($products as $product) {
			// Action to place all products separately.
      // They are the products purchased. If you add products in the same checkout, they will be sent and you receive it.
		}
	// Create the files in the current directory (You can use it as an example or a second way of saving.)
    $file_name = 'payment_info_' . time() . '.txt';
    $file_path = __DIR__ . '/../tebex_payments/' . $file_name;
    $file_content = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($file_path, $file_content);

    http_response_code(200);
	echo $json;
} else {
    http_response_code(400);
    echo "Error.";
}
?>
