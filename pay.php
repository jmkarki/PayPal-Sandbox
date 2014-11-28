<?php 
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

require '../src/start.php';
if(isset($_GET['approved'])){
	$approved = $_GET['approved'] === 'true';
	if($approved){
		$payerId = $_GET['payerID'];
		//Get payment id form database
		$paymentId = $db->prepare("
			SELECT payment_id
			FROM transaction_paypal
			WHERE hash = :hash
			");
		$paymentId->execute(['hash'=> $_SESSION['paypal_has']
			]);
		$paymentId = $paymentId->fetchObject()->payment_id;

		$payment = Payment::get($paymentId, $api);
		$execution = new PaymentExecution();
		$execution->setPayerId($payerId);
		//Executep PayPal (charge)
		$payment->execute($execution, $api);
		$updateTransaction = $db->prepare("
			UPDATE transaction_paypal
			SET complete = 1
			WHERE payment_id = :payment_id");
		$updateTransaction->execute([
			'payment_id'=> $paymentId]
			);
		//Set the user as a member
		$setMember = $db->prepare("
			UPDATE users
			SET member = 1
			WHERE id = :user_id
			");
		$setMember->execute([
			'user_id'=> $_SESSION['user_id']
			]);
		//Unset PayPal hash
		unset($_SESSION['paypal_hash']);
		header('Location:success.php');

	}else{
		header('Location:../paypal/cancel.php');
	}
}