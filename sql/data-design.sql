DROP TABLE IF EXISTS favorite;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS profile;
CREATE TABLE profile (
	profileId     INT UNSIGNED AUTO_INCREMENT NOT NULL,
	profileEmail  VARCHAR(128)                NOT NULL,
	profileHash   CHAR(128)                   NOT NULL,
	profileAvatar CHAR(32),
	profileSalt   CHAR(64)                    NOT NULL,
	UNIQUE (profileEmail),
	PRIMARY KEY (profileId)
);

CREATE TABLE product (
	productID    INT UNSIGNED AUTO_INCREMENT NOT NULL,
	productImage VARCHAR(32)                 NOT NULL,
	productprice VARCHAR(32)                 NOT NULL,
	PRIMARY KEY (productID)
);

CREATE TABLE favorite (
	favoriteProfileID INT UNSIGNED NOT NULL,
	favoriteProductID INT UNSIGNED NOT NULL,
	INDEX (favoriteProfileID),
	INDEX (favoriteProductID),
	FOREIGN KEY (favoriteProfileID) REFERENCES profile (profileId),
	FOREIGN KEY (favoriteProductID) REFERENCES product (productID),
	PRIMARY KEY (favoriteProfileID, favoriteProductID)
);



