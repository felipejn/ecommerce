<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;

// List Users GET
$app->get("/admin/users", function() {
	
	User::verifyLogin();

	$search = (isset($_GET["search"])) ? $_GET["search"] : ""; 
	$page = (isset($_GET["page"])) ? (int)$_GET["page"] : 1;

	if ($search != "")
	{
		$pagination = User::getPageSearch($search);

	} else {
		$pagination = User::getPage($page);
	}

	$pages = [];

	for ($i = 0; $i < $pagination["pages"]; $i++) 
	{ 
		array_push($pages, [
			"href"=>"/admin/users?".http_build_query([
				"page"=>$i+1,
				"search"=>$search
			]),
			"text"=>$i+1
		]);
	}

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$pagination["data"],
		"search"=>$search,
		"pages"=>$pages
	));

});

// New User - GET
$app->get("/admin/users/create", function() {
	
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");

});

// Delete GET
$app->get("/admin/users/:iduser/delete", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");

	exit;

});


// Update - GET
$app->get("/admin/users/:iduser", function($iduser) {
	
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	)); 

});

// New User- POST
$app->post("/admin/users/create", function() {

	User::verifyLogin();

	$user = new User();

	// Se $var tem valor, então é 1. Senão é 0.
	$_POST["inadmin"] = (isset($_POST["inadmin"])? 1 : 0);

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");

	exit;

});

// Update - POST
$app->post("/admin/users/:iduser", function($iduser) {

	User::verifyLogin();

	$user = new User();

	// Se $var tem valor, então é 1. Senão é 0.
	$_POST["inadmin"] = (isset($_POST["inadmin"])? 1 : 0); 

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");

	exit;

});

?>