<?php
session_start();
include("db.php");

$message = "";

if(isset($_POST['register'])){

    // Get inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Validate
    if(empty($username) || empty($email) || empty($password) || empty($role)){
        $message = "<div class='error'>All fields are required!</div>";
    } else {

        // Check if username or email already exists
        $check = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $result = $check->get_result();

        if($result->num_rows > 0){
            $message = "<div class='error'>Username or Email already exists!</div>";
        } else {


        

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email,$password, $role);

            if($stmt->execute()){
                $message = "<div class='message'>Account created successfully!</div>";
            } else {
                $message = "<div class='error'>Error creating account!</div>";
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
    background: url('https://images.unsplash.com/photo-1518837695005-2083093ee35b?auto=format&fit=crop&w=1350&q=80') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.login-box {
    background: rgba(255,255,255,0.85);
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0px 10px 25px rgba(0,0,0,0.3);
    width: 350px;
    text-align: center;
}

.login-box h2 {
    margin-bottom: 30px;
    color: #4a148c;
}

.login-box input,
.login-box select {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.login-box button {
    width: 100%;
    padding: 12px;
    margin-top: 15px;
    border: none;
    border-radius: 8px;
    background: #6a1b9a;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.login-box button:hover {
    background: #4a148c;
}

.message{
    margin-top:10px;
    color:green;
}

.error{
    margin-top:10px;
    color:red;
}

</style>

</head>
<body>

<div class="login-box">

<h2>Create Account</h2>

<form method="POST">

<input type="text" name="username" placeholder="Username" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>

<select name="role" required>
    <option value="">Select Role</option>
    <option value="student">Student</option>
    <option value="librarian">Librarian</option>
</select>

<button type="submit" name="register">Create Account</button>

</form>

<?php
if($message != ""){
    echo $message;
}
?>

<p style="margin-top:10px;">
Already have an account? <a href="login.php">Login</a>
</p>

</div>

</body>
</html>