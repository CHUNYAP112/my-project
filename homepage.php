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
?>


<!doctype html>
<html lang="en" style="height:100%;">

<head> 
    <meta charset="UTF-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>UTS Travel/home</title>
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
                    <a>DURATION DAY ↓</a>
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

                <li><a href="help-and-support.php">HELP & CONTACT</a></li>
            </ul>

        </div>
    </header2>
<!-- header 2 end -->


<!-- body-1 start -->
    <div class="content-1">
        <div class="body-1">
            <h2> EXPLORE YOUR TRAVEL PACKAGE </h2>
        </div>
    </div>
<!-- body-1 end -->


<!--Body-2 Start-->
    <div class="content-2">

            <div class="places-2">
                <img src= "mountain-1.png">
                <h1>Mountain</h1>
                <p>Let's go look amazing mountain in the world!!!</p>
                <p><a href="explore-mountain.php">Learn more</a></p>
            </div>

            <div class="places-2">
                <img src= "seaside-1.png">
                <h1>Seaside</h1>
                <p>Let's go relax at seaside!!!</p>
                <p><a href="explore-seaside.php">Learn more</a></p>
            </div>

            <div class="places-2">
                <img src= "land-1.png">
                <h1>Land</h1>
                <p>Let's go enjoy at amazing place of others country!!!</p>
                <p><a href="explore-land.php">Learn more</a></p>
            </div>
    </div>

    <div class="content-3">
        <div class="places-3">
            <img src= "iceland-3.png">
                <div class ="text-3">
                    <h1>Iceland Adventure Package</a></h1>
                    <p>Explore the beauty of Iceland with our exciting adventure package!</p>
                    <h2>Category: <a href="explore-land.php">Land</a></h2>
                    <h3>Duration: Days 1 ~ Days 13<br></h3>
                    
                    <p>
                        Day 1 ~ Day 4: Arrival in Reykjavi<br>
                        Day 5 ~ Day 8: Golden Circle Tour<br>
                        Day 9 ~ Day 11: South Coast Adventure<br>
                        Day 12 ~ Day 13: Departure<br>
                    </p>

                    <h4><strong>Package Includes: </strong><br></h4>
                    <p>Accommodations in well-reviewed hotels.<br>
                    Breakfast included daily.
                    Guided tours and activities as per itinerary.
                    Entrance fees to attractions.
                    Transportation between locations. <strong><a href ="travel-package.php">Learn More</a></strong>
                    </p>
                </div>
        </div>

        <div class="places-3">
            <img src= "switzerland-3.png">
                <div class="text-3">
                    <h1>Switzerland Explorer Package</a></h1>
                    <p>Explore the beauty of Switzerland with our exciting adventure package!</p>
                    <h2>Category: <a href="explore-land.php">Land</a></h2>
                    <h3>Duration: Days 1 ~ Days 8<br></h3>
                        
                    <p>
                        Day 1 ~ Day 2: Arrival in Zurich<br>
                        Day 3 ~ Day 5: Luncerne Day Trip<br>
                        Day 6 ~ Day 7: Interlaken Adventure<br>
                        Day 8 ~ Day 10: Jungfraujoch Excursion<br>
                        Day 10 ~ Day 11: Departure<br>
                    </p>

                    <h4><strong>Package Includes: </strong><br></h4>
                    <p>Accommodations in well-reviewed hotels.<br>
                    Breakfast included daily.
                    Guided tours and activities as per itinerary.
                    Entrance fees to attractions.
                    Transportation between locations, including train rides.
                    Airport transfers. <strong><a href ="travel-package.php">Learn More</a></strong>
                    </p>
                </div>
            </div>
        </div>
<!--Body-2 End-->


<!-- Footer -->
    <footer class="footer">
        <div class ="footer-container">
            <div class="row">

                <div class ="footer-col">
                    <h4>company</h4>
                    <ul>
                        <li><a href="UTS-company.php">about UTS company</a></li>
                        <li><a href="UTS-company.php">our service</a></li>
                        <li><a href="UTS-company.php">travel style</a></li>
                    </ul>
                </div>

                <div class ="footer-col">
                    <h4>get help</h4>
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
                    <h4>follow us</h4>
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