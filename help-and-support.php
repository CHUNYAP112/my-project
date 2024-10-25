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


<!doctype html>
<html lang="en" style="height:100%;">

<head> 
    <meta charset="UTF-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="help-and-support.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>UTS Travel/home/help & support</title>
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
                            <li><a href="explore-mountain.php">Moutain</a></li>
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

                <li><a href="help-and-support.php">HELP & CONTACT</a></li>
            </ul>

        </div>
    </header2>
<!-- header 2 end -->


<!--Body-2 Start-->

<div class="container">
        <h1 class="heading">
            <span class="travel-title">Help & Support</span>
        </h1>
        </div>


<!--HELP CONTENT START-->
<div class="about-container">
    <section class="about-message">
        <div class="about-image">
            <img src="contact.png" alt="Global Trave Image">
        </div>

        <div class="about-details">
        <div class="about_content">
        <h2>Any Help?</h2>
        <p> Welcome to our support center! Whether you have questions about our travel package, need assistance with an order, or just want to say hello, our dedicated team is here to help you. 
            We strive to provide prompt and friendly support to ensure your experience with our services is seamless. 
            Your satisfaction is our top priority, and we look forward to assisting you with any inquiries you may have.
            For any inquiries or assistance, you can contact us through the following options:</p>
    
        <ul>
            <li><strong>Email:</strong> UTS@gmail.com</li>
            <li><strong>Phone:</strong> 012-345 6789</li>
        </ul>
    </div>
    </div>
    </section>
</div>
<!--HELP CONTENT END-->


<!--SUPPORT CONTENT START-->
<div class="about-container">
    <section class="about-message">

        <div class="about-details">
        <div class="about_content">
        <h2>Any Suggestion & Comments?</h2>
        <p>We value your feedback and appreciate the opportunity to hear from you. Your suggestions and comments play a crucial role in helping us enhance our services and create a better experience for you. Whether you have ideas for improving our products, want to share your thoughts about your recent experience, or have general comments to make, we encourage you to reach out.
           Your suggest is important to us, and we're committed to considering every suggestion seriously. To share your thoughts, simply send us a message through our dedicated messaging platform. We look forward to hearing from you and thank you for being a valuable part of our community.
           If you have any feedback or comments to share, please send us a message at down.</p>
    </div>
    </div>

    <div class="about-image2">
            <img src="support1.png" alt="Global Trave Image">
        </div>
    </section>
</div>
<!--SUPPORT CONTENT END-->

<!--PAYMENT METHOD END-->
<div class="about-container">
    <section class="about-message">
        <div class="about-image">
            <img src="payment.method.png" alt="Global Trave Image">
        </div>

        <div class="about-details">
        <div class="about_content">
        <h2>Payment Method</h2>
        <p>At UTS, we've streamlined the payment process to offer three convenient options: 
            the <span style="font-weight:bold; color:black;">UTS Wallet</span> for swift and hassle-free transactions, 
            <span style="font-weight:bold; color:black;">credit cards</span> for flexibility with added security, 
            and <span style="font-weight:bold; color:black;">debit cards</span> for those who prefer direct debit convenience. 
            Rest assured, our commitment to security remains paramount, ensuring a worry-free and user-friendly payment experience as you embark on your travel adventures with us.</p>
    </div>
    </div>
    </section>
</div>
<!--PAYMENT METHOD END-->


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $fullname = mysqli_real_escape_string($conn, $_POST["fullname"]);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $passportnumber = mysqli_real_escape_string($conn, $_POST["passportnumber"]);
    $message = mysqli_real_escape_string($conn, $_POST["message"]);

    $checkUserQuery = "SELECT * FROM register WHERE fullname = '$fullname' AND email = '$email' AND passportnumber = '$passportnumber'";
    $result = $conn->query($checkUserQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['id'];

        $insertQuery = $conn->prepare("INSERT INTO messagebox (user_id, fullname, email, passportnumber, message) VALUES (?, ?, ?, ?, ?)");
        $insertQuery->bind_param("sssss", $user_id, $fullname, $email, $passportnumber, $message);

        if ($insertQuery->execute()) {
            echo "<script>alert('Sent Message Successful!');
            window.location.href = 'help-and-support.php?user_id={$user_id}';
            </script>";
        } else {
            echo "<script>alert('Sent Message Failed!');</script>";
        }
    } else {
        echo "<script>alert('User Informaiton not correct.');</script>";
    }
}
?>
<!-- MESSAGE BOX START -->
<div class="message-box-container">
    <div class="message">
    <div class="image-message-box-container">
        <img src="message.png">
    </div>

        <div class="message-box-content">
        <div class="inner-container">
            <h1>Send Us Message Box!</h1>

            <form id="contact-form" action="" method="post">

            <input type="text" class="message-details" id="fullname" name="fullname" placeholder="Enter your name..." required>
            <input type="email" class="message-details" id="email" name="email" placeholder="Enter your email..." required>
            <input type="text" class="message-details" id="passportnumber" name="passportnumber" placeholder="Enter your passportnumber" required>
            <textarea id="message" name="message" rows="5" placeholder="Enter your message..." required></textarea>

            <button type="submit" class="submit-message" name="submit">Submit</button>
            </form>

        </div>
        </div>
    </div>
</div>
<!--MESSAGE BOX END-->


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