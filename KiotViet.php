<?php

/**
 * Huynh Mai Anh Kiet
 * HuynhMaiAnhKiet@Gmail.Com
 * (+84)0905567654
 * https://me.anhkiet.info
 */
 
	/**
	 * Generate KiotViet token key
	 * @var string $client_id
	 * @var string $secret_key
	 * @return string - KiotViet token key
	 */	 
	public function get_kiotviet_token($client_id,$secret_key){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://id.kiotviet.vn/connect/token",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "scopes=PublicApi.Access&grant_type=client_credentials&client_id=".$client_id."&client_secret=".$secret_key,
		  CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"content-type: application/x-www-form-urlencoded"
		  ),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
		  return false;
		} else {
		  $response = json_decode($response,true);
		  if( isset($response['access_token']) ){
			return $response['token_type']." ".$response['access_token'];
		  }
			return false;
		}
	}
	
	/**
	 * Calling KiotViet API
	 * @var string $retailer
	 * @var string $token
	 * @var string $api_endpoint
	 * @var array $query
	 * @var string $method (Values: GET, POST, PUT, DELETE)
	 * @return array -  KiotViet's Object
	 */
	public function kiotviet_call($retailer,$token,$api_endpoint,$query=array(),$method='GET'){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://public.kiotapi.com".$api_endpoint);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_ENCODING, '');
		curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);		
		if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
			if (is_array($query)) $query = json_encode($query);
			curl_setopt ($curl, CURLOPT_POSTFIELDS, $query);
		}
		$header = array(
			"authorization: ".$token,
			"cache-control: no-cache",
			"content-type: application/json",
			"retailer: ".$retailer
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		$response = curl_exec($curl);		
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			return false;
		} else {
			return $response;
		}
	} 
	
?>
