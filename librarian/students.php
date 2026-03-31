<?php
session_start();
include("../db.php");

// Only librarian allowed
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'librarian'){
    header("Location: login.php");
    exit();
}

// Get all students
$result = $conn->query("SELECT id, username, email FROM users WHERE role='student'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Students List</title>

<style>
body {
    font-family: Arial;
    background:#f3e5f5;
    padding:30px;
}

h2 {
    color:#6a1b9a;
}

table {
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0px 5px 15px rgba(0,0,0,0.2);
}

th, td {
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:left;
}

th {
    background:#6a1b9a;
    color:white;
}

tr:hover {
    background:#f1f1f1;
}

.back {
    display:inline-block;
    margin-bottom:15px;
    padding:8px 15px;
    background:#6a1b9a;
    color:white;
    text-decoration:none;
    border-radius:5px;
}
</style>

</head>
<body>

<a class="back" href="librarian_dashboard.php">← Back</a>

<h2>📚 Students List</h2>

<table>
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Email</th>
</tr>

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        echo "<tr>
                <td>".$row['id']."</td>
                <td>".$row['username']."</td>
                <td>".$row['email']."</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='3'>No students found</td></tr>";
}
?>

</table>

</body>
</html>