<?php
date_default_timezone_set("GMT");
$black="\e[0;30m";$darkgrey="\e[1;30m";$red="\e[0;31m";$lightred="\e[1;31m";$green="\e[0;32m";$lightgreen="\e[1;32m";$brown="\e[0;33m";$yellow="\e[1;33m";$blue="\e[0;34m";$lightblue="\e[1;34m";$magenta="\e[0;35m";$lightmagenta="\e[1;35m";$cyan="\e[0;36m";$lightcyan="\e[1;36m";$lightgrey="\e[0;37m";$white="\e[1;37m";$end="\e[0m";
$i =0;
while(true){
	$i++;
	$get = Request("get", null);
	if(isset($get) && $get !== null){
		$data = json_encode(array("total_clicks" => $get["total_clicks"]+(rand(0,5)), "renovation_time" => $get["renovation_time"], "clicks_since_time" => $get["total_clicks"]+(rand(0,5)), "start_click_time" => $get["start_click_time"]));
		$re = Request("updateV3", $data);
		if(isset($re["captcha_required"]) && $re["captcha_required"] == "true"){
			echo $yellow."[".$i."]".$end.$lightmagenta."[".date("Y-m-d H:i:s")."]".$end.$lightblue." # ".$end.$lightred."Captcha Detected....!!!".$end."\n";
			$cap = json_encode(["captcha_token" => "03AOLTBLQ26LN3GYcLIANHhWcZ6Lwn8G19wkueWHKCwbMwXbGJJTLGa5yZ_9BGKPvY8GKCe9Vu4g7vay9PKSYxghhBJJVUHeWUh8NM2GXOE5jKZ57rh7N4uyM4DrGaMv8a9fAOB_V4RK-X19OIcdm2jpCNSrRArzICBMG_4VxcLldqCM7UOWTmv90eWt1KoMocLGTYljWMIu5L6zpt9RzMYq0Ly3PQLQ_hHvW4Xizc9Zw9fRKK7fathil6ZoxjMcPzutJ8aqufnBdnqvhavpD2Gvr0Y404BETcVEMvTbDokKrK-NwdW6QzZvw"]);
			$ca = Request("verifyCaptcha" , $cap);
			if(isset($ca["result"]) && $ca["result"]){
				echo $yellow."[".$i."]".$end.$lightmagenta."[".date("Y-m-d H:i:s")."]".$end.$lightblue." # ".$end.$lightgreen."Bypass Captcha Success....!!!".$end."\n";
			}
		}else{
			if(isset($re["result"]) && $re["result"] == "success"){
				echo $yellow."[".$i."]".$end.$lightmagenta."[".date("Y-m-d H:i:s")."]".$end.$lightblue." # ".$end.$lightgreen."Success....!!! ".$end."\n";
			}
		}
	}
	//sleep(10);
}
function Request($url, $post=null){
	$config = json_decode(file_get_contents("config_bchbutton.json"), true);
	if(isset($config["bearer"], $config["user_agent"]) && $config){
		$bearer = $config["bearer"];
		$agent = $config["user_agent"];
	}else{
		exit;
	}
	$ch = curl_init();
	if($url == "verifyCaptcha"){
		curl_setopt($ch, CURLOPT_URL, "http://clientapi.btcbutton.datatask.net/api/".$url);
	}else{
		curl_setopt($ch, CURLOPT_URL, "http://clientapi.btcbutton.datatask.net/api/user/".$url);
	}
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	if($post !==null){
       curl_setopt($ch, CURLOPT_POST, 1);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer ".$bearer, "Connection: Keep-Alive", "Accept-Encoding: gzip", "Content-Length: ".strlen($post)));
	}else{
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer ".$bearer, "Connection: Keep-Alive", "Accept-Encoding: gzip"));
	}
    $get["exec"] = json_decode(curl_exec($ch), true);
    if(!curl_errno($ch)){
        $get["status"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
   	 $get["infourl"] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);  
	}
    //curl_close($ch);
    return $get["exec"];
}