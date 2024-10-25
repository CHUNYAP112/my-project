<?php 

include 'config.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)) {
    header('location:admin-login.php');
};

if(isset($_GET['logout'])) {
    unset($admin_id);
    session_destroy();
    header('admin-login.php');
}
?>

<!DOCTYPE html>
<html lang="en" style="height:100%;">

<head> 
    <meta charset="UTF-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="order-completed.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>ADMIN SYSTEM/order-cancel</title>
</head>

<body>
<?php
    $select = mysqli_query($conn, "SELECT * FROM `admin` WHERE admin_id = '$admin_id'")
    or die('query failed');
    if(mysqli_num_rows($select) > 0) {
        $fetch = mysqli_fetch_assoc($select);   
    }
    ?>

	<!--Top Bar-->
	<div class="wrapper_top_bar">
		<div class="container_top_bar">
			<div class="welcometraveler-bar" style="display: block;">
			
			<div class="text_top_bar"> Welcome <?php echo $fetch['name']; ?></div>
			</div>
		</div>	
	</div>	
	
    <!--Header-->
    <input type ="checkbox" id="check">
    <header>
	    <div class="logo">
            <img src="company logo UTS 1.png" alt="Company Logo">
            <h1 class ="h1"><a href ="admin-home.php">Universal Travel Service <br>Company</a></h1> 
        </div>

        <div class = "navbar">
            <li> <a href="admin-system.php" class="active">Home</a></li>
            <li> <a href="package.php">Travel Package</a></li>
            <li> <a href="order-details.php">Order</a></li>
            <li> <a href="user-details.php">User</a></li>
            <li> <a href="admin-details.php">Admin</a></li>
            <li> <a href="admin-message.php">Message</a></li>
        </div>

                <nav>
                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="profile-dropdown-btn" onclick="toggle()">
                            <div class="profile-img">
                            
                            <?php 
                            echo '<img src = "uploaded_img/ACCOUNT LOGO PICTURE.png">';
                            ?>
                                
                            <i class="fa-solid fa-circle"></i>
                            </div>

                            <?php
                                // Check if the session variable is set
                                if ($fetch['name'] == '') {
                                    echo '<span class="fullname-container">Guest<i class="fa-solid fa-angle-down"></i></span>';
                                } else {
                                // Handle the case where the session variable is not set
                                echo '<span class="fullname-container">' .$fetch['name']. '<i class="fa-solid fa-angle-down"></i></span>';
                                }
                                ?>
                        </div>


                        <ul class="profile-dropdown-list">
                            <li class="profile-dropdown-list-item">
                                <a href="update-admin.php">
                                <i class="fa-regular fa-user"></i>
                                Edit Profile
                                </a>
                            </li>

                            <li class="profile-dropdown-list-item">
                                <a href="admin-message.php">
                                <i class="fa-regular fa-envelope"></i>
                                Inbox
                                </a>
                            </li>

                            <hr />
                            <li class="profile-dropdown-list-item">
                                <a href="admin-login.php">
                                <i class="fa-solid faa fa-sign-out-alt"></i>
                                Log Out
                                </a>
                            </li>

                        </ul>
                    </div>
                </nav>

                <label for="check" class="bar">
                    <span class="fa fa-bars" id="bars"></span>
                    <span class="fa fa-close" id="close"></span>
                </label>
    <script src="homepage.js">
    </script>
</header>


<!-- order details start -->
<content>
<section class="package" id="package">
<div class="container">
        <h1 class="heading">
        <span class="travel-title"> <a href ="order-completed.php">Completed Order</a> ||</span>
        <span> <a href ="">Cancel Order</a></span>
        </h1>
        </div>
</section>

<?php
// Check if the form is submitted for updating payment status
if (isset($_POST['submit'])) {
    if (isset($_POST['order_id'], $_POST['payment_status'])) {
        $order_id = $_POST['order_id'];
        $payment_status = $_POST['payment_status'];

    // Use a prepared statement to prevent SQL injection
    $updateSql = "UPDATE `order` SET payment_status = ? WHERE order_id = ?";
    $update_stmt = $conn->prepare($updateSql);

    if ($update_stmt) {
        // Bind parameters
        $update_stmt->bind_param("ss", $payment_status, $order_id);

        // Execute the prepared statement
        if ($update_stmt->execute()) {
            echo "<script>
            alert('Update Payment Status Successful ! order_id={$order_id}');
            window.location.href = 'order-cancel.php?order_id={$order_id}';
          </script>";
        } else {
            $update_error = "Failed to update payment status. Please try again.";
        }

            // Close the statement
            $update_stmt->close();
        } else {
            $update_error = "Failed to prepare the update statement.";
        }
    } else {
        $update_error = "Missing order_id or payment_status in the form submission.";
    }
}

