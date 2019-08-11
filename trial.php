<?php
	require_once "config.php";

	session_start();

?>

<!DOCTYPE html>
<html>
	<head>
		<title> Simple Web App</title>
	</head>
	<link rel="stylesheet" type="text/css" href="style.css"> 
	<body>
		<form action= 'store.php' method = 'post'>
			<input type="hidden" name="bank_code" value="<?php echo $_SESSION["paystack_bank_code"] ?>" />
			<div class='field'>
				<label> Company Name</label>
				<input name= 'companyName' type='text' placeholder='Enter company name' required/>
			</div>
			<div class='field'>
				<label> Product Supplied</label>
				<input name= 'productSupplied' type='text' placeholder='Enter product name' required/>
			</div>
			<div class='field'>
				<label> Account Number</label>
				<input name= 'accountNumber' type='text' value="<?php echo $_SESSION["paystack_account_number"] ?>" placeholder='Enter account number' readonly required/>
			</div>
			<div class='field'>
				<label> Account Name</label>
				<input type='text' placeholder='Enter account name' value="<?php echo $_SESSION["paystack_account_name"] ?>" required disabled/>
			</div>
			<div class='field'>
				<label> Amount</label>
				<input name= 'amount' type='number' min="0" placeholder='Enter amount' required/>
			</div>
			<button type='submit'>pay and register vendor</button>
		</form>
	</body>
</html>