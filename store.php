<?php
	require_once "config.php";
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$company_name= $_POST['companyName'];
		$productSupplied= $_POST['productSupplied'];
		$account_number= $_POST['accountNumber'];
		$bank_code = $_POST['bank_code'];
		$amount = intval($_POST['amount']) * 100;
		var_dump ($company_name,$productSupplied,$account_number, $bank_code);
		if (empty($company_name) || empty($productSupplied) || empty($account_number) || empty($bank_code)|| empty($amount))
		{
			echo "Fill all fields";
		}
		else
		{
			// Prepare an insert statement
			$sql = "INSERT INTO vendor (company_name, product_supplied, account_number, bank_code) VALUES (?, ?, ?, ?)";
			
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "ssss", $param_companyname, $param_productsupplied, $param_accountnumber, $param_bank_code);
				
				// Set parameters
				$param_companyname = $company_name;
				$param_productsupplied = $productSupplied;
				$param_accountnumber = $account_number;
				$param_bank_code = $bank_code;

				if(mysqli_stmt_execute($stmt)){
					// Redirect to login page
					echo "successfull";
					// Close statement
					mysqli_stmt_close($stmt);
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
				} else{
					echo "Something went wrong. Please try again later.";
				}

?>