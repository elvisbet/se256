<?php
    require (__DIR__ . "/../../Backend/dbQuery.php");

    $searchTxt = $_GET['searchTxt'];

    $searchResults = searchMovie($searchTxt);

    $resultHtmlStr = "";
?>

<?php
    foreach ($searchResults as $row)
    {
        $resultHtmlStr .= '<a href="/Frontend/MoviePage/moviePage.php?id=' . $row["MovieID"] . '">';
        $resultHtmlStr .= '<div id="searchItem" class="row no-margin no-pad">';
        $resultHtmlStr .= '<div class="col-sm">';
        $resultHtmlStr .= '<div class="row">';
        $resultHtmlStr .= '<div id="searchComponentMovieImg" class="col-auto no-pad">';
        $resultHtmlStr .= '<img src="../../uploads/' . $row["CoverIMG"] . '" width=75px;>';
        $resultHtmlStr .= '</div>';

        $resultHtmlStr .= '<div id="searchComponentMovieDetailsContainer" class="col-auto ml-4">';
        $resultHtmlStr .= '<div id="searchComponentDetails" class="row">';
        $resultHtmlStr .= '<div style="font-size: 20px;">' . $row["MovieTitle"] . '</div>';
        $resultHtmlStr .= '</div>';
                    
        $resultHtmlStr .= '<div id="searchComponentDetails" class="row">';
        $resultHtmlStr .= '<div> &nbsp;' . $row["CreatorName"] . '</div>';
        $resultHtmlStr .= '</div>';

        $resultHtmlStr .= '<div id="searchComponentDetails" class="row">';
        $resultHtmlStr .= '<div> &nbsp;</div>';
        $resultHtmlStr .= '</div>';
        $resultHtmlStr .= '</div>';
        $resultHtmlStr .= '</div>';
        $resultHtmlStr .= '</div>';
        $resultHtmlStr .= '</div>';

        $resultHtmlStr .= '<div id="spacerLine" class="row no-margin" style="margin-top: 10px !important; margin-bottom: 5px !important;"></div>';
        $resultHtmlStr .= '</a>';
    }

    echo $resultHtmlStr;
?>