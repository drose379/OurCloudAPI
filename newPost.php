 <?php

require_once 'connect.php';

class newPost {

	/**
	 * Need to implement new users table to creating new posts
	 * When new post is uploaded with user display name, check if display name exists in users table
	 * If YES, grab the users unique id and save that with the post (instead of all of users info)
	 * If NO, create a new user record, and return the users new unique id to be saved
	 */

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$userId = $post[0];
		$user = $post[1];
		$userPhoto = $this->formatUrl($post[2]);
		$zone = $post[3];
		$postText = $post[4];

		if ($this->userExists($userId)) {
			//insert into zone_posts
			$this->insert($userId,$zone,$postText);
		} else {
			$this->createUser($user,$userPhoto);
			$this->insert($userId,$zone,$postText);
		}

	}

	/**
	 * Used to format url to change photo size to 65 instead of 50
	 * Google API returns a url of photo and sz=X at the end of URL for image size
	 * Grab everything after = and change it to correct size
	 */
	public function formatUrl($imageUrl) {
		$urlArray = preg_split("/\=/",$imageUrl);
		$urlArray[1] = "=65";
		return implode($urlArray);
	}

	public function userExists($userId) {
		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT user_id FROM users WHERE user_id = :userId");
		$stmt->bindParam(':userId',$userId);
		$stmt->execute();
		$result = $stmt->fetch();

		$exists = $result[0] == null ? false : true;

		return $exists;
	}

	public function createUser($userId,$userName,$userPhoto) {
		$con = DBConnect::get();
		$stmt = $con->preapre("INSERT INTO users (user_id,user_name,user_image) VALUES (:id,:name,:photo)");
		$stmt->bindParam(':id',$userId);
		$stmt->bindParam(':name',$userName);
		$stmt->bindParam(':photo',$userPhoto);
		$stmt->execute();
	}

	public function insert($userId,$zone,$post) {
		$con  = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user_id,zone,postText) VALUES (:user_id,:zone,:postText)");
		$stmt->bindParam(':user_id',$userId);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postText',$post);
		$stmt->execute();
	}

}