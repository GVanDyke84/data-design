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
	}else if(empty($profileEmail) === false) {
		$profile = Profile::getProfileByProfileEmail($pdo, $profileEmail);
		if($profile !== null) {
				$reply->data = $profile;
		}
	}
}
}