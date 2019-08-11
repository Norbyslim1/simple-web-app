<?php
    require_once "config.php";
    session_start();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $amount = intval($_POST['amount']) * 100;

        $sql = "SELECT company_name, account_number, bank_code FROM vendor WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_id);
            // Set parameters
            $param_id = $_POST['company_id'];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $company_name, $account_number, $bank_code);
                    if(mysqli_stmt_fetch($stmt)){;}


                    $curl = curl_init();
            
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://api.paystack.co/transferrecipient",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_POSTFIELDS => "account_number=$account_number&bank_code=$bank_code&name=$company_name",
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
                            $recipient_code = $result['data']['recipient_code'];

                            $curl2 = curl_init();
            
                            curl_setopt_array($curl2, array(
                            CURLOPT_URL => "https://api.paystack.co/transfer",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => "recipient=$recipient_code&amount=$amount",
                            CURLOPT_HTTPHEADER => array(
                                "Accept: */*",
                                "Authorization: Bearer sk_test_9a111cc8bc8d7d24f6b218bd682845d8c2236f36",
                                "Cache-Control: no-cache",
                                "Connection: keep-alive",
                                
                            ),
                            ));
                            
                            $response2 = curl_exec($curl2);
                            $err2 = curl_error($curl2);
                            
                            curl_close($curl2);
                            
                            if ($err2) {
                            echo "cURL Error #:" . $err;
                            } else {
                                $result2 = json_decode($response2, true);
                                if($result2['status'] == 1)
                                {
                                    $transfer_code = $result2['data']['transfer_code'];
                                    $_SESSION["paystack_transfer_code"] = $transfer_code;

                                    header("location: token.php");
                                }
                                else
                                {
                                    echo 'transaaction failed';
                                }                  
                            
                            }
                        }
                        else
                        {
                            echo 'transaaction failed';
                        }                  
                      
                    }
                    
                } else{
                    echo "Error Company not found";
                    exit;
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
                header("location: welcome.php");
                exit;
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    
        // Close connection
        mysqli_close($link);
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Simple Web App</title>
	</head>
    <link rel="stylesheet" type="text/css" href="style.css">
	<body>
		<form action= '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method = 'post'>
            
        <div class="field">
            <label>Company name</label>
            <select name="company_id">
			<?php
				$sql = "select id, company_name from vendor";
				$result = $link->query($sql);
				if($result->num_rows > 0)
				{
					while($row = $result->fetch_assoc())
					{
						echo '<option value="'.$row['id'].'">'.$row['company_name'].'</option>';
					}
				}
				$link->close();
            ?>
            </select>
        </div>
        <div class='field'>
				<label> Amount</label>
				<input name= 'amount' type='number' min="0" placeholder='Enter amount' required/>
			</div>
        <button type="submit">Pay</button>
        
        </form>
    </body>
</html>