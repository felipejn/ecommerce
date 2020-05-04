<?php 

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Cart;
use \Hcode\Model\Order;
use \Hcode\Model\OrderStatus;


// Delete Order
$app->get("/admin/orders/:idorder/delete", function($idorder) {

	User::verifyLogin();

	$order = new Order();

	$order->get((int)$idorder);

	$order->delete($idorder);

	header("Location: /admin/orders");

	exit;

});

// Order Details Admin
$app->get("/admin/orders/:idorder", function($idorder) {

	User::verifyLogin();

	$order = new Order();

	$order->get((int)$idorder);

	$cart = $order->getCart();

	$page = new PageAdmin();

	$page->setTpl("order", array(
		"order"=>$order->getValues(),
		"cart"=>$cart->getValues(),
		"products"=>$cart->getProducts()
	));

});

// Order Status GET
$app->get("/admin/orders/:idorder/status", function($idorder) {

	User::verifyLogin();

	$order = new Order();

	$order->get((int)$idorder);

	$page = new PageAdmin();

	$page->setTpl("order-status", array(
		"order"=>$order->getValues(),
		"status"=>OrderStatus::listAll(),
		"msgSuccess"=>Order::getSuccess(),
		"msgError"=>Order::getError()
	));

});

//Order Status POST
$app->post("/admin/orders/:idorder/status", function($idorder){

	User::verifyLogin();

	if (!isset($_POST["idstatus"]) || !(int)$_POST["idstatus"] > 0)
	{
		Order::setError("Informe o status atual.");
		header("Location: /admin/orders/".$idorder."/status");
		exit;
	}

	$order = new Order();

	$order->get((int)$idorder);

	$order->setidstatus($_POST["idstatus"]);

	$order->save();

	Order::setSuccess("Status alterado com sucesso.");
	
	header("Location: /admin/orders/".$idorder."/status");
	
	exit;
});

// Orders list Admin
$app->get("/admin/orders", function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("orders", array(
		"orders"=>Order::listAll()
	));

});

?>