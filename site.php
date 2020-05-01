<?php

use \Hcode\Page;
Use \Hcode\Model\Category;
Use \Hcode\Model\Product;  
Use \Hcode\Model\Cart;
Use \Hcode\Model\Address;
Use \Hcode\Model\User;

// ROOT GET
$app->get('/', function() {

	$products = Product::listAll();

	$page = new Page();

	$page->setTpl("index", array(
		"products"=>Product::checkList($products)
	));

});

// Category GET
$app->get("/categories/:idcategory", function($idcategory) {

	$page = (isset($_GET["page"])) ? (int)$_GET["page"] : 1;

	$category = new Category();

	$category->get((int)$idcategory);

	$pagination = $category->getProductsPage($page);

	$pages = [];

	for ($i = 1; $i <= $pagination["pages"] ; $i++) { 
		array_push($pages, array(
			"link"=>"/categories/".$category->getidcategory()."?page=".$i,
			"page"=>$i
		));
	}

	$page = new Page();

	$page->setTpl("category", array(
		"category"=>$category->getValues(),
		"products"=>$pagination["data"],
		"pages"=>$pages
	));

});

// Product GET
$app->get("/products/:desurl", function($desurl) {

	$product = new Product();

	$product->getFromUrl($desurl);

	$page = new Page();

	$page->setTpl("product-detail", array(
		"product"=>$product->getValues(),
		"categories"=>$product->getCategories()
	));

});

// Cart GET
$app->get("/cart", function() {

	$cart = Cart::getFromSession();

	$page = new Page();

	$page->setTpl("cart", array(
		"cart"=>$cart->getValues(),
		"products"=>$cart->getProducts(),
		"error"=>Cart::getMsgError()
	));

});

// Cart - Add one or more items from a product
$app->get("/cart/:idproduct/add", function($idproduct) {

	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$qtd = isset($_GET["qtd"]) ? (int)$_GET["qtd"] : 1;

	for ($i=0; $i < $qtd; $i++) { 
		
		$cart->addProduct($product);

	}

	header("Location: /cart");

	exit;

});

// Cart - Remove one item from a product
$app->get("/cart/:idproduct/minus", function($idproduct) {

	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product);

	header("Location: /cart");

	exit;

});

// Cart - Remove all items from a product
$app->get("/cart/:idproduct/remove", function($idproduct) {

	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product, true);

	header("Location: /cart");

	exit;

});

// Cart - Calculate freight price
$app->post("/cart/freight", function() {

	$cart = Cart::getFromSession();

	$cart->setFreight($_POST["zipcode"]);

	header("Location: /cart");

	exit;

});

// Checkout Cart
$app->get("/checkout", function() {

	User::verifyLogin(false);

	$cart = Cart::getFromSession();

	$address = new Address();

	$page = new Page();

	$page->setTpl("checkout", array(
		"cart"=>$cart->getValues(),
		"address"=>$address->getValues()
	));

});

// Login Customer GET
$app->get("/login", function() {

	$page = new Page();

	$page->setTpl("login", array(
		"error"=>User::getError()
	));

});

// Login Customer POST
$app->post("/login", function() {

	try {
	
		User::login($_POST["login"], $_POST["password"]);
		
	} catch(Exception $e) {

		User::setError($e->getMessage());

	} 

	header("Location: /checkout");

	exit;

});

// Logout Customer
$app->get("/logout", function() {

	User::logout();
	header("Location: /");
	exit;

});

?>