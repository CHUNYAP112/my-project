<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(isset($_POST['update_btn'])) {

    $update_fullname = mysqli_real_escape_string($conn, $_POST['update_fullname']);
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
    $update_passportnumber = mysqli_real_escape_string($conn, $_POST['update_passportnumber']);

    mysqli_query($conn, "UPDATE `register` SET fullname = '$update_fullname', email='$update_email', passportnumber='$update_passportnumber' 
    WHERE id = '$user_id'") or die('query failed');

    mysqli_query($conn, "UPDATE `login` SET email='$update_email', passportnumber='$update_passportnumber' 
    WHERE id = '$user_id'") or die('query failed');
    $messageSuc[]="User Information Updated.";

    $old_password = $_POST['old_password'];
    $update_password = mysqli_real_escape_string($conn, $_POST['update_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if(!empty($update_password) || !empty($new_password) || !empty($confirm_password)) {
        if($update_password != $old_password) {
            $messageErr[] = 'old password not match!';
        }elseif ($new_password != $confirm_password) {
            $messageErr[] = 'confirm password not matched!';
        }else {
            mysqli_query($conn, "UPDATE `register` SET password = '$confirm_password' 
            WHERE id='$user_id'") or die('query failed');

            mysqli_query($conn, "UPDATE `login` SET password = '$confirm_password' 
            WHERE id='$user_id'") or die('query failed');

            $messageSuc[] = 'password updated successfully !';
        }
    }

    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'uploaded_img/'.$update_image;

    if(!empty($update_image)) {
        if($update_image_size > 2000000) {
            $messageErr[] = 'image is too large';
        }else {
            $image_update_query = mysqli_query($conn, "UPDATE `register` SET image = '$update_image' WHERE id = '$user_id'")
            or die('query failed');

            $image_update_query = mysqli_query($conn, "UPDATE `login` SET image = '$update_image' WHERE id = '$user_id'")
            or die('query failed');

            if($image_update_query) {
                move_uploaded_file($update_image_tmp_name, $update_image_folder);
            }
            $messageSuc[] = 'image updated successfully !';
        }
    }

}
?>



<!doctype html>
<html lang="en" style="height:100%;">

<head> 
    <meta charset="UTF-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="editprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>UTS Travel/home/edit</title>
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

                            <li class="profile-dropdown-list-item wishlist-item">
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
                                <a href="defaultpage.html">
                                <i class="fa-solid faa fa-sign-out-alt"></i>
                                Log Out
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
                            <li><a href="explore-mountain.php">Moutain</a></li>
                            <li><a href="explore-seaside.php">Seaside</a></li>
                            <li><a href="explore-land.php">Land</a></li>
                        </ul>
                </li>

                <li>
                    <a>TRAVEL PACKAGE ↓</a>
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
                        <li><a href="destination-americas.php">The Americasa</a></li>
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



<!--Body-2 Start-->
<?php
$select = mysqli_query($conn, "SELECT * FROM `register` WHERE id = '$user_id'")
    or die('query failed');
    if(mysqli_num_rows($select) > 0) {
    $fetch = mysqli_fetch_assoc($select);   
}
?>
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

<div class="update">
    <div class="edit-container">
        <div class="edit1">Edit Profile</div>
            <form action="" method="post" enctype="multipart/form-data">
            
            <div class="user-img">
            <?php
            if($fetch['image'] == '') {
                    echo '<img src = "uploaded_img/ACCOUNT LOGO PICTURE.png">';
                }else {
                    echo '<img src="uploaded_img/' .$fetch['image'].'">';
                }
            ?>
            </div>

                <div class="user-details">        
                    <div class="input-box">
                        <label for="fullname">Full Name</label>
                        <input type="text" placeholder="Update your name..." name="update_fullname" id="fullname"
                               value="<?php echo $fetch['fullname'] ?>" class="box">
                    </div>

                    <div class="input-box">
                        <label for="passportnumber">Passport Number</label>
                        <input type="text" placeholder="Update your name..." name="update_passportnumber" id="passportnumber"
                               value="<?php echo $fetch['passportnumber'] ?>" class="box">
                    </div>

                    <div class="input-box">
                        <label for="email">Email</label>
                        <input type="text" placeholder="Enter your email..." name="update_email" id="email"
                               value="<?php echo $fetch['email'] ?>" class="box">
                    </div>

                    <div class="input-box">
                        <label for="image">Update Your Picture</label>
                        <input type="file" name="update_image"
                        accept="image/jpg, image/jpeg, image/png" class="box">
                    </div>

                    <div class="input-box">
                        <input type="hidden" name="old_password" id="password"
                            value="<?php echo $fetch['password'] ?>">
                        <label for="password">Old Password</label>
                        <input type ="password" name="update_password" placeholder="Enter previous password..." 
                        class="box">
                    </div>

                    <div class="input-box">
                        <label for="password">New Password</label>
                        <input type="password" placeholder="Enter new password..." name="new_password" id="password" 
                        class="box">
                    </div>

                    <div class="input-box">
                        <label for="confirmpassword">Confirm Password</label>
                        <input type="password" placeholder="Confirm new password..." name="confirm_password"
                        class = "box">
                    </div>
                </div>

                <div class="gender-details">
                    <input type="radio" name="gender" value="Male" id="dot-1" <?php echo ($fetch['gender'] == 'Male') ? 'checked' : ''; ?>>
                    <input type="radio" name="gender" value="Female" id="dot-2" <?php echo ($fetch['gender'] == 'Female') ? 'checked' : ''; ?>>
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

                <div class="update-button">
                    <input type="submit" value="Update" name="update_btn">
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