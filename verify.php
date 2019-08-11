<?php
    require_once "config.php";

    session_start();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $bank_code= $_POST['bank_code'];
        $account_number= $_POST['account_number'];

		if (empty($bank_code) || empty($account_number))
		{
			echo "Fill all fields";
        }
        else
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number=$account_number&bank_code=$bank_code",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer sk_test_9a111cc8bc8d7d24f6b218bd682845d8c2236f36",
                "Cache-Control: no-cache"),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
            echo "cURL Error #:" . $err;
            } else {
                $result = json_decode($response, true);
                if($result['status'] == 1)
                {
                    $_SESSION["paystack_account_number"] = $result['data']['account_number'];
                    $_SESSION["paystack_account_name"] = $result['data']['account_name'];
                    $_SESSION["paystack_bank_code"] = $bank_code;


                    header("location: trial.php");
                }
                else
                {
                    echo 'invalid account details';
                }
            }
        }
    }
?>

<!doctype html>
<html>
    <head>
        <title>Verify Account Details</title>
         
    </head>
    <body>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="field">
                <label>Bank Name</label>
                <select name="bank_code">
				<?php
					$sql = "select code, name from banks";
					$result = $link->query($sql);
					if($result->num_rows > 0)
					{
						while($row = $result->fetch_assoc())
						{
							echo '<option value="'.$row['code'].'">'.$row['name'].'</option>';
						}
					}
					$link->close();
				?>
				</select>
            </div>
            <div class="field">
                <label>Account Number</label>
                <input type="text" name="account_number" required />
            </div>

            <button type="submit">Verify</button>
        </form>
    </body>
</html>