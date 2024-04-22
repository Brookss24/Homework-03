<?php



if (isset($_COOKIE['user_id']))
{
  $user_id = $_COOKIE['user_id'];
}
else
{
  setcookie('user_id', create_unique_id(), time() + 60*60*24*30);
}

if (isset($_POST['add_to_cart']))
{
  $product_id = $_POST['product_id'];
  // echo $product_id;
  $id = create_unique_id();
  $qut = $_POST['qty'];
  $verify_cart = $con->prepare("SELECT * FROM `cart` WHERE user_id =? AND product_id=?");
  $verify_cart ->execute([$user_id, $product_id]);

  $max_cart_items = $con->prepare("SELECT * FROM `cart` WHERE user_id =?");
  $max_cart_items ->execute([$user_id]);

  if ($verify_cart->rowCount() >0) 
  {
    $warning_msg[] = "already added to cart";
  }
  elseif ($max_cart_items-> rowCount()==10) 
  {
    $warning_msg[] = "Cart is full!";
  }
  else
  {
    $select_price = $con->prepare("SELECT *FROM products WHERE id=? LIMIT 1");
    $select_price ->execute([$product_id]);
    $fetch_price = $select_price ->fetch(PDO::FETCH_ASSOC);

    $insert_cart = $con->prepare("INSERT INTO `cart` (id, user_id, product_id, price, qty) VALUES (?,?,?,?,?)");
    $insert_cart ->execute([$id, $user_id, $product_id, $fetch_price['price'], $qut]);
    $success_msg[] = 'Added to cart!';
  }

}



  $select_products = "SELECT * FROM products";
  $stmt = $con->prepare($select_products);
  $stmt->execute();
  if($stmt->rowCount() > 0)
  {
    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) 
    {
      

?>



      <div class="col">
        <div class="card product-card">
          <div class="image-container">
            <img src="<?php echo $row['image']; ?>" class="card-img-top image" alt="Product images">  
            <div class="overlay">
              <!-- <a href="#" class="btn btn-outline-warning btn-sm">Add to Cart</a> -->
            </div>          
          </div>
          <div class="card-body">
            <h5 class="card-title"><?php echo $row['name']; ?></h5>
            <p class="card-text">
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-secondary"></i>            
            </p>
            <p>
              <form action="#" method="POST">
                <input type="hidden" name="product_id" value="<?= $row['id'];?>">
                <i class="fa-solid fa-dollar-sign"><?php echo $row['price']; ?></i>
                <span><input type="text" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty w-25 form-control float-end text-end border border-warning rounded-pill"></span>
                </p>
                <input type="submit" name="add_to_cart" value="Add to cart" class="btn btn-warning rounded-pill">
                <a href="checkout.php" class="btn btn-danger rounded-pill">Buy now</a>
              </form>
            
          </div>          
        </div>
      </div>

<?php
    }
  }

  else
  {
    echo '<p class="empty">no products found!</>';
  }

?>
      

  