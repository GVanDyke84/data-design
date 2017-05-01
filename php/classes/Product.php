<?php
/**
 * Created by PhpStorm.
 * User: GV8484
 * Date: 4/30/2017
 * Time: 12:38 PM
 */

namespace Edu\Cnm\DataDesign;
require_once("autoload.php");

class Product {
	/**
	 * id for the product: this is the primary key
	 * @var int $productId
	 **/
	private $productId;
	/**
	 * required price for product
	 * @var string SproductPrice
	 */
	private $productPrice;
	/**
	 * optional image attached to product
	 * @var string $profileAvatar
	 **/
	private $productImage;



	public function __construct(? int $productID, int $productPrice, string $productImage) {
		try {
			$this->setProductId($productID);
			$this->setProductPrice($productPrice);
			$this->setProductImage($productImage);
		} //determine exception type thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for productID
	 * @return int|null value of productID
	 **/
	public function getProductId(): ?int {
		return ($this->productId);
	}

	/**
	 * mutator method for productId
	 * @param int|null $productId value of productId
	 * @throws \RangeException if $productId is not positive
	 * @throws \TypeError if $productId is not an integer
	 * @throws \
	 **/
	public function setProductId(?int $productId): void {
		//if profile id is null immediately return it
		if($productId === null) {
			$this->productId = null;
			return;
		}
		// verify productId is positive
		if($productId <= 0) {
			throw(new \RangeException("product id is not positive"));
			//convert and store productId
			$this->productId;
		}
	}

	/**
	 *accessor method for product price
	 * @return string value of product price
	 **/
	public function getProductPrice(): string {
		return ($this->productPrice);
	}

	/**
	 * mutator method for product price
	 *
	 * @param string $productPrice value of product price
	 * @throws \InvalidArgumentException if $product price is not a string or insecure
	 * @throws \RangeException if $product price is > 32 characters
	 * @throws \TypeError if $product price is not a string
	 **/
	public function setProductPrice(string $productPrice): void {
		$productPrice = trim($productPrice);
		if(empty($productPrice) === true) {
			throw(new \InvalidArgumentException("product price is empty"));
			//verify the product price is less than 32 characters
			if(strlen($productPrice) > 32) {
				throw(new \RangeException("product price is longer than 32 character maximum"));
			}
			//store the product price
			$this->productPrice = $productPrice;
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
	 * mutator method for product Image
	 *
	 * @param string $productImage value of product Image
	 * @throws \RangeException if $productImage is > 32 characters
	 *
	 */
	public function setProductImage(string $productImage): void {
		$productImage = trim($productImage);
		if(strlen($productImage) > 32) {
			throw(new \RangeException("product Image is longer than the 32 character maximum"));
		}
		//store the product Image
		$this->productImage = $productImage;
	}


	/**
	 * Inserts product into MySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when MySQL errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 *
	 */
	public function insert(\PDO $pdo): void {
		// enforce the productID is null (don't insert a productID that already exists)
		if($this->productId !== null) {
			throw(new \PDOException("not a new product"));
		}
		//create query template
		$query = "INSERT INTO product(productId, productPrice, productImage) VALUES(:productId, :productPrice, :productImage)";
		$statement = $pdo->prepare($query);
		$parameters = ["productId" => $this->productId, "productPrice" => $this->productPrice, "productImage" => $this->productImage];
		$statement->execute($parameters);
		//update the null productId with what MySQL gives us
		$this->productId = intval($pdo->lastInsertId());
	}

	/**
	 * Deletes product from mySQL
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function delete(\PDO $pdo): void {
		// enforce the productId is not null (i.e. don't delete a profile that hasn't been inserted
		if($this->productId === null) {
			throw(new \PDOException("unable to delete a product that does not exist"));
		}
		//create query template
		$query = "DELETE FROM product WHERE productId = :productId";
		$statement = $pdo->prepare($query);
		// bind the product variables to the placeholder in the template
		$parameters = ["productId" => $this->productId];
		$statement->execute($parameters);
	}

	/**
	 * updates this product in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 *
	 */

	public function update(\PDO $pdo): void {
		// enforce the productId is not null (i.e. don't update a product that hasn't been inserted
		if($this->productId === null) {
			throw(new \PDOException("unable to update a product that does not exist"));
		}
		//create query template

		$query = "UPDATE product SET productId = :productId, productPrice = :productPrice, 						productImage = :productImage";
		$statement = $pdo->prepare($query);
		//bind the product variables to the placeholders in the template
		$parameters = ["productId" => $this->productId, "productPrice" => $this->productPrice, "productImage" => $this->productImage];
		$statement->execute($parameters);
	}

	/**
	 * Gets a product by productId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $productId product Id to search for
	 * @return Product|null Product found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 * @throws TypeError when variables are not correct data type
	 *
	 */

	public static function getProductByProductId(\PDO $pdo, int $productId) : ?Product {
		// sanitize the productId before searching
		if($productId <= 0) {
			throw(new \PDOException("product Id is not positive"));
		}

		//create query template
		$query = "SELECT productId, productPrice, productImage FROM product WHERE productId = 					:productId";
		$statement = $pdo->prepare($query);
		//bind the productId to the placeholder in the template
		$parameters = ["productId" => $productId];
		$statement->execute($parameters);
		//build an array of profiles
		$products = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try{
				$product = new Product($row["productId"], $row["productPrice"], $row["productImage"]);
				$products[$products->key()] = $product;
				$products->next();

			}	catch(\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($products);
	}
}

