<?php
    session_start();

    require (__DIR__ . "/../../Backend/dbQuery.php");

    $Username = $_GET['username'] ?? '';

    $userID = checkUserName($Username);

    $profileType = 'Personal';

    $rowCount = 0;

    if($_SESSION["loggedIn"] == false) {
        header('Location: /Frontend/Login-Signup/loginPage.php');    //simple and easy way for my session vars hope this is alright
    }

    if ($userID != NULL && $userID != $_SESSION['user'])
    {
        $userData = getUser($userID);
        $userFollowers = getFollowerCount($userID);
        $userFollowing = getFollowingCount($userID);

        $profileType = 'Other';
    }
    else
    {
        $userID = $_SESSION['user'];
        $profileType = 'Personal';
    }
    
    $userData = getUser($userID);
    $userFollowers = getFollowerCount($userID);
    $userFollowing = getFollowingCount($userID);

    foreach($userData as $user){
        //getting the user information from the table and storing into session variables to display on pages
        $Username = $user['Username'];
        $fName = $user['FirstName'];
        $lName = $user['LastName'];
        $profileImg = $user['ProfileImg']; 
    }

    //This saves the name for the purposes of following a user
    $_SESSION["profileName"] = $Username;
    //This saves the id for the purposes of following a user
    $_SESSION["profileID"] = $userID;

    $movies = getUserMovie($userID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JJL Movie Reviews</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../CSS/style.css">
    
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <style>

    </style>
</head>

<body>
    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <div class="wrapper">
        <!-- Sidebar -->
        <?php include(__DIR__ . "/../Blueprints/navDynamicBlueprint.php")?>
        <?php include(__DIR__ . "/../Blueprints/headerBlueprint.php")?>

        <div id="shareDisplay" class="row" style="display: none;">
            <div id="spacerCol" class="col-3"></div>

            <div class="col-auto" style="background-color: rgba(24, 26, 43, 0.99); margin-top: 30vh; margin-bottom: 30vh;">
                <div class="col-12 d-flex justify-content-center" style="margin-top: 5%;">Share this link:</div>
                <div id="shareDisplayText" class="col-12 d-flex justify-content-center" style="margin-top: 10%; word-wrap: break-word;">test link</div>
                
                <div class="col-12 d-flex justify-content-end" style="margin-top: 25px;">
                    <a id="closeBtn" class="btn btn-primary">Close</a>
                </div>
            </div>
        </div>

        <div id="bodyContainer">
            <!-- Static Sidebar -->
            <?php include(__DIR__ . "/../Blueprints/navStaticBlueprint.php")?>

            <!-- Page Content -->
            <div id="content">
                <div class="row no-margin">
                    <div id="itemContainer" class="col-xl-3"> 
                        <div class="row"> 
                            <div id="profileIMG" class="col-12 d-flex justify-content-center profileItem">
                                <img src="<?php if($profileImg != NULL){ echo $profileImg; } else{echo "/images/profile-icon-logged-out.png";}?>" width="170px" height="170px"; >
                            </div>

                            <div class="col-12">
                                <div id="profileUsername" class="col-12 d-flex justify-content-center">
                                    <?php echo $Username ?>
                                </div>

                                <div id="profileName" class="col-12 d-flex justify-content-center">
                                    <?php echo $fName ?>
                                    <?php echo $lName ?>
                                </div>

                                <div id="profileFollowers" class="col-12 d-flex justify-content-center">
                                    <div class="row" style="width: 100%;">
                                        <div class="col-6">
                                            <div class="row d-flex justify-content-center">Followers</div>

                                            <div class="row d-flex justify-content-center"><?php echo $userFollowers ?></div>
                                        </div>
                                    
                                        <div class="col-6">
                                            <div class="row d-flex justify-content-center">Following</div>

                                            <div class="row d-flex justify-content-center"><?php echo $userFollowing ?></div>
                                        </div>
                                    </div>
                                </div> 

                                <div id="profileAddMovie" class="col-12 d-flex justify-content-center align-items-end">
                                    <?php if($profileType == 'Personal'){ echo "<a href='/Frontend/UserProfile/MoviePageCRUD.php?action=add' class='btn btn-primary'>Create Movie</a>";} ?>
                                </div>
                                
                                <div id="profileLogout" class="col-12 d-flex justify-content-center align-items-center">
                                    <?php if($profileType == 'Personal'){ echo '<a href="/Frontend/Login-Signup/logoutPage.php" class="btn btn-primary">Log Out</a>';}
                                        else if (isFollowing($_SESSION["user"], $_SESSION["profileID"])){echo '<a id="unfollowBtn" class="btn btn-primary">Unfollow User</a>';}
                                        else {echo '<a id="followBtn" class="btn btn-primary">Follow User</a>';} 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8 profileMovieContainer" id="itemContainer">
                        <div class="row">
                            
                            <?php foreach($movies as $row) :?>
                                <?php $rowCount += 1; ?>

                                <div id="movieItem" class="col-6 col-xl-4 movieItem<?php echo $rowCount; ?>">
                                    <div class="col-12 d-flex justify-content-center">
                                        <a href="../MoviePage/moviePage.php?id=<?php echo $row['MovieID'];?>"><img src='../../uploads/<?php echo $row['CoverIMG'];?>' id="trendImg"; width=200px; height=300px;></a>
                                    </div>
                                    
                                    <div class="col-12 d-flex justify-content-center">  
                                        <a href="../MoviePage/moviePage.php?id=<?php echo $row['MovieID'];?>">
                                            <div id="movieitemContainer" class="row">  
                                                <div class="col centerV">  
                                                    <div><?php echo $row['MovieTitle'];?></div>
                                                </div>

                                                <div class="col-auto justify-content-center" id="itemContainer" style="padding:4px; margin:5px;">  
                                                    <div><?php if(getMovieRating($row['MovieID']) != ""){ echo getMovieRating($row['MovieID'])  . "/5";}else{echo "N/A";}?></div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <?php if($profileType == "Personal") : ?>

                                        <div class="col-12 d-flex justify-content-center">
                                            <div class="row" style="width: 200px;">
                                                <a class="col-auto no-pad" href="MoviePageCRUD.php?action=edit&id=<?php echo $row['MovieID'];?>" style="width: 80%; padding-right: 5px;">
                                                    <div id="movieitemContainer" class="col-auto d-flex justify-content-center editBtn" style="width: 100% !important; height: 36px; margin: 0px; margin-bottom: 25px; font-size: 16px;">  
                                                        <div class="centerV">  
                                                            <div>Edit</div>
                                                        </div>
                                                    </div>
                                                </a>

                                                <div id="movieitemContainer" class="col-auto d-flex justify-content-center shareBtn" style="width: 20%; height: 36px; margin: 0px; margin-bottom: 26px;">
                                                    <div class="col-auto d-flex justify-content-center align-items-center">  
                                                        <a id="shareBtn" name="https://jjlmovies-capstone.herokuapp.com/Frontend/MoviePage/moviePage.php?id=<?php echo $row['MovieID'];?>">
                                                            <img src='../../images/share.png' id="shareImg"; width=25px; height=25px;>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php else : ?>

                                        <div id="spacerDiv" style="margin-bottom: 75px;"></div>

                                    <?php endif; ?>
                                </div>   

                            <?php endforeach ?>

                            <a id="PrevPage" class="btn btn-primary"><</a>

                            <a id="NextPage" class="btn btn-primary">></a>
                        
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
</body>
</html>

<script>
    function submitFollow() {
        var ajaxRequest;  // The variable that makes Ajax possible!
        
        try {        
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
        
        // Create a function that will receive data
        // sent from the server and will update
        // div section in the same page.
        ajaxRequest.onreadystatechange = function() {
        
            if(ajaxRequest.readyState == 4) {
                var ajaxDisplay = document.getElementById('profileLogout');
                
                ajaxDisplay.innerHTML = ajaxRequest.responseText;

                $('#unfollowBtn').on('click', function () {
                    submitUnfollow();
                });
            }
        }

        ajaxRequest.open("GET", "/Frontend/Blueprints/followUser.php", true);
        ajaxRequest.send(null); 
    }



    function submitUnfollow() {
        var ajaxRequest;  // The variable that makes Ajax possible!
        
        try {        
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
        
        // Create a function that will receive data
        // sent from the server and will update
        // div section in the same page.
        ajaxRequest.onreadystatechange = function() {
        
            if(ajaxRequest.readyState == 4) {
                var ajaxDisplay = document.getElementById('profileLogout');
                
                ajaxDisplay.innerHTML = ajaxRequest.responseText;

                $('#followBtn').on('click', function () {
                    submitFollow();
                });
            }
        }

        ajaxRequest.open("GET", "/Frontend/Blueprints/unfollowUser.php", true);
        ajaxRequest.send(null); 
    }

    $(document).ready(function () {
        var movieCount = jQuery("[id=movieItem]").length; 
        var movieHiddenTracker = 1;


        $('#PrevPage').on('click', function () {
            if((movieHiddenTracker - 4) >= 0)
            {
                $(".movieItem" + String(movieHiddenTracker - 1)).show();
                $(".movieItem" + String(movieHiddenTracker - 2)).show();
                $(".movieItem" + String(movieHiddenTracker - 3)).show();
                $(".movieItem" + String(movieHiddenTracker - 4)).show();
                
                movieHiddenTracker -= 4;
            }
        });

        $('#NextPage').on('click', function () {

            if((movieHiddenTracker + 4) <= movieCount)
            {
                $(".movieItem" + String(movieHiddenTracker)).hide();
                $(".movieItem" + String(movieHiddenTracker + 1)).hide();
                $(".movieItem" + String(movieHiddenTracker + 2)).hide();
                $(".movieItem" + String(movieHiddenTracker + 3)).hide();
                
                movieHiddenTracker += 4;
            }
        });



        $('#followBtn').on('click', function () {
            submitFollow();
        });
        $('#unfollowBtn').on('click', function () {
            submitUnfollow();
        });


        jQuery("[id=shareBtn]").on('click', function () {
            $("#shareDisplayText").html($(this).attr('name'));
            $("#shareDisplay").show();
        });



        $('#shareDisplay').on('click', function () {
            $("#shareDisplay").hide();
        });

        $('#closeButton').on('click', function () {
            $("#shareDisplay").hide();
        });
    });
</script>