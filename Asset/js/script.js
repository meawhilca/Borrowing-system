// Simple login animation
document.addEventListener("DOMContentLoaded", function(){

    const box = document.querySelector(".login-box");

    box.style.opacity = 0;
    box.style.transform = "translateY(-30px)";

    setTimeout(function(){
        box.style.transition = "all 0.8s";
        box.style.opacity = 1;
        box.style.transform = "translateY(0)";
    },200);

});

// Confirmation for borrowing book
function confirmBorrow(){
    return confirm("Are you sure you want to borrow this book?");
}

