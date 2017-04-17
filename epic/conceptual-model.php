<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>CONCEPTUAL MODEL</title>
	</head>
	<body>
		<header>
			<h1>CONCEPTUAL MODEL</h1>

			<main>
				<h2>Entity-Profile</h2>
				<ul>
					<li>Profile ID (primary key) </li>
					<li>Profile hash & salt(password verification)</li>
					<li>Avatar</li>
					<li>Email</li
				</ul>
				<h2>Entity-Product</h2>
				<ul>
					<li>Product ID (primary key)</li>
					<li>Product image</li>
					<li>Size/dimensions</li>
					<li>Price</li>
				</ul>
				<h2>Entity-Favorite</h2>
				<ul>
					<li>profile ID (composite key)</li>
					<li>product ID (composite key)</li>

				</ul>
				<h2>Relationships</h2>
				<ul>
					<li>Many products can be favorited by many profiles</li>
					<li>Many favorites can be shared to many social network sites</li>
			</main>
		</header>
	</body>
</html>