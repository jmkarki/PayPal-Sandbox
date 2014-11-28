<?php 
require 'src/start.php';



 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Document</title>
</head>
<body>
<?php if($user->member):?>
	 <p>You are a member</p>
<?php else: ?>
 <p>You are not a member.</p> <a href="member/payment.php">Become a member</a>
<?php endif; ?>
</body>
</html>