<?php
include_once('components/connect.php');

if (isset($_COOKIE['user_id']))
{
  $user_id = $_COOKIE['user_id'];
}
else
{
  setcookie('user_id', create_unique_id(), time() + 60*60*24*30);
}

if (isset($_POST['add'])) 
{
	$id = create_unique_id();
	$name = $_POST['pname'];
	$price = $_POST['price'];
	$image = $_FILES['image']['name'];
	$ext = pathinfo($image, PATHINFO_EXTENSION);
	$rename = create_unique_id().'.'.$ext;
	// echo $rename;
	$image_tmp_name = $_FILES['image']['tmp_name'];
	$image_size = $_FILES['image']['size'];
	$image_folder = 'uploaded_files/'.$rename;
	if($image_size >2000000)
	{
		$warning_msg[]= 'Image size is to large';
	}
	else
	{
		$query = "INSERT INTO products SET id=?, name=?, price=?, image=?";
		$stmt = $con->prepare($query);
		$stmt->bindparam(1,$id);
		$stmt->bindparam(2, $name);
		$stmt->bindparam(3, $price);
		$stmt->bindparam(4, $image_folder);
		$stmt->execute();
		move_uploaded_file($image_tmp_name, $image_folder);
		$success_msg[] = 'Product added!';
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	   <?php include 'components/link.php'; ?>
	<title></title>
</head>
<body>
  <!--- Navbar --->
   <?php// include 'components/navbar.php'; ?>
  <!--- End navbar --->

  <div class="container mt-5 mb-5 d-flex justify-content-center">
  	<div class="card w-50">
  		<div class="card-body">
  			<form action="#" method="POST" enctype="multipart/form-data">

  				<div class="mb-3 mt-3">
  					<label for="pname">Product Name</label>
  					<input type="text" class="form-control" name="pname" id="pname" required maxlength="50" placeholder="Enter product name">
  				</div>

  				<div class="mb-3 mt-3">
  					<label for="price">Product Price</label>
  					<input type="number" class="form-control" name="price" id="price" required min="0" max="99999" maxlength="10" placeholder="Enter product price">
  				</div>

  				<div class="mb-3 mt-3">
  					<label for="image" class="form-label">Product image </label>
  					<input type="file" class= "form-control" name="image" id="image"> 					
  				</div>

  				<div class="d-grid gap-4">
  					<button type="submit" name="add" value="add" class="btn btn-primary">Submit</button>
  					  <!-- Go Back Button -->
            <a href="admin.php" class="btn btn-danger">Go Back</a>

  				</div>

  			</form>
  		</div>
  	</div>
  	
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js">
  </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>
<?php include 'components/alert.php'; ?>
</body>
</html>