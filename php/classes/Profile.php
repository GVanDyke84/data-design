<?php
namespace Edu\Cnm\DataDesign;
require_once("autoload.php");
/**
 * This is an example of a profile on an ecommerce site
 *
 * ...and a description here
 *
 * @author Gerrit Van Dyke <gvandyke1@cnm.edu>
 * @version 1.0
 **/
class Profile {
	/**
	 * id for the profile: this is the primary key
	 * @var int $profileId
	 **/
	private $profileId;
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
	 * @var string $profileSalt
	 **/
	private $profileSalt;
	/**
	 * randomly generated required hash for profile password
	 * @var string $profileHash
	 **/
	private $profileHash;


	/**
	 * accessor method for profileID
	 * @return int|null value of profileID
	 **/
	public function getProfileId(): ?int {
		return ($this->profileId);
	}

	/**
	 * mutator method for profileID
	 * @param int|null $profileId value of profileID
	 * @throws \RangeException if $profileID is not positive
	 * @throws \TypeError if $profileID is not an integer
	 * @throws \
	 **/
	public function setProfileId(?int $profileId): void {
		//if profile id is null immediately return it
		if($profileId === null) {
			$this->profileId = null;
			return;
		}
		// verify profileID is positive
		if($profileId <= 0) {
			throw(new \RangeException("profile id is not positive"));
			//convert and store proileID
			$this->profileId;
		}
		{
			require_once("autoload.php");
		}
		{ValidateDate;}
	}
	/**
	 *accessor method for profile email
	 * @return string value of profile email
	 **/
