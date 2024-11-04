<?php
session_start();

if (!isset($_SESSION['usuario']) && !isset($_SESSION['dni'])) {
    header("Location: index.php");
   
}
