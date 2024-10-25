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
    <link rel="stylesheet" type="text/css" href="admin-edit.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>ADMIN SYSTEM/edit</title>
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



<content>

<?php
// Check if admin_id is set in the URL
if (isset($_GET['admin_id'])) {
    $admin_id = $_GET['admin_id'];

    // Fetch admin information from the database
    $sql = "SELECT * FROM `admin` WHERE admin_id = $admin_id";
    $result = $conn->query($sql);

    // Check if a record is found
    if ($result->num_rows > 0) {
        $admin_data = $result->fetch_assoc();

        // Check if the update form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve form data
            $name = $_POST['name'];
            $password = $_POST['password'];
            $confirmpassword = $_POST['confirmpassword'];

            $updateSql = "UPDATE `admin` SET name = '$name' WHERE admin_id = $admin_id";

                if ($conn->query($updateSql) === TRUE) {
                    // Refresh the admin_data after the update
                    $admin_data['name'] = $name;
                } else {
                    echo "Error updating admin information: " . $conn->error;
                }
                $passwordUpdateSuccess = false;

                if (!empty($password)) {
                    if ($password != $confirmpassword) {
                        $messageErr[] = 'Confirm password not matched!';
                    }else {
                        $passwordUpdateQuery = mysqli_query($conn,"UPDATE `admin` SET password='$password' 
                        WHERE admin_id='$admin_id'") or die('password update query failed');
                        if ($passwordUpdateQuery) {
                            $passwordUpdateSuccess = true;
                            $messageSuc[] = 'Password Updated Successfully!';
                        }else {
                            $messageErr[] = 'Password Update Failed';
                        }
                    }
                }
                
                if ($updateSql && !$passwordUpdateSuccess) {
                    $messageSuc[] = 'Update Admin Name Successfully!';
                }elseif (!$updateSql && !$passwordUpdateSuccess) {
                    $messageErr[] = 'Update Failed';
                }
                }else {
                    $mmessageErr[] = 'No field to update!';
                }
    } else {
        // Handle the case where admin_id is not found
        echo "Admin not found";
        exit;
    }
} else {
    // Handle the case where admin_id is not set in the URL
    echo "Admin ID not provided";
    exit;
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
<div class="update-admin">
<div class="update-container">

<div class="admin-update">Update Profile</div>
        <form action="" method="post">
            
            <div class="admin-details">
                <div class="input-box">
                        <label for="name">Admin Name</label>
                    <input type="text" placeholder="Update admin name..." name="name" id="name" 
                    value="<?php echo $admin_data['name'] ?>"class="box" required>
                </div>

                <div class="input-box">
                        <label for="admin_id">Admin ID</label>
                    <input type="text" placeholder="Update admin ID..." name="admin_id" id="admin_id" 
                    value="<?php echo $admin_data['admin_id'] ?>"class="box" readonly>
                </div>

                <div class="input-box">
                        <label for="password">Password</label>
                    <input type="password" placeholder="Update password..." name="password" id ="password" class="box">
                </div>

                <div class="input-box">
                        <label for="confirmpassword">Confirm Password</label>
                    <input type="password" placeholder="Confirm your password..." name="confirmpassword" id="confirmpassword" class="box">
                </div>
            </div>

            <div class="update-button">
                <input type="submit" value="Update" name="update_btn">
            </div>
</div>
</div>
</content>
<div class="copyright">
        <p>&copy;Copyright 2023 fyp-UTS Company. || Designed and developed by Ooi Chun Yap | DIIS012022001.</p>
    </div>
</body>
</html>