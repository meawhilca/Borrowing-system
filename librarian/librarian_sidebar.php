<div class="sidebar">

    <div class="logo">
        <h2>📚 Librarian</h2>
        <p>Management Panel</p>
    </div>

    <div class="user">
        <span>👤</span>
        <p><?php echo $_SESSION['username']; ?></p>
    </div>

    <nav>
        <a href="librarian_dashboard.php" class="active">🏠 Dashboard</a>
        <a href="manage_books.php">📘 Manage Books</a>
         <a href="manage_request.php">📘 Manage Request</a>
        <a href="add_book.php">➕ Add Book</a>
        <a href="borrow_records.php">📖 Borrow Records</a>
        <a href="student_name.php">🎓 Students</a> 
        <a href="reports.php">📊 Reports</a>
        <a href="../logout.php" class="logout">🚪 Logout</a>
    </nav>

</div>

<style>
/* SIDEBAR */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 230px;
    height: 100%;
    background: linear-gradient(180deg, #4a148c, #6a1b9a);
    color: white;
    padding: 20px;
    box-shadow: 5px 0 15px rgba(0,0,0,0.3);

    /* ANIMATION */
    transform: translateX(-100%);
    animation: slideIn 0.5s ease forwards;
}

/* SLIDE IN */
@keyframes slideIn {
    to {
        transform: translateX(0);
    }
}

/* LOGO */
.logo h2 {
    margin: 0;
    font-size: 22px;
}

.logo p {
    font-size: 13px;
    opacity: 0.8;
    margin-bottom: 20px;
}

/* USER */
.user {
    background: rgba(255,255,255,0.1);
    padding: 10px;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 20px;
}

/* NAV */
nav a {
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

/* STAGGER ANIMATION */
nav a:nth-child(1){animation-delay:0.2s;}
nav a:nth-child(2){animation-delay:0.3s;}
nav a:nth-child(3){animation-delay:0.4s;}
nav a:nth-child(4){animation-delay:0.5s;}
nav a:nth-child(5){animation-delay:0.6s;}
nav a:nth-child(6){animation-delay:0.7s;}
nav a:nth-child(7){animation-delay:0.8s;}

/* HOVER */
nav a:hover {
    background: rgba(255,255,255,0.2);
    transform: translateX(5px);
}

/* ACTIVE */
nav a.active {
    background: white;
    color: #4a148c;
    font-weight: bold;
}

/* LOGOUT */
.logout {
    background: #ff5252;
}

.logout:hover {
    background: #ff1744;
}

/* FADE */
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