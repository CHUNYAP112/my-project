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
    <link rel="stylesheet" type="text/css" href="package.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>ADMIN SYSTEM/package</title>
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
            <li> <a href="package.php">Travel Package</a></li>
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
<section class="package" id="package">
    <h1 class="heading">
        <span> <a href ="add-package.php">Add Package</a> ||</span>
        <span> <a href ="package.php">Travel Package</a></span>
    </h1>

    <div class="box-container">
        
        <?php
        $sql= "SELECT * FROM travelpackage ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
        ?>     
        <div class="box">
            <div class="img-package-container">
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
            <div class="category"><strong>Category:  </strong><?php echo $row['category'];?></div> 
            <div class="destination"><strong>Destination: </strong><?php echo $row['destination'];?></div>
            <div class="Days"><strong>Days: </strong><?php echo $row['day'];?> Day</div>
            <div class="star">
                <?php
                $rating = $row['rating'];
                for ($i = 1; $i<=5; $i++) {
                    echo '<i class ="fas fa-star ' . ($i <= $rating ? 'filled' :'') .'"></i>';
                }
                ?>
            </div>
            <div class="price"> $<?php echo number_format($row['price'], 2);?> </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $delete = mysqli_query($conn, "DELETE FROM `travelpackage` WHERE `id` = '$id'");
        if ($delete) {
            echo "Account deleted successfully.";
        }else {
            echo "Error deleting account. ". mysqli_error($conn);        
        }
        exit();
        }   
        ?>

            <div class="button-container">
                <a class="edit-button" href="package-edit.php?package_id=<?php echo $row['id'];?>">Edit</a>
                <a class="delete-button" href="" onclick="deletePackage(<?php echo $row['id'];?>);">Delete</a>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script>
    function deletePackage(PackageId) {
        var confirmation = confirm("Are you sure you want to delete this travel package?");
        
        if (confirmation) {
            $.ajax({
                type: "POST",
                url: "",
                data: { id: PackageId },
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
        }else {
            echo 'No data found';
        }
    ?>
    </div>
</section>
</content>
<footer>
<div class="copyright">
        <p>&copy;Copyright 2023 fyp-UTS Company. || Designed and developed by Ooi Chun Yap | DIIS012022001.</p>
    </div>
    </footer>
</body>
</html>