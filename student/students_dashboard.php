<?php
session_start();
include("../db.php");

// 🔐 Check login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 🔒 Check role
if (!isset($_SESSION['role'])) {
    echo "<h3>Session error. Please login again.</h3>";
    echo "<a href='students_dashboard.php'>Go Back</a>";
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
            margin: 0;
            font-family: Arial;
            display: flex;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 200px;
            padding: 100px;
            width: 100%;
            background: #f4f4f4;
            min-height: 100vh;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            max-width: 500px;
        }

        h2 {
            color: #6a1b9a;
        }

        .top-bar {
            margin-bottom: 20px;
            font-size: 25px;
            shadow: 25px
            color: #a047b6;
        }

        .button {
            display: inline-block;
            margin-top: 10px;
            margin-right: 10px;
            padding: 10px 20px;
            background: #6a1b9a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .button:hover {
            background: #4a148c;
        }
    </style>
</head>
<body>

<!-- ✅ INCLUDE SIDEBAR -->
<?php include("students_sidebar.php"); ?>

<!-- ✅ MAIN CONTENT -->
<div class="main-content">

    <div class="top-bar">
        Logged in as: <strong><?php echo $_SESSION['username']; ?></strong> |
        Role: <strong><?php echo $_SESSION['role']; ?></strong>
    </div>

    <div class="card">
        <h2>🎓 Student Dashboard</h2>

        <p>Welcome, <strong><?php echo $_SESSION['username']; ?></strong>!</p>

        <a class="button" href="borrowed_books.php">📚 Borrowed Books</a>
        <a class="button" href="borrow_books.php">📖 Borrow Books</a>
    </div>

</div>

</body>
</html>