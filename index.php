<?php

require_once "db/connection.php";
session_start();

if (!isset($_SESSION['username'])) {
    require_once "public/html/login.html";
    require_once "public/html/signup.html";
} else {
    header("Location: home.php");
}
