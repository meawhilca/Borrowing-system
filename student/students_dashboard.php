<?php
session_start();
include("db.php");

// 🔐 Check login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 🔒 Check if role exists first
if (!isset($_SESSION['role'])) {
    echo "<h3>Session error. Please login again.</h3>";
    echo "<a href='login.php'>Go Back</a>";
    exit();
}

// 🔒 Only STUDENTS allowed
if ($_SESSION['role'] !== 'student') {
    echo "<h3>Access denied. This page is for students only.</h3>";
    echo "<a href='login.php'>Go Back</a>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            text-align: center;
            padding: 50px;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        h2 {
            color: #6a1b9a;
        }

        a {
            display: block;
            margin: 15px 0;
            padding: 12px;
            background: #6a1b9a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background: #4a148c;
        }

        .top-bar {
            margin-bottom: 20px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="box">

    <div class="top-bar">
        Logged in as: <strong><?php echo $_SESSION['username']; ?></strong> |
        Role: <strong><?php echo $_SESSION['role']; ?></strong>
    </div>

    <h2>🎓 Student Dashboard</h2>

    <p>Welcome, <strong><?php echo $_SESSION['username']; ?></strong>!</p>

    <a href="books.php">📚 View Books</a>
    <a href="mybooks.php">📖 My Borrowed Books</a>
    <a href="logout.php">🚪 Logout</a>

</div>

</body>
</html>