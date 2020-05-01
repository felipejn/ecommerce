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
		"error"=>User::getError(),
		"errorRegister"=>User::getErrorRegister(),
		"registerValues"=>(isset($_SESSION["registerValues"])) ? $_SESSION["registerValues"] : array(
			"name"=>"", 
			"email"=>"", 
			"phone"=>""
		)));

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

// Register Customer
$app->post("/register", function() {

	$_SESSION["registerValues"] = $_POST;

	if (!isset($_POST["name"]) || $_POST["name"] == "") 
	{
		User::setErrorRegister("Preencha o seu nome.");
		header("Location: /login");
		exit;
	}

	if (!isset($_POST["email"]) || $_POST["email"] == "") 
	{
		User::setErrorRegister("Preencha o seu email.");
		header("Location: /login");
		exit;
	}

	if (!isset($_POST["password"]) || $_POST["password"] == "") 
	{
		User::setErrorRegister("Preencha a senha.");
		header("Location: /login");
		exit;
	}

	if (User::checkLoginExist($_POST["email"]) === true)
	{
		User::setErrorRegister("Já existe um usuário cadastrado com este e-mail.");
		header("Location: /login");
		exit;
	}

	$user = new User();

	$user->setData([
		"inadmin"=>0,
		"deslogin"=>$_POST["email"],
		"desperson"=>$_POST["name"],
		"desemail"=>$_POST["email"],
		"nrphone"=>$_POST["phone"],
		"despassword"=>$_POST["password"]
	]);

	$user->save();

	$user->login($_POST["email"], $_POST["password"]);

	header("Location: /checkout");

	exit;

});

// Forgot Password Customer- GET
$app->get("/forgot", function() {

	$page = new Page();

	$page->setTpl("forgot");

});

// Forgot Password Customer- POST
$app->post("/forgot", function() {

	$user = User::getForgot($_POST["email"], false);

	header("Location: /forgot/sent");

	exit;

});

// Forgot Email Sent Customer- GET
$app->get("/forgot/sent", function() {

	$page = new Page();

	$page->setTpl("forgot-sent");

});

// Reset Password Customer- GET
$app->get("/forgot/reset", function() {

	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new Page();

	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));

});

// Reset Password Customer- POST
$app->post("/forgot/reset", function() {

	$forgot = User::validForgotDecrypt($_POST["code"]);

	User::setForgotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = password_hash($_POST["password"], PASSWORD_DEFAULT, [
		"cost"=>12
	]);

	$user->setPassword($password);

	$page = new Page();

	$page->setTpl("forgot-reset-success");

});

?>