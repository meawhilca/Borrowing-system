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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            background: #f5f6fa;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 200px;
            padding: 60px;
            width: 100%;
            min-height: 100vh;
        }

        .card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            max-width: 900px;
        }

        .card-content {
            max-width: 50%;
        }

        .card img {
            width: 300px;
        }

        h2 {
            color: #6a1b9a;
            font-size: 32px;
            margin-bottom: 10px;
        }

        p {
            color: #555;
            font-size: 16px;
        }

        .top-bar {
            margin-bottom: 30px;
            font-size: 18px;
            color: #6a1b9a;
        }

        .button {
            display: inline-block;
            margin-top: 15px;
            margin-right: 10px;
            padding: 12px 20px;
            background: #6a1b9a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .button:hover {
            background: #4a148c;
            transform: translateY(-2px);
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
        <div class="card-content">
            <h2>📚 BOOK LIBRARY</h2>

            <p>Welcome, <strong><?php echo $_SESSION['username']; ?></strong>! Explore and manage your books easily.</p>

            <a class="button" href="borrowed_books.php">📚 Borrowed Books</a>
            <a class="button" href="borrow_books.php">📖 Borrow Books</a>
        </div>
    </div>

</div>

</body>
</html>
