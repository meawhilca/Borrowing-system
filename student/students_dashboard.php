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
            background: linear-gradient(135deg, #ede7f6, #f5f6fa);
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 200px;
            padding: 50px;
            width: 100%;
            min-height: 100vh;
        }

        /* TOP BAR */
        .top-bar {
            margin-bottom: 25px;
            font-size: 16px;
            color: #6a1b9a;
            background: white;
            padding: 12px 18px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        /* CARD */
        .card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
            max-width: 950px;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-content {
            max-width: 55%;
        }

        h2 {
            color: #6a1b9a;
            font-size: 36px;
            margin-bottom: 10px;
        }

        p {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
        }

        /* BUTTONS */
        .button {
            display: inline-block;
            margin-top: 15px;
            margin-right: 10px;
            padding: 12px 22px;
            background: linear-gradient(135deg, #6a1b9a, #8e24aa);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: 0.3s;
            font-weight: 500;
        }

        .button:hover {
            background: linear-gradient(135deg, #4a148c, #6a1b9a);
            transform: translateY(-3px);
        }

        /* IMAGE */
        .card img {
            width: 320px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .card {
                flex-direction: column;
                text-align: center;
            }

            .card-content {
                max-width: 100%;
            }

            .card img {
                margin-top: 20px;
                width: 250px;
            }
        }

    </style>
</head>

<body>

<!-- SIDEBAR -->
<?php include("students_sidebar.php"); ?>

<!-- MAIN -->
<div class="main-content">

    <div class="top-bar">
        👤 Logged in as: <strong><?php echo $_SESSION['username']; ?></strong> |
        🎓 Role: <strong><?php echo $_SESSION['role']; ?></strong>
    </div>

    <div class="card">

        <div class="card-content">
            <h2>📚 BOOK LIBRARY</h2>

            <p>
                Welcome, <strong><?php echo $_SESSION['username']; ?></strong>!  
                Manage your borrowed books, explore available books, and track your library activity easily.
            </p>

            <a class="button" href="borrowed_books.php">📚 My Borrowed Books</a>
            <a class="button" href="borrow_books.php">📖 Borrow Books</a>
        </div>

        <img src="https://cdn-icons-png.flaticon.com/512/29/29302.png" alt="Library">

    </div>

</div>

</body>
</html>