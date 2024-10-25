<?php 

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)) {
    header('location:login.php');
};

if(isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:defaultpage.html');

}


if (isset($_GET['wishlist_id']) || isset($_GET['package_id'])) {
    $wishlist_id = isset($_GET['wishlist_id']) ? $_GET['wishlist_id'] : null;
    $package_id = isset($_GET['package_id']) ? $_GET['package_id'] : null;

    if ($wishlist_id) {
        $selectPackage = mysqli_query($conn, "SELECT w.*, r.fullname, r.email, r.passportnumber, r.wallet
            FROM wishlist w
            JOIN register r ON w.user_id = r.id
            WHERE w.id = '$wishlist_id'")
            or die('Query failed');
        
        if (mysqli_num_rows($selectPackage) > 0) {
            $packageDetails = mysqli_fetch_assoc($selectPackage);
        } else {
            echo "Wishlist Travel Package not found";
        }
}
}



if (isset($_POST['place_order'])) {{
$package_id = $packageDetails['package_id'];
$package_name = $packageDetails['package_name'];
$package_image = $packageDetails['package_image'];
$details = $packageDetails['details'];
$category = $packageDetails['category'];
$destination = $packageDetails['destination'];
$day = $packageDetails['day'];
$rating = $packageDetails['rating'];
$price = $packageDetails['price'];
$fullname = $packageDetails['fullname'];
$email = $packageDetails['email'];
$passportnumber = $packageDetails['passportnumber'];
$quantity = $_POST['quantity'];
$total_price = $quantity * $price;
$payment_method = $_POST['payment_method'];
$payment_status = $_POST['payment_status'];

$check_order_query = "SELECT * FROM `order` WHERE wishlist_id = ? AND user_id = ?";
$check_order_stmt = $conn->prepare($check_order_query);
$check_order_stmt->bind_param("ss", $wishlist_id, $user_id);
$check_order_stmt->execute();
$check_order_result = $check_order_stmt->get_result();

if ($check_order_result->num_rows > 0) {
    // Order with wishlist_id already exists, show a message
    echo "<script>
        alert('Order Already Exists !');
        window.location.href = 'wishlist.php?wishlist_id={$wishlist_id}';
    </script>";
} else {
}
$check_package_query = "SELECT * FROM `order` WHERE package_id = ? AND user_id = ?";
$check_package_stmt = $conn->prepare($check_package_query);
$check_package_stmt->bind_param("ss", $package_id, $user_id);
$check_package_stmt->execute();
$check_package_result = $check_package_stmt->get_result();

if ($check_package_result->num_rows > 0) {
    // Order with package_id already exists, show a message
    echo "<script>
        alert('Order Already Exists !');
        window.location.href = 'travel-package.php?package_id={$package_id}';
    </script>";
} else {

    $selected_payment_method = $_POST['payment_method'];
    if ($selected_payment_method === 'UTS Wallet') {
    $fetch_wallet_query = "SELECT wallet FROM `login` WHERE id = ?";
    $fetch_wallet_stmt = $conn->prepare($fetch_wallet_query);
    $fetch_wallet_stmt->bind_param("s", $user_id);
    $fetch_wallet_stmt->execute();
    $fetch_wallet_result = $fetch_wallet_stmt->get_result();

    if ($fetch_wallet_result->num_rows > 0) {
        $fetch_wallet_row = $fetch_wallet_result->fetch_assoc();
        $user_wallet = $fetch_wallet_row['wallet'];

        // Check if the user has enough money in the wallet
        if ($user_wallet >= $total_price) {
            // Deduct the amount from the wallet
            $updated_wallet = $user_wallet - $total_price;

            // Update the wallet in the login table
            $update_login_wallet_query = "UPDATE `login` SET wallet = ? WHERE id = ?";
            $update_login_wallet_stmt = $conn->prepare($update_login_wallet_query);
            $update_login_wallet_stmt->bind_param("ss", $updated_wallet, $user_id);
            $update_login_wallet_stmt->execute();

            // Update the wallet in the register table
            $update_register_wallet_query = "UPDATE `register` SET wallet = ? WHERE id = ?";
            $update_register_wallet_stmt = $conn->prepare($update_register_wallet_query);
            $update_register_wallet_stmt->bind_param("ss", $updated_wallet, $user_id);
            $update_register_wallet_stmt->execute();
        } else {
            // User does not have enough money in the wallet
            echo "<script>
                    alert('You do not have enough money in your wallet.');
                    window.location.href = 'wishlist.php?user_id={$user_id}';
                  </script>";
            exit;
        }
    }
}

$select_cart = $conn->prepare("SELECT quantity FROM `order` WHERE wishlist_id = ? or package_id = ? AND user_id = ?");
$select_cart->bind_param("sss", $wishlist_id, $package_id, $user_id);
$select_cart->execute();

$result_set = $select_cart->get_result();

if ($result_set->num_rows > 0) {
    $cart_data = $result_set->fetch_assoc();
    $initial_quantity = $cart_data['quantity'];
}else {

    $insert_query = "INSERT INTO `order` (wishlist_id, user_id, package_id, package_name, package_image, destination, category, details, day, price, rating, fullname, email, passportnumber, quantity, total_price, payment_method, payment_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("ssssssssssssssssss", $wishlist_id, $user_id, $package_id, $package_name, $package_image, $destination, $category, $details, $day, $price, $rating, $fullname, $email, $passportnumber, $quantity, $total_price, $payment_method, $payment_status);

    // Execute the prepared statement
    $insert_stmt->execute();

    if ($insert_stmt->affected_rows > 0) {
        $messageSuc[] = 'Order Successfull !';
        $insert_stmt->close();


    $select_order_id = $conn->prepare("SELECT order_id FROM `order` WHERE (wishlist_id = ? or package_id =?) AND user_id = ? ORDER BY order_id DESC LIMIT 1");
    $select_order_id->bind_param("sss", $wishlist_id, $package_id, $user_id);
    $select_order_id->execute();
    
    $result_order_id = $select_order_id->get_result();

    if ($row_order_id = $result_order_id->fetch_assoc()) {
        $order_id = $row_order_id['order_id'];

        // Redirect to your-travel.php with the order ID parameter
        echo "<script>
                alert('Order Successful !');
                window.location.href = 'your-travel.php?order_id={$order_id}';
              </script>";
        exit;

    } else {
        $messageErr[] = 'Failed to payment.';
    
}
}
$select_cart->close();
}
}
}
}
?>



<!doctype html>
<html lang="en" style="height:100%;">

<head> 
    <meta charset="UTF-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>UTS Travel/travel/package/checkout</title>
</head>

<body>
    <?php
    $select = mysqli_query($conn, "SELECT * FROM `register` WHERE id = '$user_id'")
    or die('query failed');
    if(mysqli_num_rows($select) > 0) {
        $fetch = mysqli_fetch_assoc($select);   
    }
    ?>
	<!--Top Bar-->
	<div class="wrapper_top_bar">
		<div class="container_top_bar">
			<div class="welcometraveler-bar" style="display: block;">
			
			<?php
            // Check if the session variable is set
            if ($fetch['fullname'] == '') {
            echo '<div class="text_top_bar">Welcome Traveler</div>';
            } else {
                echo '<div class="text_top_bar">Welcome ' .$fetch['fullname'].'</div>';
            }
            ?>

			</div>
		</div>	
	</div>	
	
    <!--Header-->
    <input type ="checkbox" id="check">
    <header>
	    <div class="logo">
            <img src="company logo UTS 1.png" alt="Company Logo">
            <h1 class ="h1"><a href ="homepage.php">Universal Travel Service <br>Company</a></h1> 
        </div>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                <div class ="search_box">
                    <input type="search" name="search" id="search" placeholder="Search...">
                    <div id ="searchResults"></div>
                    <button type="submit" class="search_button" value="search">
                    <img src="search logo 3.png" alt="search">
                    </button>
                    <script src="search.js"></script>
                </div>
                
                <nav>
                    <div class="profile-dropdown">
                        <div class="profile-dropdown-btn" onclick="toggle()">
                            <div class="profile-img">
                                <?php 
                                if($fetch['image'] == '') {
                                    echo '<img src = "uploaded_img/ACCOUNT LOGO PICTURE.png">';
                                }else {
                                    echo '<img src="uploaded_img/' .$fetch['image'].'">';
                                }
                                ?>
                                <i class="fa-solid fa-circle"></i>
                            </div>

                            <?php
                                // Check if the session variable is set
                                if ($fetch['fullname'] == '') {
                                    echo '<span class="fullname-container">Guest<i class="fa-solid fa-angle-down"></i></span>';
                                } else {
                                // Handle the case where the session variable is not set
                                echo '<span class="fullname-container">' .$fetch['fullname']. '<i class="fa-solid fa-angle-down"></i></span>';
                                }
                                ?>
                        </div>


                        <ul class="profile-dropdown-list">
                            <li class="profile-dropdown-list-item">
                                <a href="editprofile.php">
                                <i class="fa-regular fa-user"></i>
                                Edit Profile
                                </a>
                            </li>

                            <li class="profile-dropdown-list-item">
                                <a href="inbox.php">
                                <i class="fa-regular fa-envelope"></i>
                                Inbox
                                </a>
                            </li>

                            <li class="profile-dropdown-list-item">
                                <a href="wishlist.php">
                                <i class="fa-solid fa-heart"></i>
                                Wishlist
                                </a>
                            </li>

                            <li class="profile-dropdown-list-item">
                                <a href="your-travel.php">
                                <i class="fa-solid fa-cart-flatbed-suitcase"></i>
                                Your Travel
                                </a>
                            </li>

                            <li class="profile-dropdown-list-item">
                                <a href="help-and-support.php">
                                <i class="fa-regular fa-circle-question"></i>
                                Help & Support
                                </a>
                            </li>

                            <hr />
                            <li class="profile-dropdown-list-item">
                            <a href="defaultpage.html?logout=<?php echo $user_id; ?>">
                            <i class="fa-solid faa fa-sign-out-alt"></i>
                            LogOut
                            </a>
                            </li>

                        </ul>
                    </div>
                    <li>
                        <div class ="phone-container">
                        <span class="img-container">
                        <img src="phone logo 1.png" alt="Phone">
                        </span>
                        <a href="help-and-support.php">012-345 6789</a>
                        </div>
                      </li>
                </nav>

                <label for="check" class="bar">
                    <span class="fa fa-bars" id="bars"></span>
                    <span class="fa fa-close" id="close"></span>
                </label>
    <script src="homepage.js"></script>
    </header>

<!-- header 2 start -->
    <header2 class="header-2">

        <input type="checkbox" id="menu-bar">
        <label for="menu-bar">Menu</label>

        <img src ="website background.jpg" class="background-1">

        <div class="nav2">

            <ul>

                <li>
                    <a href ="travel-package.php">TRAVEL PACKAGE</a>
                </li>

                <li>
                    <a>EXPLORE ↓</a>
                        <ul>
                            <li><a href="explore-mountain.php">Mountain</a></li>
                            <li><a href="explore-seaside.php">Seaside</a></li>
                            <li><a href="explore-land.php">Land</a></li>
                        </ul>
                </li>

                <li>
                    <a>DURATION DAYS ↓</a>
                    <ul>
                        <li><a href="duration-3day.php">1days ~ 3days</a></li>
                        <li><a href="duration-8day.php">4days ~ 8days</a></li>
                        <li><a href="duration-12day.php">9days ~ 12days</a></li>
                        <li><a href="duration-15day.php">13days ~ 15days</a></li>
                    </ul>
                </li>

                <li>
                    <a>DESTINATION ↓</a>
                    <ul>
                        <li><a href="destination-europe.php">Europe</a></li>
                        <li><a href="destination-africa.php">Africa & Middle East</a></li>
                        <li><a href="destination-americas.php">The Americas</a></li>
                        <li><a href="destination-asia.php">Australia & Asia</a></li>
                    </ul>
                </li>
            
                <li>
                    <a>ABOUT UTS ↓</a>
                    <ul>
                        <li><a href="UTS-company.php">UTS Company</a></li>
                        <li><a href="UTS-company.php">Purpose Of Us</a></li>
                        <li><a href="UTS-company.php">Travel Style</a></li>
                    </ul>
                </li>

                <li><a href="help-and-support.php">HELP & CONTACT </a></li>
            </ul>
        </div>
    </header2>
<!-- header 2 end -->


<!--Body-2 Start-->
<content>
<section class="package" id="package">
<div class="container">
        <h1 class="heading">
            <span class="travel-title">CHECK OUT</span>
        </h1>
        <div class="wallet-header">
            <h2>Wallet</h2>
            <p>Total Money: <?php echo $packageDetails['wallet'];?><a href="wallet.php" class="addMoneyBtn">+</a></p>
        </div>
        </div>
</section>


<?php
    if(isset($messageSuc)) {
        foreach($messageSuc as $messageSuc) {
        echo '<div class="success-message">'.$messageSuc.'</div>';
        }
    }
    if(isset($messageErr)) {
        foreach($messageErr as $messageErr) {
        echo '<div class="error-message">'.$messageErr.'</div>';
        }
    }
    ?>

<div class="checkout-form">
    <div class="checkout-container">
    <form action="" method="post">


    <div class="wishlist-content">
        <div class ="img-package-container">
        <?php
            if($packageDetails['package_image'] == '') {
                    echo '<img src = "uploaded_img/default_image.png">';
                }else {
                    echo '<img src="uploaded_img/' .$packageDetails['package_image'].'">';
                }
            ?>
        </div>

        <div class="package-details">
        <h1>
            <div class="package_name">
            <i class="fas fa-map-marker-alt"></i> <?php echo $packageDetails['package_name'];?>
            </div>
        </h1>

        <div class="details">
        <p>Details: <?php echo str_replace("\n",'<br>', $packageDetails['details']);?></p>
        </div>

        <div class="category">
        <h2>
            Category: <?php echo $packageDetails['category'];?>
        </h2>
        </div>

        <div class="destination">
        <h3>
            Destination: <?php echo $packageDetails['destination'];?>
        </h3>
        </div>

        <div class="day">
        <h4>
            Duration: <?php echo $packageDetails['day'];?> day
        </h4>
        </div>

        <div class="rating">
            <h5>
                Rating:          
                <?php
                $rating = $packageDetails['rating'];
                for ($i = 1; $i<=5; $i++) {
                    echo '<i class ="fas fa-star ' . ($i <= $rating ? 'filled' :'') .'"></i>';
                }
                ?> 
            </h5>
        </div>

        <div class="price">
            <h6>
                Price: $<?php echo $packageDetails['price'];?>
            </h6>
        </div>

        <div class="order-id">
            <h6>
            <input type="hidden" name="order_id" value="' . $row['order_id'] . '">
                Order Package Number: <?php echo $packageDetails['id'];?>
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
            <p><i class="fa-solid fa-user"></i>  Fullname: <?php echo $packageDetails['fullname'];?></p>
            </h2>
        </div>

        <div class="email">
            <h3>
            <p><i class="fa-solid fa-envelope"></i>  Email: <?php echo $packageDetails['email'];?></p>
            </h3>
        </div>

        <div class="passportnumber">
            <h4>
            <p><i class="fa-solid fa-passport"></i>  Passport Number: <?php echo $packageDetails['passportnumber'];?></p>
            </h4>
        </div>

        <div class="quantity">
            <h5>
                <label for="quantity"><i class="fa-solid fa-people-group"></i>  Number of People: </label>
                <input type="number" id="quantity" name="quantity" value="<?php echo $initial_quantity['quantity']; ?>" 
                    min="1" max="15" oninput="calculateTotalPrice()"
                    onkeydown="return false" onpaste="return false">
            </h5>
        </div>

        <div class="total-price">
            <h6>
            <i class="fa-regular fa-money-bill-1"></i>  Total Price: $ 
            <span id="totalPrice">
            <?php echo $initial_quantity['quantity'] ?? 0 * $packageDetails['price'];?></span>
            </h6>
        </div>

        <div class="payment-method">
            <h6>
                <label for="payment_method">Payment Method: </label>
                <select id="paymentMethod" name="payment_method">
                    <option value="" disabled selected>Select your payment method</option>
                    <option value ="Credit Card">Credit Card</option>
                    <option value ="Debit Card">Debit Card</option>
                    <option value ="UTS Wallet">UTS Wallet</option>
                </select>
            </h6>
        </div>

        <div class="payment-status">
            <h6>
                <label for="payment_status">Payment Status: </label>
                <select id="paymentStatus" name="payment_status">
                    <option value="pending" selected>Pending</option>
                    <option value="cancel">Cancel</option>
                    <option value="completed">Completed</option>
                </select>
            </h6>
        </div>

    </div>
</div>
<script>
    function calculateTotalPrice() {
        var quantity = parseInt(document.getElementById('quantity').value) || 0;
        var price = <?php echo $packageDetails['price']; ?>;
        var totalPrice = quantity * price;

        document.getElementById('totalPrice').innerText = totalPrice;
    }
</script>

<div class ="btn-container">
<button type="submit" name="place_order">Place Order</button>
</div>
    </form>
    </div>
</div>
</content>
<!--Body-2 End-->


<!-- Footer -->
    <footer class="footer">
        <div class ="footer-container">
            <div class="row">

                <div class ="footer-col">
                    <h4> company</h4>
                    <ul>
                        <li><a href="UTS-company.php">about UTS company</a></li>
                        <li><a href="UTS-company.php">purpose of us</a></li>
                        <li><a href="UTS-company.php">travel style</a></li>
                    </ul>
                </div>

                <div class ="footer-col">
                    <h4> get help</h4>
                    <ul>
                        <li><a href="help-and-support.php">help</a></li>
                        <li><a href="help-and-support.php">contact us</a></li>
                        <li><a href="help-and-support.php">identity informaiton</a></li>
                        <li><a href="help-and-support.php">cancel package</a></li>
                        <li><a href="help-and-support.php">payment options</a></li>
                    </ul>
                </div>

                <div class ="footer-col">
                    <h4> travel package</h4>
                    <ul>
                        <li><a href="explore-mountain.php">Mountain</a></li>
                        <li><a href="explore-seaside.php">Seaside</a></li>
                        <li><a href="explore-land.php">Land</a></li>
                    </ul>
                </div>

                <div class ="footer-col">
                    <h4> follow us</h4>
                    <div class ="social-links">
                        <a href="https://www.facebook.com/profile.php?id=100011997855735&mibextid=ZbWKwL"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.discordapp.com/users/913331331398770739"><i class="fab fa-discord"></i></a>
                        <a href="https://www.instagram.com/junye_0110?igsh=NGVhN2U2NjQ0Yg=="><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/qr/T45IG5OQ67UGE1"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var searchInput = document.getElementById("search");
        var searchResults = document.getElementById("searchResults");

        searchInput.addEventListener("input", function () {
            var searchTerm = searchInput.value.trim();
            if (searchTerm.length > 0) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "search.php?term=" + searchTerm, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        searchResults.innerHTML = xhr.responseText;
                    }
                };
                xhr.send();
            } else {
                searchResults.innerHTML = "";
            }
        });

        document.addEventListener("click", function (e) {
            if (!e.target.matches(".search_box")) {
                searchResults.innerHTML = "";
            }
        });
    });
</script>
    <div class="copyright">
        <p>&copy;Copyright 2023 fyp-UTS Company. || Designed and developed by Ooi Chun Yap | DIIS012022001.</p>
    </div>
</body>
</html>