// Fetch all orders for the admin panel
$select_orders = $conn->query("SELECT * FROM `order` WHERE payment_status = 'cancel'");
if ($select_orders) {
    // Check if there are rows in the result set
    if ($select_orders->num_rows > 0) {
        // Fetch each row individually
        while ($row = $select_orders->fetch_assoc()) {
?>

<div class="checkout-form">
    <div class="checkout-container">
    <form action="" method="post">

    <div class="wishlist-content">
        <div class ="img-package-container">
        <?php
            if($row['package_image'] == '') {
                    echo '<img src = "uploaded_img/default_image.png">';
                }else {
                    echo '<img src="uploaded_img/' .$row['package_image'].'">';
                }
            ?>
        </div>

        <div class="package-details">
        <h1>
            <div class="package_name">
            <i class="fas fa-map-marker-alt"></i> <?php echo $row['package_name'];?>
            </div>
        </h1>

        <div class="details">
        <p>Details: <?php echo str_replace("\n",'<br>', $row['details']);?></p>
        </div>

        <div class="category">
        <h2>
            Category: <?php echo $row['category'];?>
        </h2>
        </div>

        <div class="destination">
        <h3>
            Destination: <?php echo $row['destination'];?>
        </h3>
        </div>

        <div class="day">
        <h4>
            Duration: <?php echo $row['day'];?> day
        </h4>
        </div>

        <div class="rating">
            <h5>
                Rating:          
                <?php
                $rating = $row['rating'];
                for ($i = 1; $i<=5; $i++) {
                    echo '<i class ="fas fa-star ' . ($i <= $rating ? 'filled' :'') .'"></i>';
                }
                ?> 
            </h5>
        </div>

        <div class="price">
            <h6>
                Price: $<?php echo $row['price'];?>
            </h6>
        </div>

        <div class="order-id">
            <h6>
                Order Package Number: <?php echo $row['order_id'];?>
            </h6>
        </div>
        </div>
</div>

<div class ="user-info">
    <div class="user">
    <h1 class="h1">User Info</h1>
    </div>

    <div class="user-container">

        <div class="fullname">
            <h2>
            <p><i class="fa-solid fa-user"></i>  Fullname: <?php echo $row['fullname'];?></p>
            </h2>
        </div>

        <div class="email">
            <h3>
            <p><i class="fa-solid fa-envelope"></i>  Email: <?php echo $row['email'];?></p>
            </h3>
        </div>

        <div class="passportnumber">
            <h4>
            <p><i class="fa-solid fa-passport"></i>  Passport Number: <?php echo $row['passportnumber'];?></p>
            </h4>
        </div>

        <div class="quantity">
            <h5>
                <label for="quantity"><i class="fa-solid fa-people-group"></i>  Number of People: </label>
                <span id="quantity"><?php echo $row['quantity']; ?></span>
            </h5>
        </div>

        <div class="total-price">
            <h6>
            <i class="fa-regular fa-money-bill-1"></i>  Total Price: $ 
            <span id="totalPrice">
            <?php echo $row['total_price'];?></span>
            </h6>
        </div>

        <div class="payment-method">
            <h6>
                <label for="payment_method"><i class="fa-regular fa-credit-card"></i>  Payment Method: </label>
                <span id="paymentMethod"><?php echo $row['payment_method'];?></span>
            </h6>
        </div>

        <div class="payment-status">
            <h6>
            <label for="payment_status">Payment Status: </label>
            <select class="paymentStatus" name="payment_status" onchange="updateColor(this)">
            <option value="pending" <?php echo ($row['payment_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="cancel" <?php echo ($row['payment_status'] == 'cancel') ? 'selected' : ''; ?>>Cancel</option>
            <option value="completed" <?php echo ($row['payment_status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
            </select>
            </h6>
        </div>

        <div class ="btn-container">
        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
        <button type="submit" name="submit">Submit Order</button>
        </div>

    </div>
</div>
            </form>
<?php } ?>
    </div>


</div>
<?php

    } else {
        echo "No orders found.";
    }

    // Free the result set
    $select_orders->free_result();
} else {
    echo "Error executing the query: " . $conn->error;
}
?>
</content>
<!-- order details start -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Set the color immediately on page load for all paymentStatus elements
        var statusDropdowns = document.querySelectorAll(".paymentStatus");
        statusDropdowns.forEach(function (dropdown) {
            updateColor(dropdown);
        });
    });

    function updateColor(dropdown) {
        var selectedStatus = dropdown.options[dropdown.selectedIndex].value;

        // Apply the color based on the selected option
        switch (selectedStatus) {
            case "pending":
                dropdown.style.color = "red";
                break;
            case "cancel":
                dropdown.style.color = "grey";
                break;
            case "completed":
                dropdown.style.color = "green";
                break;
            default:
                dropdown.style.color = ""; // Reset to default color
                break;
        }
    }
</script>
<div class="copyright">
        <p>&copy;Copyright 2023 fyp-UTS Company. || Designed and developed by Ooi Chun Yap | DIIS012022001.</p>
    </div>
</body>
</html>