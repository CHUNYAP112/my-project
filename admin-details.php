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
    <link rel="stylesheet" type="text/css" href="admin-details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Admin/details</title>
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
 $sql = "SELECT * FROM `admin`";
 $admin_profile = $conn->query($sql);
?>

<div class = "admin-details">
    <div class ="admin-container">

    <div class="box" id="update_box">
    <?php
        $getLastAdminQuery = mysqli_query($conn, "SELECT admin_id, name FROM `admin` ORDER BY admin_id DESC LIMIT 1");

        // Check if the query was successful
        if ($getLastAdminQuery) {
            // Fetch the result
            $lastAdminRow = mysqli_fetch_assoc($getLastAdminQuery);
        
            // Check if there is a result
            if ($lastAdminRow) {
            }
        }
        ?>
        <div class="update-admin">
        <p class="admin_id">Admin ID: <span class="php-text"><?php echo $lastAdminRow["admin_id"]; ?></span></p>
        <p class="name">Name: <span class="php-text"><?php echo $lastAdminRow["name"]; ?></span></p>
        </div>
    
        <div class="update-box">
            <div class="placeholder-box">New Admin</div>
        </div>
            <a class="box-button" href="register-admin.php">Register Admin</a>
    </div>


    <div class="box" id="update_box">
    <?php
    $row = mysqli_fetch_assoc($admin_profile);

    // Check if $row is not null and admin_id is 1
    if ($row !== null && $row["admin_id"] == 1) {
    ?>
        <div class="update-admin">
        <p class="admin_id"> Admin ID: <span class="php-text"><?php echo $row["admin_id"]; ?></span></p>
        <p class="name">Name: <span class="php-text"><?php echo $row["name"]; ?></span></p>
        </div>
    <?php
    }
    ?>
        
        <div class="update-box">
        <div class="placeholder-box">Main Admin Account</div>
        </div>
            <button class="box-button">Main Admin</button>
    </div>

    <div class="box" id="update_box">
        <?php
        // Check if the session variable is set
        if ($fetch['admin_id'] == '') {
        // Handle the case where the session variable is not set
        }
        ?>
        <div class="update-admin">
        <p class="admin_id">Admin ID: <span class="php-text"><?php echo $fetch["admin_id"]; ?></span></p>
        </div>

        <div class="update-box">    
        <div class="placeholder-box">Admin Account</div>
        </div>
        <a class="box-button" href="update-admin.php">Update Profile</a>
    </div>

    
<div class ="admin-account">
    <h1> All Admin Account </h1>
<?php
    $loggedInAdminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] :'null';

    while($row = mysqli_fetch_assoc($admin_profile)) { 
     if ($row["admin_id"] == $loggedInAdminId) {
        continue;
     }
    ?>
    <div class="box" id="admin-box">
        <div class="admin-box">
            <p class="admin_id">Admin ID: <span class="php-text"><?php echo $row["admin_id"]; ?></span></p>
            <p class="name">Name: <span class="php-text"><?php echo $row["name"]; ?></span></p>
        
        </div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_id'])) {
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $delete = mysqli_query($conn, "DELETE FROM `admin` WHERE `admin_id` = '$admin_id'");

        if ($delete) {
            echo "Account deleted successfully.";
        }else {
            echo "Error deleting account. ". mysqli_error($conn);        
        }
        exit();
        }   
        ?>
            <div class="button-container">
            <a class="edit-button" href ="admin-edit.php?admin_id=<?php echo $row['admin_id'];?>">Edit</a>    

            <a class="delete-button" href="admin-details.php" onclick="deleteAdmin(<?php echo $row['admin_id'];?>);">Delete</a>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    function deleteAdmin(adminId) {
        var confirmation = confirm("Are you sure you want to delete this admin account?");
        
        if (confirmation) {
            $.ajax({
                type: "POST",
                url: "",
                data: { admin_id: adminId },
                success: function (response) {
                    // Handle the success response
                    console.log(response);
                    // Optionally update the UI or perform any other actions
                },
                error: function (error) {
                    // Handle the error
                    console.log(error);
                }
            });
        }
        // Prevent the default behavior of the link (navigation)
        return false;
    }
</script>
        </div>
    </div>
<?php
    }
?>
</div>
    
</div>
</div>
</content>
<div class="copyright">
        <p>&copy;Copyright 2023 fyp-UTS Company. || Designed and developed by Ooi Chun Yap | DIIS012022001.</p>
    </div>
</body>
</html>