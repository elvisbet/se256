<?php
//logout user 
    session_start();

    unset($_SESSION['user']);
    unset($_SESSION['FName']);
    unset($_SESSION['LName']);
    unset($_SESSION['profileName']);
    unset($_SESSION['profileID']);

    $_SESSION["loggedIn"] = false;

    session_destroy();

    header("Location: ../MoviePage/homePage.php");
?>
