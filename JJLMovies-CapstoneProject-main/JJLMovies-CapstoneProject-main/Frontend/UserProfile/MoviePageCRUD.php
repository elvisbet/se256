<?php
    //creating a session
    session_start(); 

    require (__DIR__ . "/../../Backend/dbQuery.php");

    if($_SESSION["loggedIn"] == false) {
        header('Location: /Frontend/Login-Signup/loginPage.php');    //simple and easy way for my session vars hope this is alright
    }  

    $action = $_GET['action'] ?? '';
    $movieID = $_GET['id'] ?? '';

    $btnString = "Something went wrong"; //Default value just in case something potentially fails



    //If they are blank this is likely a post request so set them equal to the hidden variables to grab values if they are empty the site defaults to creating a movie
    if($action == '')
    {
        $action = filter_input(INPUT_POST, 'actionType');
    }

    if($movieID == '')
    {
        $movieID = filter_input(INPUT_POST, 'movieID');
    }



    if ($action == "add") {
        $btnString = "Create";
    } 
    
    else if ($action == "edit" && $movieID != ''){
        $btnString = "Update";

        $movieDetails = getOneMovie($movieID);

        foreach($movieDetails as $row){
            $movieTitle = $row['MovieTitle']; //creating my inital vars
            $movieDescripton = $row['MovieDescription'];
            $movieGenre = $row['MovieGenre'];
            $movieIMG = $row['CoverIMG'];
            $movieTrailer = $row['movieTrailer'];
        }
    }

    else{
        $btnString = "Create";
    }

    if(isset($_POST['editBtn']) || isset($_POST['submitBtn']))
    { 
        $movieTitle = filter_input(INPUT_POST, 'movieTitle');       //creating my inital vars
        $movieDescripton = filter_input(INPUT_POST, 'movieDescripton');
        $movieGenre = filter_input(INPUT_POST, 'movieGenre');
        $movieTrailer = filter_input(INPUT_POST, 'movieTrailer');
    }



    $error = 0;
    $error2 = 0;
    $error3 = 0;

    $statusMsg = '';
    $fixMsg = '';

    if(isset($_POST['submitBtn'])){
        // File upload path
        $targetDir = "../../uploads/";

        $fileName = basename($_FILES["file"]["name"]);

        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

        if(empty($fileName))
        {
            $fixMsg .= '<br>Please select a file to upload for your cover image!';

            $error3 = 1;  
        }
        else{
            $error3 = 0;
        }

        if(strlen($movieTitle) <= 2)
        {
            $fixMsg .= "<br>Please make the title at least 5 characters!";
            $error = 1;
        }
        else{
            $error = 0;
        }
        
        if(strlen($movieDescripton) <= 15)
        {
            $fixMsg .= "<br>Please make the Description at least 15 characters!";
            $error2 = 1;
        }
        else{
            $error2 = 0;
        }

        if($error == 0 && $error2 == 0 && $error3 == 0)
        {
            if(!empty($_FILES["file"]["name"])){
                
                // Allow certain file formats
                $allowTypes = array('jpg','png','jpeg','gif','pdf');        //all of this is for my uploading images
                
                if(in_array($fileType, $allowTypes)){
                    
                    // Upload file to server
                    if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
                        $likeCount = 0;
                        $DatePosted = date('Y-m-d H:i:s');      //making the date the current date
                        
                        $returnedAcnt = getUser($_SESSION['user']);

                        if(count($returnedAcnt)){

                            foreach($returnedAcnt as $creator){
                                //getting the user information from the table and storing into session variables to display on pages
                                $creatorName = $creator['Username'];
                            }
                        }
                
                        $isApproved = 0;
                        
                        $statusMsg = addMovie($movieTitle, $DatePosted, $movieGenre, $movieDescripton, $creatorName, $likeCount, $isApproved, $fileName, $movieTrailer, $_SESSION['user']);         //adds the movie

                        header('Location: ../UserProfile/profilePage.php');
                    }
                    else
                    {
                        $statusMsg = "Sorry, there was an error uploading your file.";
                    }
                }
                else
                {
                    $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
                }
            }
            else
            {
                $statusMsg = 'Please select a file to upload.';
            }       
        }
        else
        {
            $fixMsg .= '<br>please fix errors';
        }
    }



    if(isset($_POST['editBtn'])){ //this is for submiting 
        // File upload path
        $targetDir = "../../uploads/";

        $fileName = basename($_FILES["file"]["name"]);

        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

       

        if(empty($fileName))
        {
            $fixMsg .= '<br>Please select a file to upload for your cover image!';

            $error3 = 1;  
        }
        else{
            $error3 = 0;
        }

        if(strlen($movieTitle) <= 2)
        {
            $fixMsg .= "<br>Please make the title at least 5 characters!";
            $error = 1;
        }
        else{
            $error = 0;
        }
        
        if(strlen($movieDescripton) <= 15)
        {
            $fixMsg .= "<br>Please make the Description at least 15 characters!";
            $error2 = 1;
        }
        else{
            $error2 = 0;
        }

        if($error == 0 && $error2 == 0 && $error3 == 0)
        {
            if(!empty($_FILES["file"]["name"])){
                
                // Allow certain file formats
                $allowTypes = array('jpg','png','jpeg','gif','pdf');        //all of this is for my uploading images
                
                if(in_array($fileType, $allowTypes)){
                    
                    // Upload file to server
                    if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
                        $returnedAcnt = getUser($_SESSION['user']);

                        if(count($returnedAcnt)){

                            foreach($returnedAcnt as $creator){
                                //getting the user information from the table and storing into session variables to display on pages
                                $creatorName = $creator['Username'];
                            }
                        }
                
                        $isApproved = 0;
                        
                        $statusMsg = editMovie($movieTitle, $movieGenre, $movieDescripton, $isApproved, $fileName, $movieTrailer, $_SESSION['user'], $movieID);         //adds the movie

                        header('Location: ../UserProfile/profilePage.php');
                    }
                    else
                    {
                        $statusMsg = "Sorry, there was an error uploading your file.";
                    }
                }
                else
                {
                    $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
                }
            }
            else
            {
                $statusMsg = 'Please select a file to upload.';
            }       
        }
        else
        {
            $fixMsg .= '<br>please fix errors';
        }
    }



    if(isset($_POST['deleteBtn'])){
        deleteMovie($_SESSION['user'], $movieID);

        header('Location: ../UserProfile/profilePage.php');
    }
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
                        <form action='MoviePageCRUD.php' method='post' enctype="multipart/form-data">

                            <input name="actionType" type="hidden" class="form-control" value="<?php if($action != ''){echo $action;}?>">
                            <input name="movieID" type="hidden" class="form-control" value="<?php if($movieID != ''){echo $movieID;} ?>">

                            <div class="form-group">
                                <label  for="exampleFormControlInput1">Movie Title</label>
                                <input name="movieTitle" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Title" value="<?php if($btnString != 'Create' || isset($_POST['submitBtn'])){echo $movieTitle;} ?>">
                            </div>

                            <label class="form-label" for="customFile">Upload Movie Image</label>
                            <input name='file' type="file" class="form-control" id="customFile" value="" />

                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Movie Description</label>
                                <textarea style='height:100px; width:100%; word-wrap: break-word;' name='movieDescripton' class="form-control" id="exampleFormControlTextarea1" rows="3"><?php if($btnString != 'Create' || isset($_POST['submitBtn'])){echo $movieDescripton;} ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Movie Trailer</label>
                                <textarea name='movieTrailer' class="form-control" id="exampleFormControlTextarea1" rows="1" placeholder="Enter In A YouTube Link!"><?php if($btnString != 'Create' || isset($_POST['submitBtn'])){echo $movieTrailer;} ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Genre Select</label>
                                <select name='movieGenre' class="form-control" id="exampleFormControlSelect1" value="<?php if($btnString != 'Create' || isset($_POST['submitBtn'])){echo $movieGenre;} ?>">
                                <!--Movie options-->
                                    <option>Action</option>
                                    <option>Adventure</option>
                                    <option>Horror</option>
                                    <option>Comedy</option>
                                    <option>Family</option>
                                    <option>Thriller</option>
                                    <option>Drama</option>
                                    <option>Science Fiction</option> <!--Change to sci-fi?-->
                                    <option>Romance</option>
                                    <option>Western</option>
                                    <option>Crime</option>
                                    <option>Musical</option>
                                    <option>Fantasy</option>
                                </select>
                            </div>

                            <div class="row p-4">
                                <div class="col-6">
                                    <button name='<?php if($btnString == "Update"){echo "editBtn";}else{echo "submitBtn";}?>' type="submit" class="btn btn-primary"><?php echo $btnString?></buton>
                                </div>

                                <?php 
                                    if($btnString == "Update")
                                    {
                                        echo "
                                        <div class='col-6 d-flex justify-content-end'>
                                            <button name='deleteBtn' type='submit' class='btn btn-primary'>Delete</buton>
                                        </div>";
                                    }
                                ?>
                            </div>
                        </form>
                    </div>

                    <div id="spacer" class="col-3"></div>


                    <?php
                        // Display status message and fix errors
                        echo $fixMsg;
                        echo $statusMsg;
                    ?> 
                </div>
            </div>
        </div>
    </div>  
</body>
</html>