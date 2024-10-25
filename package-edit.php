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
    <link rel="stylesheet" type="text/css" href="package-edit.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>ADMIN SYSTEM/package/edit</title>
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
        <span> <a href ="package.php">Travel Package </a></span>
    </h1>
</section>

<?php
if (isset($_GET['package_id'])) {
    $package_id = $_GET['package_id'];

$selectPackage = mysqli_query($conn, "SELECT * FROM `travelpackage` WHERE id = '$package_id'")
    or die('query failed');

    if(mysqli_num_rows($selectPackage) > 0) {
    $packageDetails = mysqli_fetch_assoc($selectPackage);   
}else {
    echo "Travel Package not found";
    exit;
}
}else {
    echo "Package ID not provided";
    exit;
}
?>
<?php
if (isset($_POST['submit'])) {
$package_name = $_POST['package_name'];
$details = $_POST['details'];
$category = $_POST['category'];
$destination = $_POST['destination'];
$day = $_POST['day'];
$rating = $_POST['rating'];
$price = $_POST['price'];

$updateSql = "UPDATE `travelpackage` SET package_name = '$package_name', details ='$details', category='$category', destination='$destination', day='$day', rating='$rating', price='$price'
    WHERE id = '$package_id'";

if ($conn->query($updateSql) === TRUE) {
    $packageDetails['package_name'] = $package_name;
    $packageDetails['details'] = $details;
    $packageDetails['category'] = $category;
    $packageDetails['destination'] = $destination;
    $packageDetails['day'] = $day;
    $packageDetails['rating'] = $rating;
    $packageDetails['price'] = $price;
    $messageSuc[]="Update Travel Package Information Successfully !";
    }else {
    $messageErr[] = 'Failed to update travel package. Please try again.';
    }
    
$package_image = $_FILES['package_image']['name'];
$image_size = $_FILES['package_image']['size'];
$image_tmp_name = $_FILES['package_image']['tmp_name'];
$image_folder = 'uploaded_img/'.$package_image;
if(!empty($package_image)) {
    if($image_size > 2000000) {
        $messageErr[] = 'image is too large';
    }else {
        $image_update_query = mysqli_query($conn, "UPDATE `travelpackage` SET package_image = ? WHERE id = ?")
        or die('query failed');

        if($image_update_query) {
            move_uploaded_file($image_tmp_name, $image_folder);
        }
        $messageSuc[] = 'image updated successfully !';
    }
}
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

<div class="add-package">
    <div class="box-container">
    <form action="" method="post" enctype="multipart/form-data">

    <div class="package-content">
        <div class="preview-box-container">
        <div class="preview-box" id="previewBox">
        <?php
            if($packageDetails['package_image'] == '') {
                    echo '<img src = "uploaded_img/default_image.png" class="preview-image" id="previewImage">';
                }else {
                    echo '<img src="uploaded_img/' .$packageDetails['package_image'].'"class="preview-image" id="previewImage">';
                }
            ?>
        </div>
        </div>

        <div class="input-box">
            <label for ="package_image">Package Image</label>
            <input type="file" name="package_image" class="box" accept="image/*" id=package_image onchange="getImagePreview(event)"
            value="<?php echo $packageDetails['package_image'];?>">
        </div>

        <div class="input-box">
            <label for="package_name">Package Name</label>
            <input type="text" placeholder="Enter package name..." name="package_name" id="package_name" class="box" 
            value="<?php echo $packageDetails['package_name'];?>" required>
        </div>

        <div class="input-box">
            <label for="category">Category</label>
            <select name="category" id="category" class="box" 
            value="<?php echo $packageDetails['category'];?>"required>
                <option value="Mountain">Mountain</option>
                <option value="Seaside">Seaside</option>
                <option value="Land">Land</option>
            </select>
        </div>

        <div class="input-box">
            <label for="Destination">Travel Destination</label>
            <input type="text" placeholder="Enter travel destination..." name="destination" id="destination" class="box" 
            value="<?php echo $packageDetails['destination'];?>"required>
        </div>

        <div class="input-box">
            <label for="day">Days</label>
            <input type="number" placeholder="Enter travel day..." name="day" id="day" class="box" 
            value="<?php echo $packageDetails['day'];?>"required>
        </div>

        <div class="input-box">
            <label for="rating">Rating</label>
            <input type="number" name="rating" min="1" max="5" class="box" 
            value="<?php echo $packageDetails['rating'];?>"required>
        </div>

        <div class="input-box">
            <label for="price">Package Price</label>
            <input type="text" placeholder="Enter package price..." name="price" id="price" class="box" 
            value="<?php echo $packageDetails['price'];?>"required>
        </div>

        <div class="input-box-details">
            <label for="details">Package Details</label>
            <textarea placeholder="Enter package details..." name="details" id="details" class="box" 
            required><?php echo $packageDetails['details'];?></textarea>
        </div>

        <div class="update-button">
            <input type="submit" value="Update Package" name="submit">
        </div>
            
        </div>
    </div>
    <script type="text/javascript">
        function getImagePreview(event) {
            var image= URL.createObjectURL(event.target.files[0]);
            document.getElementById('previewImage').src = image;
            document.getElementById('previewBox').style.display = 'block';
        }
</script>

</form>
</div>
</div>
</content>
<div class="copyright">
        <p>&copy;Copyright 2023 fyp-UTS Company. || Designed and developed by Ooi Chun Yap | DIIS012022001.</p>
    </div>
</body>
</html>