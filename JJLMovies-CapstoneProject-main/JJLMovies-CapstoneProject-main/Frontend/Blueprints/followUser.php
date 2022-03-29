<?php
    session_start();
    require (__DIR__ . "/../../Backend/dbQuery.php");

    $searchResults = followUser($_SESSION["profileID"], $_SESSION["Username"], $_SESSION["user"], $_SESSION["profileName"]);

    if($searchResults == 'Success')
    {
        echo '<a id="unfollowBtn" class="btn btn-primary">Unfollow User</a>';
    }
?>