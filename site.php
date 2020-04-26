<?php

use \Hcode\Page;
Use \Hcode\Model\Category;
Use \Hcode\Model\Product;  

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

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", array(
		"category"=>$category->getValues(),
		"products"=>Product::Checklist($category->getProducts())
	));

});

// Product GET
$app->get("/products/:idproduct", function($idproduct) {

	$product = new Product();

	$product->get((int)$idproduct);

});

?>