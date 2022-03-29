<link rel="stylesheet" href="../CSS/navDynamic.css">

<div id="fadeLayer"></div>

<nav id="sidebar">
    <div id="sidebarHeader" class="row no-margin">
        <div class="col-auto no-margin no-pad">
            <a id="sidebarCollapseBtnNav" class="btn btn-primary"><i id="sidebarCollapseBtnIcon" class="fa-solid fa-bars"></i></a>
        </div>

        <a href="/Frontend/MoviePage/homePage.php" class="col-9 no-margin"><img id='navLogo' class="text-center no-pad col" src="/images/logo-icon.png"></a>
    </div>

    <div id="spacerLine" class="row no-margin"></div>

    <ul class="list-unstyled components">
        <li>
            <a href="/Frontend/MoviePage/homePage.php" class="row <?php if(basename($_SERVER['PHP_SELF']) == 'homePage.php'){echo 'current-page';} ?> no-marginR"><img id='navIcon' class="text-center no-pad col" src="/images/home-icon.png"><p class="col">Home</p><img id='navArrow' class="text-center col" src="/images/right-arrow.png"></a>
        </li>

        <li>
            <a href="/Frontend/MoviePage/highlightsPage.php" class="row <?php if(basename($_SERVER['PHP_SELF']) == 'highlightsPage.php'){echo 'current-page';} ?> no-marginR"><img id='navIcon' class="text-center no-pad col" src="/images/highlights-icon.png"><p class="col">Highlights</p><img id='navArrow' class="text-center col" src="/images/right-arrow.png"></a>
        </li>

        <li>
            <a href="/Frontend/UserProfile/MoviePageCRUD.php?action=add" class="row <?php if(basename($_SERVER['PHP_SELF']) == 'MoviePageCRUD.php'){echo 'current-page';} ?> no-marginR"><img id='navIcon' class="text-center no-pad col" src="/images/addMovie-icon.png"><p class="col">Add Movie</p><img id='navArrow' class="text-center col" src="/images/right-arrow.png"></a>
        </li>
    </ul>
</nav>

<script>
    $(document).ready(function () {
        $('#sidebarCollapseBtnNav').on('click', function () {
            $('#sidebar').toggleClass('active');
            $('#fadeLayer').toggleClass('active');
        });
    });
</script>