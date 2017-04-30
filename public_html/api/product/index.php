<?php


require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DataDesign\ {
	Product
};


/**
 * API for Product
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
	$productPrice = filter_input(INPUT_GET, "productPrice", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$productImage = filter_input(INPUT_GET, "productImage", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//ensure the id is valid for the methods that require it

	if((method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id can't be negative or empty", 405));

	}
	if($method === "GET") {
		//XSRF cookie
		setXsrfCookie();

		//gets a post by content
		if(empty($id) === false) {
			$product = Product::getProductByProductId($pdo, $id);

			if($product !== null) {
				$reply->data = $product;
			}
		} else if(empty($productPrice) === false) {
			$product = Product::getProductByProductPrice($pdo, $productPrice);
			if($product !== null) {
				$reply->data = $product;
			}
		} else if(empty($productImage) === false) {
			$product = Product::getProductByProductImage($pdo, $productImage);
			if($product !== null) {
				$reply->data = product;
			}
		}
	} elseif($method === "PUT") {

		//ensure the user is signed in and trying to edit only their own product
		if(empty($_SESSION["product"]) === true || $_SESSION{"product"}->getProductId() !== $id) {
			throw(new \InvalidArgumentException("You are not allowed to access this product", 403));
		}
		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//retrieve product to be updated

		$product = Product::getProductByProductId($pdo, $id);
		if($product === null) {
			throw(new RuntimeException("Product does not exist", 404));
		}

		if(empty($requestObject->newPassword) === true) ;
		{

			//enforce that XSRF token is present in the header
			verifyXsrf();

			//product price is required
			if(empty($requestObject->productPrice) === true) {
				throw(new \InvalidArgumentException("No product price present", 405));

				//product Image
				if(empty($requestObject->productImage) === true) {
					throw(new \InvalidArgumentException("No product image present", 405));

				}}}

				$product->setProductPrice($requestObject->productPrice);
				$product->setProductImage($requestObject - productImage);
				$product->update($pdo);

				//update reply

				$reply->message = "Product information updated";

			} elseif($method === "DELETE") {
				//verify the XSRF token
				verifyXsrf();

				$product = Profile::getProductByProductId($pdo, $id);
				if($product === null) {
					throw (new RuntimeException("Product does not exist"));
				}

				//ensure the user is logged in and only trying to edit their own product
				if(empty($_SESSION["product"]) === true || $_SESSION["product"]->getProductId() !== $product->getProductId()) {
					throw(new \InvalidArgumentException("You are not allowed to access this product", 403));
				}

				//delete the product from the database
				$product->delete($pdo);
				$reply->message = "Product Deleted";
			} else {
				throw (new InvalidArgumentException("Invalid HTTP request", 400));
			}
			//catch any exceptions that were thrown and update the status and message state variable fields

		} catch
		(\Exception | \TypeError $exception) {
			$reply->status = $exception->getCode();
			$reply->message = $exception->getMessage();
		}
}
	header("Content-type: application/json");
	if($reply->data === null) {
		unset($reply->data);
	}
	//encode and return reply to front end caller
	echo json_encode($reply);}



