<?php
session_start();
if(isset($_POST['login']))
{
  include 'components/connect.php';
  $userName = $_POST['username'];
  $password = $_POST['pswd'];

  // Fetch data from database on the basis of username/email and password

  $query = "SELECT username, email, pass, userRole FROM users WHERE(username=:username || email=:username)";
  $stmt = $con->prepare($query);
  $stmt ->bindparam(':username', $userName, PDO::PARAM_STR);
  $stmt->execute();
  $results = $stmt->fetchAll(PDO::FETCH_OBJ);
    // print_r($results);
  if ($stmt ->rowCount() == 1) 
  {
      foreach ($results as $value) 
      {
        $hashpass = $value->pass;
        //echo $hashpass;
        $userRole = $value->userRole; 
        //echo '<br>'.$userRole;
      }
      if((password_verify($password, $hashpass)) &&($userRole == 'User' || $userRole=='Admin'))
      {
          $_SESSION['userlogin'] = $_POST['username'];
          $_SESSION['userRole'] = $userRole;
          print_r($_SESSION['userlogin']);

          if($userRole == 'Admin')
          {
            echo "<script>document.location='admin.php'</script>";
          }

          if ($userRole == 'User') 
          {
            echo "<script>document.location='index.php'</script>";
          }
      }
      else
      {
        $warning_msg[] = "Wrong Password or username Please try again!";
      }


  }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Bootstrap Example</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

  <!--- Navbar --->
   <?php include 'components/navbar.php'; ?>
  <!--- End navbar --->

 
<div class="container mt-3 w-50">
<h2></h2>
<div class="card">
<div class="card-header">Login / Register</div>
<div class="card-body">

  <form action="#" method="POST">
  <div class="mb-3 mt-3">
    <label for="text" class="form-label">User name / Email:</label>
    <input type="text" class="form-control" id="username" placeholder="Enter Email / User Name" name="username">
  </div>

  <div class="mb-3">
    <label for="pwd" class="form-label">Password:</label>
    <input type="password" class="form-control" id="pwd" placeholder="Enter Password" name="pswd">
  </div>


<div class="d-grid gap-4">
    
  
  <button type="submit" class="btn btn-primary" name='login'> Login </button>
  <a href="signup.php" class="btn btn-primary" > Register </a>

</div>
</form>

 
    </div> 
    <div class="card-footer">

</div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
 <?php include 'components/alert.php'; ?>
</body>
</html>
