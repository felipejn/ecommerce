<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
Use \Hcode\Model\Category;
use \Hcode\Model\Product;

// List Categories GET
$app->get("/admin/categories", function() {

	User::verifyLogin();

	$categories = Category::listAll();

	$page = new PageAdmin();

	$page->setTpl("categories", array(
		"categories"=>$categories
	));

});

// New Category GET
$app->get("/admin/categories/create", function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("categories-create");

});

// New Category POST
$app->post("/admin/categories/create", function() {

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");

	exit;

});

// Delete Category GET
$app->get("/admin/categories/:idcategory/delete", function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->delete();

	header("Location: /admin/categories");

	exit;

});

// Update Category GET
$app->get("/admin/categories/:idcategory", function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-update", array(
		"category"=>$category->getValues()
	));

});

// Update Category POST
$app->post("/admin/categories/:idcategory", function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->setData($_POST);

	$category->save();

	header ("Location: /admin/categories");

	exit;

});

// Category List Products GET
$app->get("/admin/categories/:idcategory/products", function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-products", array(
		"category"=>$category->getValues(),
		"productsRelated"=>$category->getProducts(),
		"productsNotRelated"=>$category->getProducts(false)
	));

});

// Add Product in Category GET
$app->get("/admin/categories/:idcategory/products/:idproduct/add", function($idcategory, $idproduct) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$category->addProduct($product); // -> Método recebe classe instanciada como argumento

	header("Location: /admin/categories/".$idcategory."/products");

	exit;

});

// Remove Product in Category GET
$app->get("/admin/categories/:idcategory/products/:idproduct/remove", function($idcategory, $idproduct) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$productsct = new Product();

	$product->get((int)$idproduct);

	$category->removeProduct($product); // -> Método recebe classe instanciada como argumento

	header("Location: /admin/categories/".$idcategory."/products");

	exit;

});

?>