<?php
session_start();

// Protect page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Only students
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    echo "Access denied.";
    exit();
}
?>

<style>
.sidebar {
    width: 220px;
    height: 100vh;
    background: #6a1b9a;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 20px;
    color: white;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
}

.sidebar a {
    display: block;
    color: white;
    padding: 12px 20px;
    text-decoration: none;
    transition: 0.3s;
}

.sidebar a:hover {
    background: #4a148c;
}

.main {
    margin-left: 220px;
    padding: 20px;
}
</style>

<div class="sidebar">
    <h2>📚 Library</h2>

    <p style="text-align:center;">👤 <?php echo $_SESSION['username']; ?></p>

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="books.php">📚 View Books</a>
    <a href="mybooks.php">📖 My Borrowed Books</a>
    <a href="profile.php">👤 Profile</a>
    <a href="logout.php">🚪 Logout</a>
</div>