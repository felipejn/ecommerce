<?php

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
Use \Hcode\Model\Category;

$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/', function() {

	$page = new Page();

	$page->setTpl("index");

});

$app->get('/admin', function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");

});

$app->get('/admin/login', function() {

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");
});

$app->post('/admin/login', function() {
	
	User::login($_POST["login"], $_POST["password"]);

	header("Location: /admin");

	exit;

});

$app->get('/admin/logout', function() {

	User::logout();

	header("Location: /admin/login");

	exit;

});

// List
$app->get("/admin/users", function() {
	
	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$users
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

// Create New - POST
$app->post("/admin/users/create", function() {

	User::verifyLogin();

	$user = new User();

	// Se $var tem valor, então é 1. Senão é 0.
	$_POST["inadmin"] = (isset($_POST["inadmin"])? 1 : 0);

	$_POST["despassword"] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [
		"cost"=>12
	]);

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

// Forgot Password - GET
$app->get("/admin/forgot", function() {

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot");

});

// Forgot Password - POST
$app->post("/admin/forgot", function() {

	$user = User::getForgot($_POST["email"]);

	header("Location: /admin/forgot/sent");

	exit;

});


// Forgot Email Sent - GET
$app->get("/admin/forgot/sent", function() {

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-sent");

});

// Reset Password - GET
$app->get("/admin/forgot/reset", function() {

	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));

});

// Reset Password - POST
$app->post("/admin/forgot/reset", function() {

	$forgot = User::validForgotDecrypt($_POST["code"]);

	User::setForgotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = password_hash($_POST["password"], PASSWORD_DEFAULT, [
		"cost"=>12
	]);

	$user->setPassword($password);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-reset-success");

});

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

	$category = new Category;

	$category->get((int)$idcategory);

	$category->delete();

	header("Location: /admin/categories");

	exit;

});

// Update Category GET
$app->get("/admin/categories/:idcategory", function($idcategory) {

	User::verifyLogin();

	$category = new Category;

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

$app->run();

?>