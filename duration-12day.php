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

if(isset($_POST['book_now'])) {
    if(isset($_POST['package_id'])){
    $package_id = $_POST['package_id'];
    $package_name = $_POST['package_name'];
    $package_image = $_POST['package_image'];
    $details = $_POST['details'];
    $category = $_POST['category'];
    $destination = $_POST['destination'];
    $day = $_POST['day'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];

    $select_cart = $conn->prepare("SELECT * FROM `wishlist` WHERE package_id = ? AND package_name = ? AND user_id = ?");
    $select_cart->bind_param("sss", $package_id, $package_name, $user_id);
    $select_cart->execute();
    
    $result_set = $select_cart->get_result();

    if(mysqli_num_rows($result_set) > 0) {
        $_SESSION['messageErr'][]='Travel package already added to wishlist !';
    }else {
        // Insert the package into the cart
        $insert_query = "INSERT INTO `wishlist` (user_id, package_id, package_name, package_image, destination, category, details, day, price, rating)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("ssssssssss", $user_id, $package_id, $package_name, $package_image, $destination, $category, $details, $day, $price, $rating);

        // Execute the prepared statement
        $insert_stmt->execute();

        if ($insert_stmt->affected_rows > 0) {
            $_SESSION['messageSuc'][] = 'Travel package added to wishlist!';
        } else {
            $_SESSION['messageErr'][] = 'Failed to add product to wishlist.';
        }
        $insert_stmt->close();
    }
    $select_cart->close();
}
}
?>


<!doctype html>
<html lang="en" style="height:100%;">

<head> 
    <meta charset="UTF-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="duration-12day.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>UTS Travel/travel/package/12day</title>
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
    <h1 class="heading">
        <span>9 ~ 12 DAY Travel Package</span>
        
    </h1>
    <?php
    if(isset($_SESSION['messageSuc'])) {
        foreach($_SESSION['messageSuc'] as $messageSuc) {
        echo '<div class="success-message">'.$messageSuc.'</div>';
        }
        unset($_SESSION['messageSuc']);
    }

    if(isset($_SESSION['messageErr'])) {
        foreach($_SESSION['messageErr'] as $messageErr) {
        echo '<div class="error-message">'.$messageErr.'</div>';
        }
        unset($_SESSION['messageErr']);
    }
?>
    <div class="box-container">
        
        <?php
        $sql = "SELECT tp.* FROM travelpackage tp
        LEFT JOIN `order` o ON tp.id = o.package_id AND o.user_id = '$user_id'
        WHERE o.package_id IS NULL AND tp.day BETWEEN 9 AND 12";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
        ?>     
        <div class="box">
            <div class ="img-package-container">
        <?php
            if($row['package_image'] == '') {
                    echo '<img src = "uploaded_img/default_image.png">';
                }else {
                    echo '<img src="uploaded_img/' .$row['package_image'].'">';
                }
            ?>
        </div>

        <div class="content">
            <div class="package-name">   
            <h3>   
            <i class="fas fa-map-marker-alt"></i> <?php echo $row['package_name'];?> 
            </h3>
            </div>
            
            <div class="details"><?php echo str_replace("\n",'<br>', $row['details']);?></div>
            <div class="category">Category: <?php echo $row['category'];?></div> 
            <div class="destination">Destination: <?php echo $row['destination'];?></div>
            <div class="Days">Days: <?php echo $row['day'];?> Day</div>
            <div class="star">
                <?php
                $rating = $row['rating'];
                for ($i = 1; $i<=5; $i++) {
                    echo '<i class ="fas fa-star ' . ($i <= $rating ? 'filled' :'') .'"></i>';
                }
                ?>
            </div>
            <div class="price"> $<?php echo number_format($row['price'], 2);?> </div>
            
              
        <form method="post" action="">
            <input type="hidden" name="package_image" value="<?php echo $row['package_image']; ?>">
            <input type="hidden" name="package_name" value="<?php echo $row['package_name']; ?>">
            <input type="hidden" name="details" value="<?php echo str_replace("\n",'<br>',$row['details']); ?>">
            <input type="hidden" name="destination" value="<?php echo $row['destination']; ?>">
            <input type="hidden" name="category" value="<?php echo $row['category']; ?>">
            <input type="hidden" name="day" value="<?php echo $row['day']; ?>">
            <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
            <input type="hidden" name="rating" value="<?php echo $row['rating']; ?>">
            <input type="hidden" name="package_id" value="<?php echo $row['id']; ?>">

            <div class="book-now">
            <input type="submit" class="btn" value="Add to Wishlist" name="book_now">
            </div>
        </form>

        </div>
        </div>
        <?php
            }
        }else {
            echo 'No data found';
        }
    ?>
    </div>

    <div class="load-more-container">
    <div id="load-more"> Load More </div>
    <script>
        let loadMoreBtn = document.querySelector('#load-more');
        let currentItem = 6;

        loadMoreBtn.onclick = () => {
            let boxes = [...document.querySelectorAll('.package .box-container .box')];
            
            if (currentItem < boxes.length) {
            for (var i = currentItem; i < currentItem + 6 && i < boxes.length; i++) {
                boxes[i].style.display = 'inline-block';
            }
            currentItem += 6;
        } else {
            // If no more items to load, hide the "Load More" button
            loadMoreBtn.style.display = 'none';
        }
    }
    </script>
    </div>

</section>
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