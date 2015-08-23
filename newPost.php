 <?php

require_once 'connect.php';

class newPost {

	/**
	 * Need to implement new users table to creating new posts
	 * When new post is uploaded with user display name, check if display name exists in users table
	 * If YES, grab the users unique id and save that with the post (instead of all of users info)
	 * If NO, create a new user record, and return the users new unique id to be saved
	 */

	// WHEN IN_RANGE ARRAY COMES, NEED TO JSON_DECODE THAT FROM THE JSON_DECODED MASTER ARRAY.
	//Save array as string to in_range column. keep it in json format.
	//Look for zone name in the in_range array, remove it from the array

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$userId = $post[0];
		$zone = $post[1];
		$postText = $post[2];
		$timeMillis = $post[3];
		$expDateMillis = $post[4];

		if($expTimeMillis > 0) {
			$this->insertWithExp($userId,$zone,$postText,$timeMillis,$expDateMillis);
		} else {
			$this->insertWithoutExp($userId,$zone,$postText,$timeMillis);
		}

		
	}

	public function insertWithExp($userId,$zone,$post,$postTime,$expDate) {
		$con  = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user_id,zone,postText,postTime,expDate) VALUES (:user_id,:zone,:postText,:time,:expDate)");
		$stmt->bindParam(':user_id',$userId);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postText',$post);
		$stmt->bindParam(':time',$postTime);
		$stmt->bindParam(':expDate',$expDate);
		$stmt->execute();
	}

	public function insertWithoutExp($userId,$zone,$post,$postTime) {
		$con  = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user_id,zone,postText,postTime) VALUES (:user_id,:zone,:postText,:time)");
		$stmt->bindParam(':user_id',$userId);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postText',$post);
		$stmt->bindParam(':time',$postTime);
		$stmt->execute();
	}

}