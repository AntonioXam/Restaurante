<?php
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario'])) {
    header("Location: index.php");
}
