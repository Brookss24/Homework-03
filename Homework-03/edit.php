<?php

// Include database connection
include "components/connect.php";

$nameError = $priceError = $categoryError = $descriptionError = "";
$isNameValid = $isPriceValid = $isCategoryValid = $isDescriptionValid = false;

// Check if ID parameter exists
if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);

    // Prepare statement to select record with the given ID
    $query = "SELECT id, name, price, category, description FROM products WHERE id = :id";

    // Prepare
    $stmt = $con->prepare($query);

    // Bind parameter
    $stmt->bindParam(':id', $id);

    // Execute
    $stmt->execute();

    // Fetch record
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Extract variables
    extract($row);
} else {
    // Redirect if ID parameter is not provided
    header("Location: index.php");
    exit();
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate inputs
    if (empty($_POST['name'])) {
        $nameError = "Name is required";
    } else {
        $name = test_input($_POST['name']);
        // Perform any additional validation if needed
        $isNameValid = true;
    }

    if (empty($_POST['price'])) {
        $priceError = "Price is required";
    } else {
        $price = test_input($_POST['price']);
        // You might want to perform additional validation for price, such as numeric validation
        $isPriceValid = true;
    }

    if (empty($_POST['category'])) {
        $categoryError = "Category is required";
    } else {
        $category = test_input($_POST['category']);
        // Perform any additional validation if needed
        $isCategoryValid = true;
    }

    if (empty($_POST['description'])) {
        $descriptionError = "Description is required";
    } else {
        $description = test_input($_POST['description']);
        // Perform any additional validation if needed
        $isDescriptionValid = true;
    }

    // If all fields are valid, proceed with updating the record
    if ($isNameValid && $isPriceValid && $isCategoryValid && $isDescriptionValid) {
        // Update query
        $query = "UPDATE products SET name = :name, price = :price, category = :category, description = :description WHERE id = :id";

        // Prepare
        $stmt = $con->prepare($query);

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);

        // Execute
        if ($stmt->execute()) {

            $successMessage = "Record Updated Successfully.";

            echo "<script>
                setTimeout(function() {
                window.location.href = 'Admin.php';
                }, 3000);
                </script>";

            // Redirect to index.php after successful update
            // header("Location: index.php");
            // exit();
        } else {
            // Handle error
            echo "Error updating record.";
        }
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Product Info</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap5.3.2.css" rel="stylesheet">
    <script src="js.bootstrap5.3.2.js"></script>
    <style type="text/css">
        {
        background-color: #ababab;
        }
    </style>
</head>

<body>
    <div class="container mt-3">
        <h2>Edit Product Info</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control <?php echo !empty($nameError) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>">
                <div class="invalid-feedback"><?php echo $nameError; ?></div>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control <?php echo !empty($priceError) ? 'is-invalid' : ''; ?>" id="price" name="price" value="<?php echo isset($price) ? $price : ''; ?>">
                <div class="invalid-feedback"><?php echo $priceError; ?></div>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" class="form-control <?php echo !empty($categoryError) ? 'is-invalid' : ''; ?>" id="category" name="category" value="<?php echo isset($category) ? $category : ''; ?>">
                <div class="invalid-feedback"><?php echo $categoryError; ?></div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control <?php echo !empty($descriptionError) ? 'is-invalid' : ''; ?>" id="description" name="description"><?php echo isset($description) ? $description : ''; ?></textarea>
                <div class="invalid-feedback"><?php echo $descriptionError; ?></div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <?php if (!empty($successMessage)) : ?>
        <div class="container mt-3">
            <div class="alert alert-success" role="alert"><?php echo $successMessage; ?></div>
        </div>
    <?php endif; ?>
</body>

</html>
