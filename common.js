// common.js
function showDropdown() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "check_session.php", true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText === "logged_in") {
                // User is logged in, show the dropdown
                document.getElementById("dropdown").classList.toggle("show");
            } else {
                // User is not logged in, navigate to login.html
                window.location.href = "login.html";
            }
        }
    };
    xhr.send();
}

window.onclick = function(event) {
    if (event.target.id !== 'profile-icon') {
        var dropdowns = document.getElementById("dropdown");
        if (dropdowns.classList.contains('show')) {
            dropdowns.classList.remove('show');
        }
    }
}

function showCart() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "check_session.php", true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText === "logged_in") {
                // User is logged in, navigate to shopping-cart.php
                window.location.href = "shopping-cart.php";
            } else {
                // User is not logged in, navigate to login.html
                alert('Please log in to access the shopping cart.')
                window.location.href = "login.html";
            }
        }
    };
    xhr.send();
}

function signOut() {
    const userConfirmed = confirm("Are you sure you want to sign out?");
    if (userConfirmed) {
        // Add an AJAX request to inform the server to destroy the session
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "logout.php", true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (xhr.responseText.trim() === "success") {
                    // Clear email from sessionStorage
                    sessionStorage.removeItem('email');

                } else {
                    // alert("Failed to sign out.");
                }
                alert("You are successfully signed out.");
                window.location.href = "login.html";
            }
        };
        xhr.send();
    } else {
        alert("Sign-out canceled. You are still signed in.");
    }
}


