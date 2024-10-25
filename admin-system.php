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
    <link rel="stylesheet" type="text/css" href="admin-system.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>UTS Travel/ADMIN SYSTEM</title>
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
            <li> <a href="package.php">Travel</a></li>
            <li> <a href="order-details.php">Order</a></li>
            <li> <a href="user-details.php">User</a></li>
            <li> <a href="admin-details.php">Admin</a></li>
            <li> <a href="admin-message.php">Message</a></li>
        </div>

                <nav>
                    <div class="profile-dropdown">
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



<content>
<div class = "system">

    <div class ="system-container">

    <div class="box" id="package_box">
    <?php
            $package_total_query = "SELECT * FROM `travelpackage`";
            $package_total_query_run = mysqli_query($conn, $package_total_query);

            if($package_total = mysqli_num_rows($package_total_query_run)) {
                echo '<h1 class= "total-package"> '.$package_total.' </h1>';
            }else {
                echo '<h1 class="total-package"> No Data </h4>';
            }
        ?>
        <div class="inner-box">
            <div class="placeholder-box">Total Package</div>
        </div>
        <button class="box-button"><a href="package.php">Package Details</a></button>
    </div>

    <div class="box" id="order-pending-box">
    <?php
            $order_pending_total_query = "SELECT * FROM `order` WHERE payment_status = 'pending'";
            $order_pending_total_query_run = mysqli_query($conn, $order_pending_total_query);

            if($order_pending_total = mysqli_num_rows($order_pending_total_query_run)) {
                echo '<h1 class= "total-order-pending"> '.$order_pending_total.' </h1>';
            }else {
                echo '<h1 class="total-order-pending"> No Data </h4>';
            }
        ?>
        <div class="inner-box">
            <div class="placeholder-box">Order</div>
        </div>
            <button class="box-button"><a href="order-details.php">See Order</button>
    </div>

    <div class="box" id="order-completed-box">
    <?php
            $order_completed_total_query = "SELECT * FROM `order` WHERE payment_status = 'completed'";
            $order_completed_total_query_run = mysqli_query($conn, $order_completed_total_query);

            if($order_completed_total = mysqli_num_rows($order_completed_total_query_run)) {
                echo '<h1 class= "total-order-completed"> '.$order_completed_total.' </h1>';
            }else {
                echo '<h1 class="total-order-completed"> No Data </h4>';
            }
        ?>
        <div class="inner-box">
            <div class="placeholder-box">Order Completed</div>
        </div>
            <button class="box-button"><a href="order-completed.php">Total Order</button>
    </div>

    <div class="box" id="user-box">
        <?php
            $user_total_query = "SELECT * FROM `login`";
            $user_total_query_run = mysqli_query($conn, $user_total_query);

            if($user_total = mysqli_num_rows($user_total_query_run)) {
                echo '<h1 class= "total-user"> '.$user_total.' </h1>';
            }else {
                echo '<h1 class="total-user"> No Data </h4>';
            }
        ?>
        <div class="inner-box">
            <div class="placeholder-box">Total User</div>
        </div>
        <button class="box-button"><a href="user-details.php">User Details</a></button>
        </div>


    <div class="box" id="admin-box">
    <?php
            $user_total_query = "SELECT * FROM `admin`";
            $user_total_query_run = mysqli_query($conn, $user_total_query);

            if($user_total = mysqli_num_rows($user_total_query_run)) {
                echo '<h1 class= "total-user"> '.$user_total.' </h1>';
            }else {
                echo '<h1 class="total-user"> No Data </h4>';
            }
        ?>
        <div class="inner-box">
            <div class="placeholder-box">Total Admin</div>
        </div>
            <button class="box-button"><a href="admin-details.php">Admin Details</a></button>
    </div>

    <div class="box" id="message-box">
        <?php
            $message_total_query = "SELECT * FROM `messagebox`";
            $message_total_query_run = mysqli_query($conn, $message_total_query);

            if($message_total = mysqli_num_rows($message_total_query_run)) {
                echo '<h1 class= "total-message"> '.$message_total.' </h1>';
            }else {
                echo '<h1 class="total-message"> No Data </h4>';
            }
        ?>
        <div class="inner-box">
            <div class="placeholder-box">Total Message</div>
        </div>
            <button class="box-button"><a href="admin-message.php">New Message</button>
    </div>

</div>
</div>
</content>
<div class="copyright">
        <p>&copy;Copyright 2023 fyp-UTS Company. || Designed and developed by Ooi Chun Yap | DIIS012022001.</p>
    </div>
</body>
</html>