<?php
    session_start(); 

    require (__DIR__ . "/../../Backend/dbQuery.php");

    $id=$_GET['id'] ?? -1;

    $movieDetails=getOneMovie($id);

    $movieID=$id;

    $getAvgReview=getMovieRating($id);
    
    foreach($movieDetails as $r){
        $userID=$r['UserAccountID'];//getting the userAccount id from the accounts table
    }


    $userData = getUser($userID);

    foreach($userData as $user){
        $username = $user['Username'];
    }

    $reviews = getReviews($id);
    //$userdetails=getUser($userID);

    if(isPostRequest() && filter_input(INPUT_POST, 'txtReview') != "" && filter_input(INPUT_POST,'txtRates') != ""){
        $userAccountID=$userID;//adding user id to the review tables 
        $ReviewDescription= filter_input(INPUT_POST, 'txtReview');;
        $Rating= filter_input(INPUT_POST, 'rating');        
        addReview($userAccountID,$movieID,$ReviewDescription,$Rating);

        header("Location: moviePage.php?id=" . $id);
    };      //adds value to function when the page posts
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
    <link rel="stylesheet" href="../CSS/review.css">

    <script src="Script/jquery-1.3.2.min.js" type="text/javascript"></script>

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
</head>

<body>
    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
    </script>
    <script>
        function showForm(){
            document.getElementById(reviewForm).style.display = "none";
            return false;
        }
    </script>

    <div class="wrapper">
        <!-- Sidebar -->
        <?php include(__DIR__ . "/../Blueprints/navDynamicBlueprint.php")?>
        <?php include(__DIR__ . "/../Blueprints/headerBlueprint.php")?>
        <?php 
        
        ?>

        <div id="bodyContainer">

            <!-- Static Sidebar -->
            <?php include(__DIR__ . "/../Blueprints/navStaticBlueprint.php")?>

            <!-- Page Content -->
            <div id="content" method="POST">

                <?php foreach($movieDetails as $row) :?>
                    <div class="row">
                        <div class="col-xl-4">
                            <img src='../../uploads/<?php echo $row['CoverIMG'];?>' id="trendImg" width="240px" height="350px"; >
                        </div>
                        
                        <div class="col-xl-8">  
                            <div class="row" id="itemContainer">  
                                <div class="col">  
                                        Title: <?php echo $row['MovieTitle'];?>
                                </div>

                                <div class="col">
                                    <a href="<?php echo "/Frontend/UserProfile/profilePage.php?username=" . $username; ?>"><div>Creator: <?php echo $username;?></div></a>
                                </div>
                <?php endforeach ?>
                
                                <div class="col">  
                                        Rating: <?php echo  $getAvgReview;?>
                                </div>
                            </div>
                
                <?php foreach($movieDetails as $row) :?>
                            <div class="row" id="itemContainer">
                                    <h2 style="width:100%">Description</h2>

                                    <p style="width:100%"><?php echo $row['MovieDescription']; ?></p>
                            </div>
                        </div>               
                            <!--- it's be width x height in html not length but for now to avoid stretching images let them size themselvs --->
                    </div>            
                <?php endforeach ?>

                <div id="itemContainer">
                    <!---<div class="col-6">
                            <input id="btnReview" class="btn btn-primary" type="submit" value="Write A Review" name="btnReview" onClick="return showForm()">
                    </div>--->

                    <form id="reviewForm"  action="moviePage.php?id=<?php echo $id;?>" method="post" class="row">

                        <div class="col-12">
                                <textarea id="txtReview" type="text" rows="6" cols="60" style="width:100%;" name="txtReview"></textarea>
                        </div>

                        <fieldset id="txtRates" name="txtRates" class="rating col-auto">
                            <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="Rocks!">5 stars</label>
                            <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Pretty good">4 stars</label>
                            <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Meh">3 stars</label>
                            <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Kinda bad">2 stars</label>
                            <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Sucks big time">1 star</label>
                        </fieldset>


                        
                        <div id="reviewSubmitContainer" class="col-auto d-flex justify-content-end" >
                                <input id="btnReview" class="btn btn-primary" type="submit" value="Submit" name="btnReview">
                        </div>

                    


                    </form>
                    
                    
                    <?php foreach($reviews as $rev): ?>
                        <div class="row no-margin no-pad">
                            <div id="detail" class="col pl-4 pr- pt-3">
                                <p><?php echo $rev['ReviewDescription'];?></p>
                            </div>

                            <div id="detail" class="col pl-4 pr- pt-3">
                                <p><?php echo $rev['Rating'];?>/5</p>
                            </div>
                        </div>
                    <?php endforeach; ?> 
                </div>
                
            </div>
        </div>
    </div>  
</body>
</html>