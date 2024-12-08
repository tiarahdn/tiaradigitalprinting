<?php
// Fungsi untuk mengecek apakah admin sudah login
function is_logged_in()
{
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Fungsi untuk sanitasi input
function sanitize_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}
