
<?php
include 'connect.php';

if (isset($_COOKIE['user_id']))
{
  $user_id = $_COOKIE['user_id'];
}




  $count_cart_items = $con->prepare("SELECT * FROM `cart` WHERE user_id =?");
  $count_cart_items->execute([$user_id]);
  $total_cart_items = (string) $count_cart_items->rowCount();


?>


<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a href="#" class="navbar-brand">
        <img src="images/logo.png" height="28" alt="Logo">
      </a>
      <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav">
          <a href="index.php" class="nav-item nav-link active">Home</a>
          
          <a href="index.php" class="nav-item nav-link ">View Product</a>
          <a href="orders.php" class="nav-item nav-link ">My Orders</a>
        </div>

        <div class="d-flex ms-auto">
          <a class="nav-link" href="shopping_cart.php" style="font-size: 20px; color: red;">
            <span class="fa-solid fa-cart-shopping"><?= $total_cart_items; ?></span>
          </a>
          <a href="login.php" class="nav-item nav-link">Login</a>
          <form class="d-flex ms-auto">
            <input type="text" class="form-control me-sm-2" name="Search" placeholder="Search..">
            <button type="submit" class="btn btn-outline-light">Search</button>           
            </form>
          </div>
        </div>
      </div>
    </nav>