<?php


class GcmController {


	public static function sendGcmUserUpdate( $users ) {
		$receivers = [];
		$usersArray = json_decode($users,true);
		foreach (json_decode($users,true) as $user) {
			$receivers[] = $user["user_gcm_id"];
		}

		GcmController::sendGCM($receivers,"1",$users);
	}

	public static function sendGcmPrivateMessage(/* ..... */) {
		/*
		 * 
		 */
	}

	public static function sendGCM( $receivers, $messageType, $message) {

		//loop over users to get the receiving ID of each

		$apiKey = "AIzaSyCDcjNLaOHSmIzcugv2QzPSXJ0kW0N1Ld8";
		$url = "https://gcm-http.googleapis.com/gcm/send";

		$headers = [];
		$headers[] = "Content-Type:application/json";
		$headers[] = "Authorization:key=" . $apiKey;

		$gcmMessage = [];
		$gcmMessage["registration_ids"] = $receivers;
		$gcmMessage["data"] = ["type" => $messageType , "message" => $message];		


		$cURL = curl_init($url);
		curl_setopt($cURL,CURLOPT_HTTPHEADER,$headers);
		curl_setopt($cURL,CURLOPT_POSTFIELDS,json_encode($gcmMessage));
		curl_setopt($cURL,CURLOPT_RETURNTRANSFER,true);
		error_log(curl_exec($cURL));

	}


}