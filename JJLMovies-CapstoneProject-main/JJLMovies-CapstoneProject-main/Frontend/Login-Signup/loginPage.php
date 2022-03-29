<?php
    session_start();

    $_SESSION["loggedIn"] = false;

    require (__DIR__ . "/../../Backend/dbQuery.php"); 

    $loginFailed = "";

    //Checks if the user clicks log in and tests input (password storage and checks must be done in sha256 encryption)
    if(isPostRequest()) {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        $returnedAcnt = validateLogin($username, $password);
        
        if(count($returnedAcnt)){

            foreach($returnedAcnt as $user){
                //getting the user information from the table and storing into session variables to display on pages
                $user_ID = $user['UserAccountID'];
                $username = $user['Username'];
            }

            $_SESSION['user'] = $user_ID;
            $_SESSION['Username'] = $username;

            $_SESSION["loggedIn"] = true;
        
            header('Location: ../MoviePage/homePage.php');   
        }
        else{
            $_SESSION["loggedIn"] = false;
            $loginFailed = 'Sorry, Login failed'; //does not allow user to enter the other pages until logged in 
        }
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
            <!--Made sure the login is exactly how the figma looks-->
            <div id="content">
                <div id="formContainer" class="row center no-margin no-padL">
                    <h1 class="col-xl-12 center">Login</h1>

                    <div id="spacer" class="col-3"></div>
                    
                    <form action="loginPage.php" method="post" class="col-6">
                        <div class="row d-flex flex-column align-items-center">
            
                            <div>
                                <div class="row">
                                    <div class="col-12 center">Username</div>

                                    <div class="col-12 center mb-3">
                                        <input type="text" name="username">
                                    </div>
                                </div>
                            </div>
                            <!--Make sure input type is password for extra security-->
                            <div>
                                <div class="row">
                                    <div class="col-12 center">Password</div>

                                    <div class="col-12 center mb-3">
                                        <input type="password" name="password">
                                    </div>
                                </div>
                            </div>  

                            
                            <div class>
                                <div id="login" class="center headerBtn mb-3">
                                <input type="submit" name="Login" value="Login" class="btn btn-primary"></a></input>
                                </div>
                                
                                <div id="createAcnt" class="center headerBtn mb-3">
                                    <a href="/Frontend/Login-Signup/signupPage.php" class="btn btn-primary">Create Account</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="spacer" class="col-3"></div>
                </div>

                <?php 
                //Made sure user is aware that they have entered invalid login information 
                    if($loginFailed != ""){
                        echo "<div class='row center'><h2 style='color:red;font-size:20px; margin-top: 20px;'>Please enter in a valid username and password.</h2></div>"; 
                    }
                ?> 

            </div>    
        </div>
    </div>
</body>
</html>
