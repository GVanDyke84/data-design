<?php
/**
 * Created by PhpStorm.
 * User: GV8484
 * Date: 5/1/2017
 * Time: 8:49 AM
 */

namespace Edu\Cnm\DataDesign;


class Favorite {
	/**
//favorite class linking back to the primary keys of profileId and productId
	 **/

	/**
	 * id of the profile favoriting a product
	 * @var int $favoriteProfileId
	 */
	private $favoriteProfileId;

	/**
	 * id of the product being favorited
	 * @var int $favoriteProductId
	 **/

	private $favoriteProductId;

	/**
	 * Favorite constructor.
	 * @param int $newFavoriteProfileId id of the parent profile
	 * @param int $newFavoriteProductId id of the parent product
	 * @throws \Exception if some other exception occurs
	 * @throws \TypeError if data types violate type hints
	 */

	public function __construct(? int $newFavoriteProfileId, int $newFavoriteProductId) {
	try {
	$this->setFavoriteProfileId($newFavoriteProfileId);
	$this->setFavoriteProductId($newFavoriteProductId);

	} //determine exception type thrown
	catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
	$exceptionType = get_class($exception);
	throw(new $exceptionType($exception->getMessage(), 0, $exception));
	}
	}

	/**
	 * accessor method for profileId
	 * @return int value of profileId
	 **/
	public function getFavoriteProfileId(): ?int {
		return ($this->favoriteProfileId);
	}

	/**
	 * mutator method for profileId
	 * @param int $favoriteProfileIdprofileId value of favoriteProfileId
	 * @throws \RangeException if $favoriteProfileId is not positive
	 * @throws \TypeError if $favoriteProfileId is not an integer

	 **/
	public function setFavoriteProfileId(?int $favoriteProfileId): void {
		//if favorite profile id is null immediately return it
		if($favoriteProfileId === null) {
			$this->favoriteProfileId = null;
			return;
		}
		// verify favoriteProfileId is positive
		if($favoriteProfileId <= 0) {
			throw(new \RangeException("favorite profile id is not positive"));
			//convert and store favoriteProfiletId
			$this->favoriteProfileId;
		}
	}

	/**
	 *accessor method for favorite product Id
	 * @return int for favorite product Id
	 **/
	public function getFavoriteProductId(): int {
		return ($this->favoriteProductId);
	}

	/**
	 * mutator method for favorite product id
	 *
	 * @param int $favoriteProductId value of favorite product Id
	 *
	 * @throws \RangeException if $favoriteProductId is not positive
	 * @throws \TypeError if $favoriteProductId is not an integer
	 **/
	public function setFavoriteProductId(?int $favoriteProductId): void {
		//if favorite profile id is null immediately return it
		if($favoriteProductId === null) {
			$this->favoriteProductId = null;
			return;
		}
		// verify favoriteProductId is positive
		if($favoriteProductId <= 0) {
			throw(new \RangeException("favorite product id is not positive"));
			//convert and store favoriteProfiletId
			$this->favoriteProductId;
		}
	}
	/**
	 * accessor method for product image
	 * @return string value of product image
	 **/
	public function getProductImage(): string {
		return ($this->productImage);
	}



	/**
	 * Inserts favorite profile Id into MySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when MySQL errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 *
	 */
	public function insert(\PDO $pdo): void {
		// enforce the favoriteProfileId is null (don't insert a favoriteProfileId that already exists)
		if($this->favoriteProfileId !== null) {
			throw(new \PDOException("not a new favorite profile Id"));
		}
		//create query template
		$query = "INSERT INTO favorite(favoriteProfileId) VALUES(:favoriteProfileId, :favoriteProductId)";
		$statement = $pdo->prepare($query);
		$parameters = ["favoriteProfileId" => $this->favoriteProfileId, "favoriteProductId" => $this->favoriteProductId];
		$statement->execute($parameters);
		//update the null favoriteProfileId with what MySQL gives us
		$this->favoriteProfileId = intval($pdo->lastInsertId());
	}

	/**
	 * Deletes favoriteProfileId from mySQL
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function delete(\PDO $pdo): void {
		// enforce the favoriteProfileId is not null (i.e. don't delete a favorite profile Id that hasn't been inserted
		if($this->favoriteProfileId === null) {
			throw(new \PDOException("unable to delete a favorite profile Id that does not exist"));
		}
		//create query template
		$query = "DELETE FROM favorite WHERE favoriteProfileId = :favoriteProfileId";
		$statement = $pdo->prepare($query);
		// bind the favorite profile Id variables to the placeholder in the template
		$parameters = ["favoriteProfileId" => $this->favoriteProfileId];
		$statement->execute($parameters);
	}

	/**
	 * updates this favorite profile Id in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 *
	 */

	public function update(\PDO $pdo): void {
		// enforce the favoriteProfileId is not null (i.e. don't update a favorite that hasn't been inserted
		if($this->favoriteProfileId === null) {
			throw(new \PDOException("unable to update a favorite that does not exist"));
		}
		//create query template

		$query = "UPDATE favorite SET favoriteProfileId = :favoriteProfileId, favoriteProductId = :favoriteProductId";
		$statement = $pdo->prepare($query);
		//bind the favorite variables to the placeholders in the template
		$parameters = ["favoriteProfileId" => $this->favoriteProfileId, "favoriteProductId" => $this->favoriteProductId];
		$statement->execute($parameters);
	}

	/**
	 * Gets a favorite by favoriteProfileId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $favoriteProfileId favorite profile Id to search for
	 * @return Favorite|null Favorite found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 * @throws TypeError when variables are not correct data type
	 *
	 */

	public static function getFavoriteByFavoriteProfileId(\PDO $pdo, int $favoriteProfileId) : ?Favorite {
		// sanitize the productId before searching
		if($favoriteProfileId <= 0) {
			throw(new \PDOException("favorite profile Id is not positive"));
		}

		//create query template
		$query = "SELECT favoriteProfileId, favoriteProductId FROM favorite WHERE favoriteProfileId = 					:favoriteProfileId";
		$statement = $pdo->prepare($query);
		//bind the favoriteProfileId to the placeholder in the template
		$parameters = ["favoriteProfileId" => $favoriteProfileId];
		$statement->execute($parameters);
		//build an array of favorites
		$favorites = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try{
				$favorite = new Favorite($row["favoriteProfileId"], $row["favoriteProductId"]);
				$favorites[$favorites->key()] = $favorite;
				$favorites->next();

			}	catch(\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($favorites);
	}


}