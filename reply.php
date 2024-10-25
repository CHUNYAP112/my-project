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
    <link rel="stylesheet" type="text/css" href="reply.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>ADMIN SYSTEM/admin/reply</title>
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


<!--Body-2 Start-->
<content>
<section class="package" id="package">
<div class="container">
        <h1 class="heading">
            <span class="travel-title">Reply User Message</span>
        </h1>
</div>
</section>




<!-- MESSAGE BOX START -->
<div class="message-box-container">
    <div class="message">
        
    <div class="user-message-info">
    <?php
    if (isset($_GET['message_id'])) {
        $message_id = intval($_GET['message_id']);

        $selecQuery = "SELECT * FROM messagebox WHERE message_id = ?";
        $stmt = $conn->prepare($selecQuery);
        $stmt->bind_param("s", $message_id);
        $stmt->execute();
    
        $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
        <div class="user-container">
        <h1>User ID: <?php echo $row['user_id'];?></h1>
        <h1>Fullname: <?php echo $row['fullname'];?></h1>
        <h1>Email: <?php echo $row['email'];?></h1>
        <h1>Passport Number: <?php echo $row['passportnumber'];?></h1>
        <h2>Message: </h2>
        <p>    
        <?php echo str_replace("\\r\\n", '<br>', $row['message']);?>
        </p>
        </div>
    </div>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_name = $_POST['admin_name'];
    $user_id = $_POST['user_id'];
    $message_id = $_POST['message_id'];
    $email = $_POST['email'];
    $message = $_POST['message']; 
    $reply_message = $_POST['reply_message'];

    $insertQuery = "INSERT INTO inbox (message_id, admin_name, user_id, email, message, reply_message) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssssss", $message_id, $admin_name, $user_id, $email, $message, $reply_message);

    if ($stmt->execute()) {
        echo "<script>
        alert('Reply submitted successfully!');
        window.location.href = 'admin-message.php?message_id={$message_id}';
        </script>";
    } else {
        echo "<script>
        alert('Error submitting reply !');
        </script>";
    }

}
?>
    <form method="post" action=""> <!-- Add a form with the appropriate action -->
    <?php
    $user_id_hidden = isset($row['user_id']) ? htmlspecialchars($row['user_id']) : 'N/A';
    $email_hidden = isset($row['email']) ? htmlspecialchars($row['email']) : 'N/A';
    ?>

    <input type="hidden" name="user_id" value="<?php echo $user_id_hidden; ?>">
    <input type="hidden" name="email" value="<?php echo $email_hidden; ?>">
    <input type="hidden" name="user_id" value="<?php echo isset($row['user_id']) ? htmlspecialchars($row['user_id']) : 'N/A'; ?>">
    <input type="hidden" name="email" value="<?php echo isset($row['email']) ? htmlspecialchars($row['email']) : 'N/A'; ?>">
    <input type="hidden" name="message" value="<?php echo isset($row['message']) ? htmlspecialchars($row['message']) : 'N/A'; ?>">
    <input type="hidden" name="admin_name" value="<?php echo isset($fetch['name']) ? htmlspecialchars($fetch['name']) : 'N/A'; ?>">
    <input type="hidden" name="message_id" value="<?php echo isset($row['message_id']) ? htmlspecialchars($row['message_id']) : 'N/A'; ?>">
    

    <div class="reply-user-message">
        <div class="reply-container">

    <?php 
        if (isset($_GET['admin_id'])) {
        $admin_id = intval($_GET['admin_id']);

        // Assuming $conn is your mysqli connection object
        $select = $conn->prepare("SELECT * FROM admin WHERE admin_id = ?");
        $select->bind_param("i", $admin_id);
        $select->execute();
        $result = $select->get_result();

        if ($result->num_rows > 0) {
            $fetch = $result->fetch_assoc();
        } else {
            echo "No admin found with admin_id = $admin_id";
        }
        $select->close();
    }
    ?>

        <h3> Admin ID: <?php echo isset($fetch['admin_id']) ? htmlspecialchars($fetch['admin_id']) : 'N/A'; ?></h3>
        <h3> Admin Name: <?php echo isset($fetch['name']) ? htmlspecialchars($fetch['name']) : 'N/A'; ?></h3>
        <h4>
            <label for ="reply">Reply Message: </label> 
        </h4>
            <textarea placeholder="Enter anything you want to reply...." name="reply_message"
            id ="reply_message" required></textarea>
        </div>
    </div>
        <button type="submit" class="submit-message">Submit</button>
</form>
<?php
        }
    }
    }
    ?>
    </div>
    </div>

    <div class ="empty">

    </div>
</content>
<!--MESSAGE BOX END-->
<div class="copyright">
        <p>&copy;Copyright 2023 fyp-UTS Company. || Designed and developed by Ooi Chun Yap | DIIS012022001.</p>
    </div>
</body>
</html>