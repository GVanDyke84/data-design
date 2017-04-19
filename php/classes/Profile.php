<?php
namespace\;
require_once("autoload.php");
/**This is an example of a profile on an ecommerce site
 * @author Gerrit Van Dyke <gvandyke1@cnm.edu>
 * @version 1.0
 **/
class Profile {
	/**
	 * id for the profile: this is the primary key
	 * @var int $profileID
	 **/
private $profileID;
/**
 * required email attached to the profile
 * @var string SprofileEmail
 */
private $profileEmail;
/**
 * optional avatar name or photo connected with profile
 * @var string $profileAvatar
 **/
private $profileAvatar;
/**
 * randomly generated required salt for profile password
 * @string bin2hex(random_bytes(16)) $profileSalt
 **/
private $profileSalt;
/**
 * randomly generated required hash for profile password
 * @string hash_pbkdf2("sha512", $password, $profileSalt, 262144);
**/
private $profileHash;
}
