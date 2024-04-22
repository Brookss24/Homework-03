<?php
session_start();
include 'components/connect.php';

if(empty($_SESSION['userlogin'])) {
    echo "<script>document.location='signup.php'</script>";
    exit; // Stop executing further if the session is not present
}

// select all data
$query = "SELECT id, name, price, image, category, description FROM products";

// prepare
$stmt = $con->prepare( $query );

// execute
$stmt->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Product Management</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap5.3.2.css" rel="stylesheet">
<script src="js/jquery-3.7.0.min.js"></script>
<script src="js/bootstrap5.3.2.js"></script>
<style type="text/css">
body {
    background-color: #ababab;
}
</style>
</head>
<body>
<div class="container mt-3">
  <h2>Product Management / Admin</h2>
  <a href="add_product.php" class="btn btn-success float-end txt-dark">Add New Product</a> <br><br>
  
  <!-- Search Bar -->
  <form method="GET" class="mb-3">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Search by product name">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </form>
  
  <table class="table rounded-3 overflow-hidden">
    <thead>
      <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Image</th>
        <th>Category</th>
        <th>Description</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Initialize the query string
      $query = "SELECT id, name, price, image, category, description FROM products";
      
      // Check if search query is provided
      if(isset($_GET['search']) && !empty($_GET['search'])) {
          // Sanitize the search term to prevent SQL injection
          $search = '%' . $_GET['search'] . '%';
          // Modify the query to include search condition
          $query .= " WHERE name LIKE ?";
          // Prepare and execute the statement with search term
          $stmt = $con->prepare($query);
          $stmt->execute([$search]);
      } else {
          // No search query provided, execute the default query
          $stmt = $con->query($query);
      }
      
      // Fetch and display results
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          extract($row);
          
          echo '<tr>';
          echo "<td>{$name}</td>";
          echo "<td>{$price}</td>";
          echo "<td><img src='{$image}' style='max-width: 100px; max-height: 100px;'></td>";
          echo "<td>{$category}</td>";
          echo "<td>{$description}</td>";
          echo '<td>';
          echo "<a href='edit.php?id={$id}' class='btn btn-warning btn-sm'>Edit</a>&nbsp;";
          echo '<a href="#" class="btn btn-sm btn-danger btn-xs mr-1 deleteBtn" data-id="'. $id . '">Delete</a> &nbsp';
          echo '</td>';
          echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</div>
</body>
</html>


<?php
// Close the cursor to enable the next query
$stmt->closeCursor();
// Close the connection
$con = null;
?>