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
