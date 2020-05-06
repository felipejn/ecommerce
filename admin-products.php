<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
Use \Hcode\Model\Product;  

// List Products GET
$app->get("/admin/products", function() {

	User::verifyLogin();

	$search = (isset($_GET["search"])) ? $_GET["search"] : ""; 
	$page = (isset($_GET["page"])) ? (int)$_GET["page"] : 1;

	if ($search != "")
	{
		$pagination = Product::getPageSearch($search);

	} else {
		$pagination = Product::getPage($page);
	}

	$pages = [];

	for ($i = 0; $i < $pagination["pages"]; $i++) 
	{ 
		array_push($pages, [
			"href"=>"/admin/products?".http_build_query([
				"page"=>$i+1,
				"search"=>$search
			]),
			"text"=>$i+1
		]);
	}

	$page = new PageAdmin();

	$page->setTpl("products", array(
		"products"=>$pagination["data"],
		"search"=>$search,
		"pages"=>$pages
	));

});

// New Product GET
$app->get("/admin/products/create", function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("products-create");

});

// New Product POST
$app->post("/admin/products/create", function() {

	User::verifyLogin();

	$product = new Product();

	$product->setData($_POST);

	$product->save();

	header("Location: /admin/products");

	exit;

});

// Delete Product GET
$app->get("/admin/products/:idproduct/delete", function($idproduct) {

	User::verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$product->delete();

	header("Location: /admin/products");

	exit;

});

// Update Product GET
$app->get("/admin/products/:idproduct", function($idproduct) {

	User::verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$page = new PageAdmin();

	$page->setTpl("products-update", array(
		"product"=>$product->getValues()
	));

});

// Update Product POST
$app->post("/admin/products/:idproduct", function($idproduct) {

	User::verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$product->setData($_POST);

	$product->save();

	// Se error = 0 , há arquivo para ser enviado
	if ($_FILES["file"]["error"] === 0)
	{
		$product->setPhoto($_FILES["file"]);
	}

	header("Location: /admin/products");

	exit;

});

?>