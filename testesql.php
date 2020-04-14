<?php  

$conn = new PDO("mysql:dbname=db_ecommerce;host=127.0.0.1;port=8889","root","root");

$stmt = $conn->prepare("SELECT * FROM tb_users");

$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);

?>