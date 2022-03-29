<?php
    session_start();
    require (__DIR__ . "/../../Backend/dbQuery.php");

    $searchResults = unfollowUser($_SESSION["profileID"], $_SESSION["user"]);

    if($searchResults == 'Success')
    {
        echo '<a id="followBtn" class="btn btn-primary">Follow User</a>';
    }
?>