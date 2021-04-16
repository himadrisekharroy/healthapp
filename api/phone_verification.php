<?php

class PhoneVerification {
	const base     = "http://2factor.in/API/V1/";
	const api_key  = "8fde3df6-7cc0-11ea-9fa5-0200cd936042";
	private $curl;

	public function __construct(){
		$this->curl = curl_init();
	}

	/**
	 * send OTP to $phone_number
	 * returns json decoded response of shape {"Status":"Success","Details":"68bbe094-312d-497c-b462-4b107adf39e2"}
	 * value of the details field serves as the session id
	 */
	public function send($phone_number){		
		curl_setopt_array($this->curl, array(
		  CURLOPT_URL => PhoneVerification::base . PhoneVerification::api_key . "/SMS/$phone_number/AUTOGEN/login_otp",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "",
		  CURLOPT_HTTPHEADER => array(
		    "content-type: application/x-www-form-urlencoded"
		  ),
		));

		$response = curl_exec($this->curl);
		$err = curl_error($this->curl);

		curl_close($this->curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  $decoded = json_decode($response);
		  return $decoded;
		}
	}
	
	/**
	 * verifies a given $otp with a $session_id
	 * return json decoded response
	 */
	public function verify($session_id, $otp){
		curl_setopt_array($this->curl, array(
		  CURLOPT_URL => PhoneVerification::base . PhoneVerification::api_key . "/SMS/VERIFY/$session_id/$otp",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "",
		  CURLOPT_HTTPHEADER => array(
		    "content-type: application/x-www-form-urlencoded"
		  ),
		));

		$response = curl_exec($this->curl);
		$err = curl_error($this->curl);

		curl_close($this->curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  $decoded = json_decode($response);
		  return $decoded;
		}
	}
}
