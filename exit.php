<?
session_start();
unset($_SESSION["id"]);
unset($_SESSION["name"]);
unset($_SESSION["email"]);
unset($_SESSION["phone"]);
unset($_SESSION["prof"]);
unset($_SESSION["avatar"]);
header("Location: /");
?>
