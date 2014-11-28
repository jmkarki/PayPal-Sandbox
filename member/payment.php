<?php 
use PayPal\Api\Payer;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Exception\PPConnectionException;


require '../src/start.php';

$payer = new Payer();
$details = new Details();
$amount = new Amount();
$transaction = new Transaction();
$payment = new Payment();
$redirectUrls = new RedirectUrls();
$payer->setPayment_method('paypal');
 //details
$details->setShipping('2.00')
		->setTax('0.00')
		->setSubtotal('20.00');
//amount
$amount->setCurrency('GBP')
		->setTotal('22.00')
		->setDetails($details);
//transaction
$transaction->setAmount($amount)
				->setDescription('Membership');
$payment->setIntent('sale')
		->setPayer($payer)
		->setTransactions([$transaction]);
//redirct urls
	$redirectUrls->setReturnUrl('http://localhost/Paypal/pay.php?approved=true')
				->setCancelUrl('http://localhost/Paypal/pay.php?approved=false');
$payment->setRedirectUrls($redirectUrls);
 try{
 	$payment->create($api);
 	//Generate and store has
 	$hash = md5($payment->getId());
 	$_SESSION['paypal_hash'] = $hash;
 	//Prepare and store the trans storage
 	$store = $db->prepare("
 		INSERT INTO transaction_paypal(user_id, payment_id,hash, complete) 
 		VALUES(:user_id,:payment_id,:hash,0)"
 		);
 	$store->execute([
 		'user_id'=> $_SESSION['user_id'],
 		'payment_id' => $payment->getId(),
 		'hash'=> $hash
 		]);




 }catch(PPConnectionException $e){
 	//Perhaps log an error
 	header('Location:../error.php');
 }
foreach ($payment->getLinks() as $link) {
	if($link->getRel() == 'approval_url'){
		$redirctUrl = $link->getHref();
	}
}

header('Location:'.$redirctUrl);








