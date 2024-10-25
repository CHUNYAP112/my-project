<?php

include 'config.php';
session_start();

if (isset($_POST['submit'])) {

$name = mysqli_real_escape_string($conn, $_POST['name']);
$password = mysqli_real_escape_string($conn, ($_POST['password']));
$admin_id = mysqli_real_escape_string($conn, ($_POST['admin_id']));


$select = mysqli_query($conn, "SELECT * FROM `admin` WHERE name = '$name' AND
admin_id = '$admin_id' AND password = '$password'") or die('query failed');

if(mysqli_num_rows($select) > 0) {
    $row = mysqli_fetch_array($select);
    $_SESSION['admin_id'] = $row['admin_id'];
    header('location:admin-home.php');
    }else {
    $messageErr[] = 'incorrect name or password!';
    }
}
?>


<!doctype html>
<html lang="en" style="height:100%;">

<head> 
    <meta charset="UTF-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin-login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>UTS Travel/Admin/Login</title>
</head>

<body>
	<!--Top Bar-->
	<div class="wrapper_top_bar">
		<div class="container_top_bar">
			<div class="welcometraveler-bar" style="display: block;">
			
			<div class="text_top_bar"> Welcome Traveler</div>
			</div>
		</div>	
	</div>	
	
    <!--Header-->
    <input type ="checkbox" id="check">
    <header>
	    <div class="logo">
            <img src="company logo UTS 1.png" alt="Company Logo">
            <h1 class ="h1"><a href ="defaultpage.html">Universal Travel Service <br>Company</a></h1> 
        </div>
                <div class ="search_box">
                        <input type="search" placeholder="Search..." >
                        <button type="submit" class="search_button">
                        <img src="search logo 3.png" alt="Search">
                        </button>
                </div>
                
                <nav>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li>
                        <span class="img-container">
                        <img src="phone logo 1.png" alt="Phone" style="width: 22px; height: 22px; vertical-align: middle; margin-left: 5px;">
                        </span>
                        <a href="help-and-support.php">012-345 6789</a>
                      </li>
                </nav>

                <label for="check" class="bar">
                    <span class="fa fa-bars" id="bars"></span>
                    <span class="fa fa-close" id="close"></span>
                </label>
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
                            <li><a href="#">Moutain</a></li>
                            <li><a href="#">Seaside</a></li>
                            <li><a href="#">Land</a></li>
                        </ul>
                </li>

                <li>
                    <a>TRAVEL PACKAGE ↓</a>
                    <ul>
                        <li><a href="#">1days ~ 3days</a></li>
                        <li><a href="#">4days ~ 8days</a></li>
                        <li><a href="#">9days ~ 12days</a></li>
                        <li><a href="#">13days ~ 15days</a></li>
                    </ul>
                </li>

                <li>
                    <a>DESTINATION ↓</a>
                    <ul>
                        <li><a href="#">Malaysia</a></li>
                        <li><a href="#">United State</a></li>
                        <li><a href="#">IceLand</a></li>
                        <li><a href="#">Switzerland</a></li>
                    </ul>
                </li>
            
                <li>
                    <a>ABOUT UTS ↓</a>
                    <ul>
                        <li><a href="#">UTS Company</a></li>
                        <li><a href="#">Purpose Of Us</a></li>
                        <li><a href="#">Travel Style</a></li>
                    </ul>
                </li>

                <li><a href="#">HELP & CONTACT</a></li>
            </ul>

        </div>
    </header2>
<!-- header 2 end -->


<!--Body Start-->
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

<div class = "login-admin">
    <div class="login-admin-container">
        <div class="login-admin1">Admin Login</div>
        <form method="post" action="">
            
            <div class="admin-details">

                <div class="input-box">
                    <label for="name">Name</label>
                <input type="text" placeholder="Enter your name..." name="name" id="name" required>
            </div>

            <div class="input-box">
                    <label for="admin_id">Admin ID</label>
                <input type="text" placeholder="Enter your passport number..." name="admin_id" id="admin_id" required>
            </div>

                <div class="input-box">
                        <label for="password">Password</label>
                    <input type="password" placeholder="Enter your password..." name="password" id="password" required>
                </div>
            </div>

            <div class="login-button">
                <input type="submit" value="Login" name="submit">
            </div>

        </form>
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
                        <li><a href="UTS-company.php">purpose of us</a></li>
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
                    <h4>travel package</h4>
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

    <div class="copyright">
        <p>&copy;Copyright 2023 fyp-UTS Company. || Designed and developed by Ooi Chun Yap | DIIS012022001.</p>
    </div>
</body>
</html>
