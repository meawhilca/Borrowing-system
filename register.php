<?php
session_start();
include("db.php"); // your database connection

$error = "";
$success = "";

// Handle form submission
if(isset($_POST['create_account'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if($password !== $confirm_password){
        $error = "Passwords do not match!";
    } else {
        // Check if username or email already exists
        $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
        if(mysqli_num_rows($check) > 0){
            $error = "Username or Email already exists!";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert into database
            $insert = mysqli_query($conn, "INSERT INTO users (username, email, password) VALUES ('$username','$email','$hashed_password')");
            if($insert){
                $success = "Account created successfully! <a href='login.php'>Login here</a>.";
            } else {
                $error = "Failed to create account. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account</title>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin:0;
    padding:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: url('images/library-bg.jpg') no-repeat center center/cover;
}

.register-box {
    background: rgba(255,255,255,0.95);
    padding:40px;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,0.3);
    width:350px;
    text-align:center;
}

.register-box h2 {
    color:#4a148c;
    margin-bottom:20px;
}

.register-box input {
    width:100%;
    padding:12px 15px;
    margin:10px 0;
    border:1px solid #ccc;
    border-radius:8px;
    outline:none;
}

.register-box input:focus {
    border-color:#6a1b9a;
    box-shadow:0 0 5px rgba(106,27,154,0.5);
}

.register-box button {
    width:100%;
    padding:12px;
    margin-top:15px;
    border:none;
    border-radius:8px;
    background:#6a1b9a;
    color:white;
    font-size:16px;
    cursor:pointer;
}

.register-box button:hover {
    background:#4a148c;
}

.error {
    color:red;
    margin-top:10px;
}

.success {
    color:green;
    margin-top:10px;
    font-size:14px;
}
</style>
</head>
<body>

<div class="register-box">
    <h2>Create Account</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit" name="create_account">Create Account</button>
    </form>

    <?php if($error != "") { ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>

    <?php if($success != "") { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php } ?>

    <p style="margin-top:15px;">Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>