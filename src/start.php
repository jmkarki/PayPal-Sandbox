<?php 
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payment;

session_start();

$_SESSION['user_id'] = 1;

require __DIR__.'/../vendor/autoload.php';

//API
$api = new ApiContext(new OAuthTokenCredential(
			'AXKlYhC80o5JGneIcL1m9ZgGb94bSM0ejqLrYUUlY2qzDGtWdZdIcu_csjME',
			 'EDc92hBefDAZeNQxCTkkWH_f9aw59mbgzfjZmejEZnYmlDg-6p4nCcVPbGIP')
		);

$api->setConfig([
	'mode'=> 'sandbox',
	'http.ConnectionTimeout' => 30,
	'log.LogEnabled' => false,
	'validation.level' => 'log',
	]);
$db = new PDO('mysql:host=localhost;dbname=paypal','root','');
$user = $db->prepare("
	SELECT * FROM users
	WHERE id = :user_id");
$user->execute(['user_id'=> $_SESSION['user_id']]);
$user = $user->fetchObject();
 ?>