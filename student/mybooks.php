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

// 📚 Fetch books
$query = "SELECT * FROM books";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Books</title>

<style>

body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(rgba(74,20,140,0.7), rgba(106,27,154,0.7)),
                url('https://images.unsplash.com/photo-1512820790803-83ca734da794')
                no-repeat center/cover;
    min-height: 100vh;
}

/* Container */
.container {
    padding: 40px;
}

/* Title */
h1 {
    color: white;
    text-align: center;
    margin-bottom: 30px;
}

/* Grid */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

/* Card */
.card {
    background: rgba(255,255,255,0.9);
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}

/* Book title */
.card h3 {
    margin: 0 0 10px;
    color: #4a148c;
}

/* Info */
.card p {
    margin: 5px 0;
    font-size: 14px;
}

/* Back button */
.back-btn {
    display: inline-block;
    margin: 20px;
    text-decoration: none;
    background: white;
    color: #4a148c;
    padding: 10px 15px;
    border-radius: 8px;
}

.back-btn:hover {
    background: #ddd;
}

</style>
</head>

<body>

<a href="students_dashboard.php" class="back-btn">⬅ Back</a>

<div class="container">

<h1>Available Books</h1>

<div class="grid">

<?php
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        echo "
        <div class='card'>
            <h3>{$row['title']}</h3>
            <p><strong>Author:</strong> {$row['author']}</p>
            <p><strong>Quantity:</strong> {$row['quantity']}</p>
        </div>
        ";
    }
} else {
    echo "<p style='color:white;'>No books available.</p>";
}
?>

</div>

</div>

</body>
</html>