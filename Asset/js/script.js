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

// 📌 Fetch user data (secure)
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// ✅ UPDATE LOGIC
if (isset($_POST['update'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    $update = $conn->prepare("UPDATE users SET fullname=?, email=? WHERE username=?");
    $update->bind_param("sss", $fullname, $email, $username);

    if ($update->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location='profile.php';</script>";
    } else {
        echo "<script>alert('Update failed');</script>";
    }
}
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

/* Edit button */
.edit-btn {
    margin-top: 10px;
    padding: 8px 12px;
    border: none;
    background: #6a1b9a;
    color: white;
    border-radius: 6px;
    cursor: pointer;
}

.edit-btn:hover {
    background: #4a148c;
}

</style>
</head>

<body>

<div class="profile-card">

<h2><?php echo $user['username']; ?></h2>

<div class="info">
    <p><strong>Full Name:</strong> <?php echo $user['fullname'] ?? 'N/A'; ?></p>
    <p><strong>Email:</strong> <?php echo $user['email'] ?? 'N/A'; ?></p>
    <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
</div>

<!-- ✅ Edit Button -->
<button class="edit-btn" onclick="toggleEdit()">Edit Profile</button>

<!-- ✅ Hidden Edit Form -->
<div id="editForm" style="display:none; margin-top:15px; text-align:left;">

<form method="POST">
    <label>Full Name</label>
    <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>" required style="width:100%; padding:6px; margin:5px 0;">

    <label>Email</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required style="width:100%; padding:6px; margin:5px 0;">

    <button type="submit" name="update" style="margin-top:10px;">Save Changes</button>
</form>

</div>

<a href="students_dashboard.php" class="back-btn">Back to Dashboard</a>

</div>

<!-- ✅ Toggle Script -->
<script>
function toggleEdit() {
    var form = document.getElementById("editForm");
    form.style.display = (form.style.display === "none") ? "block" : "none";
}
</script>

</body>
</html>