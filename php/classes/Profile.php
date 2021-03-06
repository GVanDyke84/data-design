<?php

namespace Edu\Cnm\DataDesign;
require_once("autoload.php");

/**
 * This is an example of a profile on an ecommerce site
 *
 * The primary key of the profile is profileID, also linked to the profile are a required email address
 * and an optional profile avatar. For security purposes hashing and salting is used for the password
 *so the password will not be stored on the site
 *
 * @author Gerrit Van Dyke <gvandyke1@cnm.edu>
 * @version 1.0
 *
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

	public function __construct(? int $profileID, int $profileEmail, string $profileAvatar) {
		try {
			$this->setProfileId($profileID);
			$this->setProfileEmail($profileEmail);
			$this->setProfileAvatar($profileAvatar);
		} //determine exception type thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

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
	}

	/**
	 *accessor method for profile email
	 * @return string value of profile email
	 **/
	public function getProfileEmail(): string {
		return ($this->profileEmail);
	}

	/**
	 * mutator method for profile email
	 *
	 * @param string $profileEmail value of profile email
	 * @throws \InvalidArgumentException if $profileEmail is not a string or insecure
	 * @throws \RangeException if $profileEmail is > 128 characters
	 * @throws \TypeError if $profileEmail is not a string
	 **/
	public function setProfileEmail(string $profileEmail): void {
		$profileEmail = trim($profileEmail);
		if(empty($profileEmail) === true) {
			throw(new \InvalidArgumentException("profile email is empty"));
			//verify the email address is less than 128 characters
			if(strlen($profileEmail) > 128) {
				throw(new \RangeException("email address is longer than the 128 character maximum"));
			}
			//store the profile email address
			$this->profileEmail = $profileEmail;
		}
	}

	/**
	 * accessor method for profile email
	 * @return string value of profile avatar
	 **/
	public function getProfileAvatar(): string {
		return ($this->profileAvatar);
	}

	/**
	 * mutator method for profile avatar
	 *
	 * @param string $profileAvatar value of profile avatar
	 * @throws \RangeException if $profileAvatar is > 32 characters
	 *
	 */
	public function setProfileAvatar(string $profileAvatar): void {
		$profileAvatar = trim($profileAvatar);
		if(strlen($profileAvatar) > 32) {
			throw(new \RangeException("profile avatar is longer than the 32 character maximum"));
		}
		//store the profile avatar
		$this->profileAvatar = $profileAvatar;
	}

	/**
	 * accessor method for profile hash
	 * @return string value of profile hash
	 */
	public function getProfileHash(): string {
		return ($this->profileHash);
	}

	/**
	 * mutator method for profile hash
	 * @param string $profileHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 128 characters
	 * @throws \TypeError if the profile hash is not a string
	 */
	public function setProfileHash(string $profileHash): void {
		$profileHash = trim($profileHash);
		if(empty($profileHash) === true) {
			throw(new \InvalidArgumentException("profile hash empty or insecure"));
		}
		//enforce profile Hash is exactly 128 characters
		if(strlen($profileHash) !== 128) {
			throw(new \RangeException("profile hash must be 128 characters"));
		}
		//store the profile hash
		$this->profileHash = $profileHash;
	}

	/**
	 * accessor method for profile salt
	 * @return string value of profile salt
	 */

	public function getProfileSalt(): string {
		return ($this->profileSalt);
	}

	/**
	 * mutator method for profile salt
	 * @param string $profileSalt
	 * @throws \InvalidArgumentException if the salt is not secure
	 * @throws \RangeException if the salt is not 128 characters
	 * @throws \TypeError if the salt is not a string
	 */
	public function setProfileSalt(string $profileSalt) : void {
		$profileSalt = trim($profileSalt);
		if(empty($profileSalt) === true) {
			throw(new \InvalidArgumentException("profile salt empty or insecure"));
		}
		//enforce profile salt is exactly 128 characters
		if (strlen($profileSalt) !== 128) {
			throw(new \RangeException("profile salt must be 128 characters"));
		}
		//store the profile salt
		$this->profileSalt = $profileSalt;
	}

	/**
	 * Inserts profile into MySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when MySQL errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 *
	 */
	public function insert(\PDO $pdo): void {
		// enforce the profileID is null (don't insert a profileID that already exists)
		if($this->profileId !== null) {
			throw(new \PDOException("not a new profile"));
		}
		//create query template
		$query = "INSERT INTO profile(profileId, profileEmail, profileAvatar) VALUES(:profileID, :profileEmail, :profileAvatar)";
		$statement = $pdo->prepare($query);
		$parameters = ["profileId" => $this->profileId, "profileEmail" => $this->profileEmail, "profileAvatar" => $this->profileAvatar];
		$statement->execute($parameters);
		//update the null profileId with what MySQL gives us
		$this->profileId = intval($pdo->lastInsertId());
	}

	/**
	 * Deletes profile from mySQL
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function delete(\PDO $pdo): void {
		// enforce the profileId is not null (i.e. don't delete a profile that hasn't been inserted
		if($this->profileId === null) {
			throw(new \PDOException("unable to delete a profile that does not exist"));
		}
		//create query template
		$query = "DELETE FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);
		// bind the profile variables to the placeholder in the template
		$parameters = ["profileId" => $this->profileId];
		$statement->execute($parameters);
	}

	/**
	 * updates this profile in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 *
	 */

	public function update(\PDO $pdo): void {
		// enforce the profileId is not null (i.e. don't update a profile that hasn't been inserted
		if($this->profileId === null) {
			throw(new \PDOException("unable to update a profile that does not exist"));
		}
		//create query template

		$query = "UPDATE profile SET profileId = :profileId, profileEmail = :profileEmail, 						profileAvatar = :profileAvatar";
		$statement = $pdo->prepare($query);
		//bind the profile variables to the placeholders in the template
		$parameters = ["profileId" => $this->profileId, "profileEmail" => $this->profileEmail, "profileAvatar" => $this->profileAvatar];
		$statement->execute($parameters);
	}

	/**
	 * Gets a profile by profileId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $profileId profile Id to search for
	 * @return Profile|null Profile found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 * @throws TypeError when variables are not correct data type
	 *
	 */

	public static function getProfileByProfileId(\PDO $pdo, int $profileId) : ?Profile {
		// sanitize the profileId before searching
		if($profileId <= 0) {
			throw(new \PDOException("profile Id is not positive"));
		}

		//create query template
		$query = "SELECT profileId, profileEmail, profileAvatar FROM profile WHERE profileId = 					:profileId";
		$statement = $pdo->prepare($query);
		//bind the profileId to the placeholder in the template
		$parameters = ["profileId" => $profileId];
		$statement->execute($parameters);
		//build an array of profiles
		$profiles = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try{
				$profile = new Profile($row["profileId"], $row["profileEmail"], $row["profileAvatar"]);
				$profiles[$profiles->key()] = $profile;
				$profiles->next();

			}	catch(\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($profiles);
	}
}
