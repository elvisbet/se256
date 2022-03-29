<?php
    //creating a session
    session_start(); 

    if($_SESSION["loggedIn"] == false) {

        header('Location: /Frontend/Login-Signup/loginPage.php');    //simple and easy way for my session vars hope this is alright
    
    
    }
        
    require (__DIR__ . "/../../Backend/dbQuery.php");

    $action = $_GET['action'] ?? '';
    $btnString = "test";

    if ($action == "add") {
        $btnString = "Create";
    } 
    
    else if ($action == "edit"){
        $btnString = "Update";
    }

    else{
        $btnString = "Add Movie";
    }

    $movieTitle = filter_input(INPUT_POST, 'movieTitle');       //creating my inital vars

    // $movieIMG = filter_input(INPUT_POST, 'movieIMG');
    // $movieBanner = filter_input(INPUT_POST, 'movieBanner');

    $movieDescripton = filter_input(INPUT_POST, 'movieDescripton');
    $movieGenre = filter_input(INPUT_POST, 'movieGenre');

    $id = filter_input(INPUT_GET, 'movieID', FILTER_VALIDATE_FLOAT);        


    $row = grabMovies($id);

    $id = filter_input(INPUT_GET, 'movieID', FILTER_VALIDATE_FLOAT);


    $movieTitle = $row['MovieTitle'];

    $movieDescripton = $row['MovieDescription'];

    $movieGenre = $row['MovieGenre'];
 
   
    //grabbing the ID so I can delete moviesas wlel




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

        <div id="bodyContainer">
            <!-- Static Sidebar -->
            <?php include(__DIR__ . "/../Blueprints/navStaticBlueprint.php")?>

            <!-- Page Content -->
            <div id="content">        
                <div id="AddEditMovie" class="row center no-margin no-padL">
                    <div id="spacer" class="col-3"></div>
                    

                    <div id="signupContainer" class="col-6">

                        
                        <form action='movieEDIT.php' method='post' enctype="multipart/form-data">  
                        <input type="hidden" name="movieID" value="<?= $id;?>">
                            <div class="form-group">
                                <label  for="exampleFormControlInput1">Movie Title</label>
                                <input name="movieTitle" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Title" value="<?=$movieTitle; ?>">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Movie Description</label>
                                <textarea name='movieDescripton' class="form-control" id="exampleFormControlTextarea1" rows="3"><?php echo $movieDescripton; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Genre Select</label>
                                <select name='movieGenre' class="form-control" id="exampleFormControlSelect1" value="<?=$movieGenre; ?>">
                                    <option>Action</option>
                                    <option>Adventure</option>
                                    <option>Horror</option>
                                    <option>Comedy</option>
                                    <option>Family</option>
                                    <option>Thriller</option>
                                    <option>Drama</option>
                                    <option>Science Fiction</option>
                                    <option>Romance</option>
                                    <option>Western</option>
                                    <option>Crime</option>
                                    <option>Musical</option>
                                    <option>Fantasy</option>
                                </select>
                            </div>

                            <div class="row">
                                <button name='deleteBtn' type="submit" class="btn btn-primary">Delete Movie</buton>

                        
                            
                                <button name='submitBtn' type="submit" class="btn btn-primary">Edit Movie</buton>
                            </div>


                        </form>

                        <h2><br>When you edit your movie, your like count will be reset along with needing to be approved once more!<br><br>For any drastic changes needed please contact support @ JJLHelp@gmail.com
                    </div>

                    <div id="spacer" class="col-3"></div>


                    <?php

                        $id = filter_input(INPUT_GET, 'movieID', FILTER_VALIDATE_FLOAT); 


                        $row = getOneMovie($id);

                        if(isset($_POST['deleteBtn'])) {            //this is the function that will allow users to delete movies

                            $id = filter_input(INPUT_POST, 'movieID', FILTER_VALIDATE_INT);

                            deleteMovie($id);

                        

                            header('Location: CHANGE PLEASE.php');      //creating my own button for delete once pressed the delete function runs
                        }

                                                
                        


                        if(isset($_POST['submitBtn'])){     //this is for submiting 




                           

        


                        
                            // $status = $statusMsg = ''; 

                            // if(isset($_POST["submit"])){ 


                            //     $status = 'error'; 
                                
                            //     if(!empty($_FILES["movieIMG"]["name"])) { 

                                    
                            //         $fileName = basename($_FILES["movieIMG"]["name"]); 

                            //         $fileType = pathinfo($fileName, PATHINFO_EXTENSION
                            //     ); 
                                    
                            //         // Allow certain file formats 
                            //         $allowTypes = array('jpg','png','jpeg','gif'); 
                            //         if(in_array($fileType, $allowTypes)){ 
                            //             $image = $_FILES['movieIMG']['tmp_name']; 
                            //             $imgContent = addslashes(file_get_contents($image)); 
                                    
                            //             // Insert image content into database 
                            //             //  $insert = $db->query("INSERT into images (image, created) VALUES ('$imgContent', NOW())"); 
                                        
                            //             if($insert){ 
                            //                 $status = 'success'; 
                            //                 $statusMsg = "File uploaded successfully."; 
                            //             }else{ 
                            //                 $statusMsg = "File upload failed, please try again."; 
                            //             }  
                            //         }else{ 
                            //             $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.'; 
                            //         } 
                            //     }else{ 
                            //         $statusMsg = 'Please select an image file to upload.'; 
                            //     } 
                            // } 
                            
                            // // Display status message 
                            // echo $statusMsg; 






                            $error = 0;


                            $error2 = 0;


                            $movieTitle = filter_input(INPUT_POST, 'movieTitle');



                            $statusMsg = '';

                            // File upload path
                           

                            


                    
                            
            
                



                            if(strlen($movieTitle) <= 5)
                            {

                                echo"<br>Please make the title at least 5 characters!";
                                $error = 1;
                            }
                            else{

                                $error = 0;
                            }


                            if(strlen($movieDescripton) <= 15)
                            {

                                echo"<br>Please make the Description at least 15 characters!";
                                $error2 = 1;
                            }
                            else{

                                $error2 = 0;
                            }

                            if($error == 0 && $error2 == 0)
                            {

                                
                                
                        

                                $likeCount = 0;

                                $DatePosted = date('Y-m-d H:i:s');      //making the date the current date

                                $useraccountId = 1;
                                
                                $isApproved = 0;

                                $creatorName = 'Lance';
                                
                                $results = updateMovie($movieTitle, $DatePosted, $movieGenre, $movieDescripton, $creatorName, $likeCount, $isApproved, $useraccountId);         //adds the movie
                                
                                
                                

                                if(isPostRequest()){
                                    
                                    echo "<br>Movie added";
                                    

                                    header('Location: profilePage.php');
                                    
                                }



                            }
                            else{

                                echo '<br>please fix errors';
                                
                            }

                        }



                        if(isset($_POST['editBtn'])) { 

                        }


                    ?>



                        
                </div>

                
                <!-- <div id="DeleteMovie" class="row center no-margin no-padL">
                    <div id="spacer" class="col-3"></div>

                    <div id="signupContainer" class="col-6">
                        <form action='MoviePageCRUD
                        .php' method="post">
                            <div>movie title</div>

                            <div>movie img</div>

                            <div class="col-sm-6">
                                <button name='deletebtn'type="submit" class="btn btn-primary">Delete Movie</button>
                            </div>
                        </form>
                    </div>

                    <div id="spacer" class="col-3"></div>

                    // OPENING PHP

                        //if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Something posted 
                            //echo "This is a test" ; 
                        
                            //if (isset($_POST['btnDelete'])) {
                                //echo "Your movie has been deleted " ;
                            } //else {
                                // Assume btnSubmit
                            }
                        }
                    // CLOSING PHP
                </div> -->
            </div>
        </div>
    </div>  
    
</body>
</html>