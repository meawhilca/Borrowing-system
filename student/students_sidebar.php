<?php

?>

<div class="sidebar">
    <h2>📚 Library</h2>

    <p class="user">👤 <?php echo $_SESSION['username']; ?></p>

    <a href="students_dashboard.php">🏠 Dashboard</a>
    <a href="borrow_books.php">📖 Borrow Books</a>
    <a href="return_book.php">📚 Return Books</a>
    <a href="borrowed_books.php">📖 Borrowed Books</a>
    <a href="profile.php">👤 Profile</a>
    <a href="../logout.php" class="logout">🚪 Logout</a>
</div>

<style>
/* SIDEBAR */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 200px;
    height: 100%;
    background: linear-gradient(180deg, #6a1b9a, #4a148c);
    color: white;
    padding: 20px;
    box-shadow: 5px 0 15px rgba(0,0,0,0.3);

    /* animation */
    transform: translateX(-100%);
    animation: slideIn 0.5s ease forwards;
}

/* TITLE */
.sidebar h2 {
    text-align: center;
    margin-bottom: 20px;
}

/* USER */
.user {
    text-align: center;
    background: rgba(255,255,255,0.1);
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 20px;
}

/* LINKS */
.sidebar a {
    display: block;
    padding: 12px;
    margin: 6px 0;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: 0.3s;

    opacity: 0;
    animation: fadeIn 0.5s ease forwards;
}

/* STAGGER EFFECT */
.sidebar a:nth-child(3){animation-delay:0.2s;}
.sidebar a:nth-child(4){animation-delay:0.3s;}
.sidebar a:nth-child(5){animation-delay:0.4s;}
.sidebar a:nth-child(6){animation-delay:0.5s;}
.sidebar a:nth-child(7){animation-delay:0.6s;}

/* HOVER */
.sidebar a:hover {
    background: rgba(255,255,255,0.2);
    transform: translateX(5px);
}

/* LOGOUT */
.logout {
    background: #ff5252;
}

.logout:hover {
    background: #ff1744;
}

/* ANIMATIONS */
@keyframes slideIn {
    to {
        transform: translateX(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>