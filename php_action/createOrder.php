<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

    $order_date = $_POST['orderDate'];
    $clientName = $_POST['clientName'];
    $clientContact = $_POST['clientContact'];
    $subTotal = $_POST['subTotal'];
    $totalAmount = $_POST['totalAmount'];
    $discount = $_POST['discount'];
    $grandTotal = $_POST['grandTotal'];
    $vat = $_POST['gstn'];
    $paid = $_POST['paid'];
    $due = $_POST['due'];
    $paymentType = $_POST['paymentType'];
    $paymentStatus = $_POST['paymentStatus'];
    $paymentPlace = $_POST['paymentPlace'];

    $user = 1;


    $sql = "INSERT INTO orders (user_id, order_date, client_name, client_contact, sub_total, vat, total_amount, discount, grand_total, paid, due, payment_type, payment_status, order_status) 
    VALUES ('$user', '$order_date', '$clientName', '$clientContact', '$subTotal', '$vat', '$totalAmount', '$discount', '$grandTotal', '$paid', '$due', '$paymentType', '$paymentStatus',1)
    ";

	if($connect->query($sql) === TRUE) {
	 	$valid['success'] = true;
		$valid['messages'] = "Successfully Added";
		header('location:fetchOrder.php');	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "Error while adding the members";
	}

	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST