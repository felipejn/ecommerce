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

?>