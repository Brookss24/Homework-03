<?php
// include database connection
include"components/connect.php";
 
// Check if ID is set and not empty
if (isset($_POST['id']) && !empty($_POST['id']))
{
	// Sanitize the ID to prevent SQL injection
	$id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
 
	// Perform deletion query
	$query = "DELETE FROM products WHERE id=?";
	$stmt = $con->prepare($query);
	$stmt->bindParam(1, $id, PDO::PARAM_STR);
	$success = $stmt->execute();
 
	// Return success response
	echo json_encode(['success'=>$success]);
	exit();
}
 
// If ID is not set or empty, return failure response
echo json_encode(['success'=> false]);
 
 
?>