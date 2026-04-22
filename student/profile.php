<?php
session_start();
include("../db.php");

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role'] != 'student') {
    echo "<h3>Access denied. Students only.</h3>";
    exit();
}

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// UPDATE
if (isset($_POST['update'])) {
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    $update = $conn->prepare("UPDATE users SET email=?, gender=? WHERE username=?");
    $update->bind_param("sss", $email, $gender, $username);

    if ($update->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location='profile.php';</script>";
    } else {
        echo "<script>alert('Update failed');</script>";
    }
}

// Avatar
$gender = $user['gender'] ?? 'other';

if ($gender == 'male') {
    $avatar = "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
} elseif ($gender == 'female') {
    $avatar = "https://cdn-icons-png.flaticon.com/512/3135/3135789.png";
} else {
    $avatar = "https://cdn-icons-png.flaticon.com/512/149/149071.png";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Profile</title>

<style>

body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #894d88, #51455e);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* CARD */
.profile-card {
    width: 420px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.2);
    overflow: hidden;
}

/* HEADER */
.header {
    background: linear-gradient(135deg, #6a1b9a, #8e24aa);
    padding: 25px;
    text-align: center;
    color: white;
}

/* AVATAR */
.avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 3px solid white;
    margin-bottom: 10px;
}

/* BODY */
.body {
    padding: 25px;
}

/* INFO ROWS */
.info {
    text-align: left;
}

.row {
    padding: 10px 0;
    border-bottom: 1px solid #b2acc5;
    font-size: 14px;
}

.row strong {
    color: #6a1b9a;
}

/* BUTTON */
.edit-btn {
    margin-top: 15px;
    width: 100%;
    padding: 10px;
    border: none;
    background: #6a1b9a;
    color: white;
    border-radius: 8px;
    cursor: pointer;
}

.edit-btn:hover {
    background: #4a148c;
}

/* FORM */
#editForm {
    display: none;
    margin-top: 15px;
}

input, select {
    width: 100%;
    padding: 8px;
    margin: 6px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.save-btn {
    width: 100%;
    padding: 10px;
    background: #4caf50;
    border: none;
    color: white;
    border-radius: 8px;
    margin-top: 10px;
}

.back-btn {
    display: block;
    text-align: center;
    margin-top: 10px;
    text-decoration: none;
    color: #666;
}

</style>
</head>

<body>

<div class="profile-card">

<!-- HEADER -->
<div class="header">
    <img src="<?php echo $avatar; ?>" class="avatar">
    <h3><?php echo $user['username']; ?></h3>
</div>

<!-- BODY -->
<div class="body">

<div class="info">
    <div class="row"><strong>Email:</strong> <?php echo $user['email'] ?? 'N/A'; ?></div>
    <div class="row"><strong>Gender:</strong> <?php echo $user['gender'] ?? 'N/A'; ?></div>
    <div class="row"><strong>Role:</strong> <?php echo $user['role'] ?? 'N/A'; ?></div>
</div>

<button class="edit-btn" onclick="toggleEdit()">Edit Profile</button>

<!-- EDIT FORM -->
<div id="editForm">

<form method="POST">

    <label>Email</label>
    <input type="email" name="email"
           value="<?php echo $user['email'] ?? ''; ?>" required>

    <label>Gender</label>
    <select name="gender">
        <option value="male" <?php if(($user['gender'] ?? '')=='male') echo 'selected'; ?>>Male</option>
        <option value="female" <?php if(($user['gender'] ?? '')=='female') echo 'selected'; ?>>Female</option>
        <option value="other" <?php if(($user['gender'] ?? '')=='other') echo 'selected'; ?>>Other</option>
    </select>

    <button class="save-btn" type="submit" name="update">Save Changes</button>

</form>

</div>

<a href="students_dashboard.php" class="back-btn">Back to Dashboard</a>

</div>

</div>

<script>
function toggleEdit() {
    var form = document.getElementById("editForm");
    form.style.display = (form.style.display === "none" || form.style.display === "") 
        ? "block" 
        : "none";
}
</script>

</body>
</html>