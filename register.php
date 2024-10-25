<?php

include 'config.php';

if (isset($_POST['submit'])) {

$fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
$passportnumber = mysqli_real_escape_string($conn, $_POST['passportnumber']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$confirmpassword = mysqli_real_escape_string($conn, $_POST['confirmpassword']);
$image = $_FILES['image']['name'];
$image_size = $_FILES['image']['size'];
$image_tmp_name = $_FILES['image']['tmp_name'];
$image_folder = 'uploaded_img/'.$image;
$gender = mysqli_real_escape_string($conn, $_POST['gender']);

$checkPassportNumberQuery = mysqli_query($conn, "SELECT * FROM `register` WHERE passportnumber = '$passportnumber'");
if (mysqli_num_rows($checkPassportNumberQuery) > 0) {
    $messageErr[] = 'Passport Number already exists. Choose a different passport number.';
}else {

$select = mysqli_query($conn, "SELECT * FROM `register` WHERE email = '$email' AND
password = '$password'") or die('query failed');

if(mysqli_num_rows($select) > 0) {
    $messageErr[] = 'User already exists';
    }else {
        if($password != $confirmpassword) {
            $messageErr[] = 'Confirm password not matched!';
        }elseif ($image_size > 2000000) {
            $messageErr[] = 'Image size is too large!';
        }else {
            $insertRegister = mysqli_query($conn, "INSERT INTO `register`(fullname, passportnumber, email, password, image, gender)
            VALUES('$fullname', '$passportnumber', '$email', '$password', '$image', '$gender')") or die('query failed');

            $user_id = mysqli_insert_id($conn);

            $insertLogin = mysqli_query($conn, "INSERT INTO `login` (id, passportnumber, email, password, image)
            VALUES('$user_id', '$passportnumber', '$email', '$password', '$image')") or die('query failed');


            if($insertRegister && $insertLogin) {
                move_uploaded_file($image_tmp_name, $image_folder);
                $messageSuc[] = 'Registered Successfully!';
            }else {
                $messageErr[] = 'Registered Failed!';
            }
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
    <link rel="stylesheet" type="text/css" href="register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>UTS Travel/Register</title>
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
                        <a href="#">012-345 6789</a>
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
<div class = "register">
    <div class="register-container">
        <div class="register1">Registration</div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="user-details">

                <div class="input-box">
                        <label for="fullname">Full Name</label>
                    <input type="text" placeholder="Enter your name..." name="fullname" id="fullname" class="box" required>
                </div>

                <div class="input-box">
                        <label for="passportnumber">Passport Number</label>
                    <input type="text" placeholder="Enter your passport number..." name="passportnumber" id="passportnumber" class="box" required>
                </div>

                <div class="input-box">
                        <label for="email">Email</label>
                    <input type="email" placeholder="Enter your email..." name="email" id ="email" class="box" required>
                </div>

                <div class="input-box">
                        <label for="password">Password</label>
                    <input type="password" placeholder="Enter your password..." name="password" id="password" class="box" required>
                </div>

                <div class="input-box">
                        <label for="confirmpassword">Confirm Password</label>
                    <input type="password" placeholder="Confirm your password..." name="confirmpassword" class="box" required>
                </div>

                <div class="input-box">
                        <label for="image">User Picture</label>
                    <input type="file" name="image"class="box" accept="image/jpg, image/jpeg, image/png">
                </div>
            </div>

            <div class="gender-details">
                <input type="radio" name="gender" value="Male" id="dot-1" required>
                <input type="radio" name="gender" value="Female" id="dot-2" required>
                <span class="gender-title">Gender</span>
                <div class="category">
                    <label for="dot-1">
                        <span class="dot one"></span>
                        <span class="gender">Male</span>
                    </label>
                    <label for="dot-2">
                        <span class="dot two"></span>
                        <span class="gender">Female</span>
                    </label>
                </div>
            </div>

            <div class="register-button">
                <input type="submit" value="Register" class="btn" name="submit">
            </div>
            <p>already have an account? <a href="login.php">Login Now</a></p>

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
                        <li><a href="#">about UTS company</a></li>
                        <li><a href="#">purpose of us</a></li>
                        <li><a href="#">travel style</a></li>
                    </ul>
                </div>

                <div class ="footer-col">
                    <h4>get help</h4>
                    <ul>
                        <li><a href="#">help</a></li>
                        <li><a href="#">contact us</a></li>
                        <li><a href="#">identity informaiton</a></li>
                        <li><a href="#">cancel package</a></li>
                        <li><a href="#">payment options</a></li>
                    </ul>
                </div>

                <div class ="footer-col">
                    <h4>travel package</h4>
                    <ul>
                        <li><a href="#">Mountain</a></li>
                        <li><a href="#">Seaside</a></li>
                        <li><a href="#">Land</a></li>
                    </ul>
                </div>

                <div class ="footer-col">
                    <h4>follow us</h4>
                    <div class ="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
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

