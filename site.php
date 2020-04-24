<?php

use \Hcode\Page;
Use \Hcode\Model\Category;
Use \Hcode\Model\Product;  

// ROOT GET
$app->get('/', function() {

	$page = new Page();

	$page->setTpl("index");

});

// Categories GET
$app->get("/categories/:idcategory", function($idcategory) {

	$category = new Category;

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", array(
		"category"=>$category->getValues(),
		"products"=>[]
	));

});

?>