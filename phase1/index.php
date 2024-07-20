<?php session_start(); // Ensure session is started if not already started ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Responsive Registration Form</title>
</head>
<body>

<?php require 'header.php'; ?>


<div class="container">
    <header class="form_title">Registration</header>

    <form id="registrationForm" action="db_ops.php" method="post" enctype="multipart/form-data">
        <div class="form first">
            <div class="details personal">
                <span class="title">Personal Details</span>

                <div class="fields">
                    <div class="input-field">
                        <label>Full Name</label>
                        <input type="text" name="full_name" placeholder="Enter your name" required>
                    </div>

                    <div class="input-field username-container">
                        <label>Username</label>
                        <input id="username" type="text" name="username" placeholder="Enter your username" required>
                        <span id="already-taken-status" class="already-taken-status"></span>
                        <span id="available-status" class="available-status"></span>
                    </div>

                    <div class="input-field">
                        <label>Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        <span class="error" id="email-error"></span>
                    </div>

                    <div class="input-field">
                        <label>Mobile Number</label>
                        <input type="tel" name="mobile_number" placeholder="Enter your mobile number" required>
                        <span class="error"></span>
                    </div>


                    <div class="input-field has-error">
                        <label>Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        <span id="passwordMessage" class="error"></span>
                    </div>

                    <div class="input-field">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password"
                               placeholder="Confirm your password" required>
                        <!-- Include an empty span with the error class for spacing -->
                        <span class="error"></span>
                    </div>

                    <!-- <div class="error">
                        <span id="passwordMessage" class="error"></span>
                    </div> -->


                    <div class="input-field">
                        <label for="birthdate">Date of Birth</label>
                        <div class="date-input-container">
                            <input type="date" id="birthdate" name="birthdate" placeholder="Enter birth date"  min="1900-01-01" required>
                            <button class="show-popup" id="showDatePicker" type="button">Check</button>
                        </div>
                    </div>


                    <div class="input-field">
                        <label>Address</label>
                        <input type="text" name="address" placeholder="Enter your address" required>
                    </div>


                    <div class="photo">
                        <label>Upload Photo</label>
                        <input type="file" name="user_image" accept="image/*" required>
                    </div>
                    
                    <div class="success">
                        <?php
                        if (isset($_SESSION['success_message'])) {
                            echo('<p style="color: green;">' . $_SESSION['success_message'] . '</p>');
                        }
                        unset($_SESSION['success_message']);
                        ?>
                    </div>

                    <div>
                        <button class="btnText">
                            <span class="txt">Submit</span>
                            <i class="uil uil-navigator"></i>
                        </button>
                    </div>

                    <div class="popup-container">
                        <div class="popup-box">
                            <h1>Hey fellow birthday twins! 🎉🎂</h1>
                            <div id = "imagesContainer"></div>
                            <button class="close-btn">Done</button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>


<?php include 'footer.php'; ?>

<script src="script.js"></script>
</body>
</html>