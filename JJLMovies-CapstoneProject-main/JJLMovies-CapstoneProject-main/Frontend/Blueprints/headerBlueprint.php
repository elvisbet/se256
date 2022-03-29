<link rel="stylesheet" href="../CSS/pageHeader.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://kit.fontawesome.com/18ddcc2bb6.js" crossorigin="anonymous"></script>

<div id="pageHeader" class="row header centerV no-marginL flex-nowrap">
    <nav id="logoContainer" class="col-auto transparent centerV no-padL navbar-expand-lg navbar-light bg-light">
        <div class="row flex-nowrap" style="align-items: center;">
            <div class="col-auto no-padR">
                <a class="btn btn-primary" id="sidebarCollapseBtnHead"><i id="sidebarCollapseBtnIcon" class="fa-solid fa-bars"></i></a>
            </div>

            <a id="headerLogo" href="/Frontend/MoviePage/homePage.php" class="col-9"><img id='navLogo' class="text-center no-pad" src="/images/logo-icon.png"></a>
        </div>
    </nav>

    <div id="headerSearchContainer" class="center headerBtn col-auto">
        <input id="headerSearch" name="headerSearch" type="text" placeholder="Search">

        <div id="headerSearchBox">
            
        </div>
    </div>

    <div id="loginContainer" class="centerV headerBtn col-auto justify-content-end no-pad">
        <a href="<?php if(isset($_SESSION['user'])){echo "/Frontend/UserProfile/profilePage.php";}else{echo "/Frontend/Login-Signup/loginPage.php";} ?>"><img src="<?php if(isset($_SESSION['user'])){echo "/images/profile-icon-logged-in.png";}else{echo "/images/profile-icon-logged-out.png";} ?>" width="50px" height="50px"; ></a>
    </div>
</div>

<script>
    function searchRequest() {
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
                var ajaxDisplay = document.getElementById('headerSearchBox');
                ajaxDisplay.innerHTML = ajaxRequest.responseText;
            }
        }

        ajaxRequest.open("GET", "/Frontend/Blueprints/searchResults.php?searchTxt=" + $('#headerSearch').val(), true);
        ajaxRequest.send(null); 
    }



    $(document).ready(function () {
        //Sideabar and fade layer view functionality
        $('#sidebarCollapseBtnHead').on('click', function () {
            $('#sidebar').toggleClass('active');
            $('#fadeLayer').toggleClass('active');
        });

        $('#fadeLayer').on('click', function () {
            $('#sidebar').removeClass('active');
            $('#fadeLayer').removeClass('active');
        });

        //Headersearch box view functionality
        $('#headerSearch').on('input', function () {
            if($('#headerSearch').val().length > 0)
            {
                $('#headerSearchBox').addClass('active');
                
                searchRequest();
            }
            else{
                $('#headerSearchBox').removeClass('active');
            }
        });

        $('#headerSearch').on('focusin', function () {

            $('#headerSearchContainer').addClass('active');
            $('#logoContainer').addClass('active');

            if($('#headerSearch').val().length > 0)
            {
                $('#headerSearchBox').addClass('active');
                
                searchRequest();
            }
        });

        /*$('#headerSearch').on('focusout', function () {
            
            $('#headerSearchBox').removeClass('active');

            $('#headerSearchContainer').removeClass('active');
            $('#logoContainer').removeClass('active');
        });*/
    });
</script>