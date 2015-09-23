<?php


class GcmController {

	public static function sendGCM( $receivers, $messageType, $message) {
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
		curl_setopt($cURL,CURLOPT_POSTFIELDS,json_encode($testData));
		curl_setopt($cURL,CURLOPT_RETURNTRANSFER,true);
		echo curl_exec($cURL);

	}


}