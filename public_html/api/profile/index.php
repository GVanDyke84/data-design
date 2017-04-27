<?php


require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DataDesign\ {
			Profile
};


/**
 * API for Profile
 *
 * @author Gerrit Van Dyke
 * @version 1.0
 */

//verify the session, if it isn't active then start it

if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab mySQL connection
	$pdo = connectToEncryptedMySQL("insert path");

	//determine HTTP method used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$profileEmail = filter_input(INPUT_GET, "profileEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileAvatar = filter_input(INPUT_GET, "profileAvatar", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//ensure the id is valid for the methods that require it

	if((method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id can;t be negative or empty", 405));

	}
	if($method === "GET") {
		//XSRF cookie
		setXsrfCookie();

		//gets a post by content
		if(empty($id) === false) {
			$profile = Profile::getProfileByProfileId($pdo, $id);

			if($profile !== null) {
				$reply->data = $profile;
			}
		} else if(empty($profileEmail) === false) {
			$profile = Profile::getProfileByProfileEmail($pdo, $profileEmail);
			if($profile !== null) {
				$reply->data = $profile;
			}
		} else if(empty($profileAvatar) === false) {
			$profile = Profile::getProfileByProfileAvatar($pdo, $profileAvatar);
			if($profile !== null) {
				$reply->data = profile;
			}
		}
	} elseif($method === "PUT") {

		//ensure the user is signed in and trying to edit only their own profile
		if(empty($_SESSION["profile"]) === true || $_SESSION{"profile"}->getProfileId() !== $id) {
			throw(new \InvalidArgumentException("You are not allowed to access this profile", 403));
		}
		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//retrieve profile to be updated

		$profile = Profile::getProfileByProfileId($pdo, $id);
		if($profile === null) {
			throw(new RuntimeException("Profile does not exist", 404));
		}

		if(empty($requestObject->newPassword) === true) ;
		{

			//enforce that XSRF token is present in the header
			verifyXsrf();

			//profile email is required
			if(empty($requestObject->profileEmail) === true) {
				throw(new \InvalidArgumentException("No profile email present", 405));

				//profile Avatar
				if(empty($requestObject->profileAvatar) === true) {
					throw(new \InvalidArgumentException("No profile avatar present", 405));

				}

				$profile->setProfileEmail($requestObject->profileEmail);
				$profile->setProfileAvatar($requestObject - profileAvatar);
				$profile->update($pdo);

				//update reply

				$reply->message = "Profile information updated";

			}
		}
	}
}



