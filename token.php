<?php
    require_once "config.php";

    session_start();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $transfer_code= $_POST['transfer_code'];
        $otp_code= $_POST['otp_code'];


		if (empty($otp_code))
		{
			echo "Enter OTP";
        }
        else
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.paystack.co/transfer/finalize_transfer",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "transfer_code=$transfer_code&otp=$otp_code",
                CURLOPT_HTTPHEADER => array(
                  "Accept: */*",
                  "Authorization: Bearer sk_test_9a111cc8bc8d7d24f6b218bd682845d8c2236f36",
                  "Cache-Control: no-cache",
                  "Connection: keep-alive",
                  
                ),
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
                    echo "Transaction Successfull\n";
                    echo "Thank you";
                    header("location: welcome.php");
                    } else{ echo "wrong otp entered";}
                }
        }
    }
?>

<!doctype html>
<html>
    <head>
        <title>Enter OTP Code</title>
    </head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <body>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="transfer_code" value="<?php echo $_SESSION["paystack_transfer_code"] ?>" />
            <div class="field">
                <label>OTP Code</label>
                <input name="otp_code" type="text" name="Enter OTP" required />
            </div>

            <button type="submit">Confirm</button>
        </form>
    </body>
</html>