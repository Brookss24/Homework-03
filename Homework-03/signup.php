<?php
include 'components/connect.php';

if(isset($_POST['signup']))

{
    $email = $_POST['email'];
    $userName = $_POST['username'];
    $password = $_POST['pswd'];

    // password hashing
    $options = ['cost' => 12];
    $hashedpas = password_hash($password, PASSWORD_BCRYPT, $options);
    // echo $hashedpas;

    // query for validation of username and email-id
    $query = "SELECT * FROM users WHERE(username=? || email=?)";
    $stmt = $con->prepare($query);
    $stmt ->bindparam(1,$userName);
    $stmt ->bindparam(2,$email);
    $stmt ->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    //print_r($result);

    if($stmt ->rowCount() == 0)
    {

      $query = "INSERT INTO users SET username=:username, email=:email, pass=:pass, userRole=:userRole";
      $stmt = $con->prepare($query);
      $userRole = "User";
      $stmt ->bindparam(':username', $userName, PDO::PARAM_STR);
      $stmt ->bindparam(':email', $email, PDO::PARAM_STR);
      $stmt ->bindparam(':pass', $hashedpas, PDO::PARAM_STR);
      $stmt ->bindparam(':userRole', $userRole, PDO::PARAM_STR);
      $stmt ->execute();
      $lastInsertId = $con->lastInsertId();
      if($lastInsertId)
      {
        $success_msg[] = "You have to signup successfully!";
      }  
      else 
      {
        $error_msg[] = "Something went wrong. please try again!";
      }
      

    }
    else
    {
      $warning_msg[] = "Please try again!";
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


<div class="container mt-3 w-50">
<h2></h2>
<div class="card">
<div class="card-header">Login / Register</div>
<div class="card-body">

  <form action="#" method="POST">
  <div class="mb-3 mt-3">
    <label for="email" class="form-label">Email:</label>
    <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email">
  </div>

    <div class="mb-3 mt-3">
    <label for="username" class="form-label">User Name:</label>
    <input type="username" class="form-control" id="username" placeholder="Enter Username" name="username">
  </div>


  <div class="mb-3">
    <label for="pwd" class="form-label">Password:</label>
    <input type="password" class="form-control" id="pwd" placeholder="Enter Password" name="pswd">
  </div>

  <div class="form-check mb-3">
    <label class="form-check-label">
      <input class="form-check-input" type="checkbox" name="remember"> Remember me
    </label>
  </div>
<div class="d-grid gap-4">
    
  

  <button type="submit" class="btn btn-primary" name='signup'>Register</button>
  <a type="submit" href="login.php"class="btn btn-primary">Login</a>

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
