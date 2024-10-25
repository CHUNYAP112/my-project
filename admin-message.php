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
    <link rel="stylesheet" type="text/css" href="admin-message.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>ADMIN SYSTEM/admin/messagebox</title>
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
<div class = "user-details">
    <div class ="user-container">

<div class ="user-account">
    <h1> Message Box </h1>

<div class = "user-details">
    <div class ="user-container">

<?php
    $sql = "SELECT * FROM `messagebox`";
    $result = $conn->query($sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
                // Check if message_id exists in inbox table
                $messageId = $row["message_id"];
                $inboxCheckSql = "SELECT COUNT(*) as count FROM `inbox` WHERE `message_id` = $messageId";
                $inboxCheckResult = $conn->query($inboxCheckSql);
                $inboxCheckRow = mysqli_fetch_assoc($inboxCheckResult);
                
                // If message_id exists in inbox table, skip displaying this message
                if ($inboxCheckRow["count"] > 0) {
                    continue;
                }
    ?> 
    <div class="box" id="user-box">
        <div class="user-box">
            <p class="user_id">User ID: <span class="php-text"><?php echo $row["user_id"]; ?></span></p>
            <p class="fullname">Fullname: <span class="php-text"><?php echo $row["fullname"]; ?></span></p>
            <p class="passportnumber">Passport Number: <span class="php-text"><?php echo $row["passportnumber"]; ?></span></p>
            <p class="email">Email: <span class="php-text"><?php echo $row["email"]; ?></span></p>
            <p class="Message">Message: <span class="php-text"><?php echo str_replace("\\r\\n", '<br>', $row['message']);?></span></p>

        </div>


        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message_id'])) {
        $message_id = mysqli_real_escape_string($conn, $_POST['message_id']);
        $delete = mysqli_query($conn, "DELETE FROM `messagebox` WHERE `message_id` = '$message_id'");
        if ($delete) {
            echo "Account deleted successfully.";
        }else {
            echo "Error deleting account. ". mysqli_error($conn);        
        }
        exit();
        }   
        ?>
            <div class="button-container">
            <a class="edit-button" href ="reply.php?message_id=<?php echo $row['message_id'];?>admin_name=<?php echo $fetch['name'];?>">Reply</a>        
            <a class="delete-button" href="" onclick="deleteMessage(<?php echo $row['message_id'];?>);">Delete</a>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    function deleteMessage(MessageId) {
        var confirmation = confirm("Are you sure you want to delete this message?");
        
        if (confirmation) {
            $.ajax({
                type: "POST",
                url: "",
                data: { message_id: MessageId },
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