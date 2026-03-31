<?php
session_start();
include("db.php");

$error = "";

if(isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    $role = mysqli_real_escape_string($conn,$_POST['role']);

    $query = "SELECT * FROM users 
              WHERE username='$username' 
              AND password='$password' 
              AND role='$role'";

    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) == 1) {

        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Redirect based on role
        if($role == "librarian"){
            header("Location: librarian/librarian_dashboard.php");
        } else {
            header("Location: student/students_dashboard.php");
        }
        exit();

    } else {
        $error = "Invalid Username, Password, or Role!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Library Login</title>

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

.error{
    color:red;
    margin-top:10px;
}

</style>

</head>
<body>

<div class="login-box">

<h2>Library System Login</h2>

<form method="POST">

<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>

<select name="role" required>
    <option value="">Select Role</option>
    <option value="student">Student</option>
    <option value="librarian">Librarian</option>
</select>

<button type="submit" name="login">Login</button>

</form>

<?php
if($error != ""){
    echo "<div class='error'>$error</div>";
}
?>

</div>

</body>
</html>