<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
Use \Hcode\Model\Product;  

// List Products GET
$app->get("/admin/products", function() {

	User::verifyLogin();

	$products = Product::listAll();

	$page = new PageAdmin();

	$page->setTpl("products", array(
		"products"=>$products
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

	$product->setPhoto($_FILES["file"]);

	header("Location: /admin/products");

	exit;

});

?>