<?php
session_start();
include("../db.php");

// 🔐 Check login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// 🔒 Only STUDENT allowed
if ($_SESSION['role'] != 'student') {
    echo "<h3>Access denied. Students only.</h3>";
    exit();
}

$username = $_SESSION['username'];
$message = "";

// 📌 Upload profile picture
if(isset($_POST['upload'])){

    $file = $_FILES['profile_pic']['name'];
    $temp = $_FILES['profile_pic']['tmp_name'];
    $folder = "uploads/" . $file;

    if(move_uploaded_file($temp, $folder)){
        $update = "UPDATE users SET profile_pic='$file' WHERE username='$username'";
        mysqli_query($conn, $update);
        $message = "Profile picture updated!";
    } else {
        $message = "Failed to upload image.";
    }
}

// 📌 Fetch user data
$query = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$profile_pic = $user['profile_pic'] ? $user['profile_pic'] : "default.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Profile</title>

<style>

body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(rgba(196, 147, 206, 0.7), rgba(168, 181, 236, 0.7)),
                url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f') no-repeat center/cover;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Card */
.profile-card {
    background: rgba(255, 255, 255, 0.95);
    padding: 40px;
    width: 400px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

/* Profile Image */
.profile-card img {
    width: 150px;
    height: 150px;
    border-radius: 100%;
    object-fit: cover;
    border: 4px solid #c5b3d1;
    margin-bottom: 15px;
}

/* Title */
.profile-card h2 {
    color: #4a148c;
    margin-bottom: 10px;
}

/* Info */
.info {
    text-align: left;
    margin-top: 20px;
}

.info p {
    margin: 8px 0;
}

/* Upload */
input[type="file"] {
    margin-top: 10px;
}

button {
    margin-top: 10px;
    padding: 10px;
    width: 100%;
    background: #423181;
    border: none;
    color: white;
    border-radius: 8px;
    cursor: pointer;
}

button:hover {
    background: #7f51e9;
}

/* Back button */
.back-btn {
    display: inline-block;
    margin-top: 15px;
    text-decoration: none;
    background: #999;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
}

.message {
    color: green;
    margin-top: 10px;
}

</style>
</head>

<body>

<div class="profile-card">

<img src="uploads/<?php echo $profile_pic; ?>" alt="Profile Picture">

<h2><?php echo $user['username']; ?></h2>

<div class="info">
    <p><strong>Full Name:</strong> <?php echo $user['fullname'] ?? 'N/A'; ?></p>
    <p><strong>Email:</strong> <?php echo $user['email'] ?? 'N/A'; ?></p>
    <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
</div>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="profile_pic" required>
    <button type="submit" name="upload">Upload Picture</button>
</form>

<?php if($message != "") echo "<div class='message'>$message</div>"; ?>

<a href="students_dashboard.php" class="back-btn">Back to Dashboard</a>


</div>

</body>
</html>