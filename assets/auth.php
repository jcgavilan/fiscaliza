<?php
/*
function getToken(){
    $curl = curl_init();
   
    $params = array(
      CURLOPT_URL =>  ACCESS_TOKEN_URL."?"
                      ."code=".$code
                      ."&grant_type=authorization_code"
                      ."&client_id=". CLIENT_ID
                      ."&client_secret=". CLIENT_SECRET
                      ."&redirect_uri=". CALLBACK_URL,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_NOBODY => false, 
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded",
        "accept: *",
        "accept-encoding: gzip, deflate",
      ),
    );
   
    curl_setopt_array($curl, $params);
   
    $response = curl_exec($curl);
    $err = curl_error($curl);
   
    curl_close($curl);
   
    if ($err) {
      echo "cURL Error #01: " . $err;
    } else {
      $response = json_decode($response, true);    
      if(array_key_exists("access_token", $response)) return $response;
      if(array_key_exists("error", $response)) echo $response["error_description"];
      echo "cURL Error #02: Something went wrong! Please contact admin.";
    }
  }
  */
  
define("RESPONSE_TYPE", "code");
define("CLIENT_ID", "jU6WCq3_zAumegDwjH_J2hWQzSEa");
define("CLIENT_SECRET", "PxaztPR63rA5ioT9LBRur1MM1VIa");
define("REDIRECT_URI", "https://sistemas.pc.sc.gov.br/cidadao/retorna.sea.php");
define("AUTH_URL", "https://acesso.ciasc.sc.gov.br/oauth2/authorize");
define("CALLBACK_URL", "https://sistemas.pc.sc.gov.br/cidadao/retorna.sea.php");

define("ACCESS_TOKEN_URL", "https://acesso.ciasc.sc.gov.br/oauth2/token");
define("SCOPE", "openid"); 

$url = AUTH_URL."?"
   ."response_type=code"
   ."&client_id=". urlencode(CLIENT_ID)
   ."&scope=". urlencode(SCOPE)
   ."&redirect_uri=". (CALLBACK_URL) //urlencode(CALLBACK_URL)
;

echo $url;

?>