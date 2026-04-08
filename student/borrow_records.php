<?php
include("../db.php"); // your database connection

// Assuming student ID is stored in session
$student_id = $_SESSION['id'];

// Correct SQL to get borrowed books for this student
$sql = "
    SELECT br.id, b.title AS book_title, br.borrow_date, br.return_date
    FROM borrow_records br
    JOIN books b ON br.book_id = b.id
    WHERE br.student_id = ? AND br.return_date IS NULL
    ORDER BY br.borrow_date DESC
";

// Prepare statement to avoid SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result(); // mysqli_result object

// Check if any rows exist
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Book: " . htmlspecialchars($row['book_title']) . " | Borrowed on: " . $row['borrow_date'] . "<br>";
    }
} else {
    echo "No borrowed books found.";
}

$stmt->close();
$conn->close();
?>