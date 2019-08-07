<?php
include 'kapcs.php';
?>
    <!DOCTYPE html>
    <html lang="hu-HU">
    <head>
        <meta charset="UTF-8">
        <title>Risky Jobs - Search</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
              integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
              crossorigin="anonymous">
    </head>
<body class="hatter">
<div class="container">
    <h1 class="text-center">Risky Jobs</h1>
    <br>
    <p class="text-center">Danger! Your dream job is out there.
        <br>Dou you have the guts to go find it?</p>
    <br>
<?php

if(isset($_POST['submit'])) {};

    if (!empty($_GET['sort'])) {
        $sort = $_GET['sort'];
    } else {
        $sort = '';
    }
    if (!empty($_GET['search'])) {
        $search = $_GET['search'];
    } else {
        $search = mysqli_real_escape_string($kapcs, trim($_POST['search']));
    }
    if(!empty($_GET['cur_page'])){
        $cur_page= $_GET['cur_page'];
    }
    else{
        $cur_page= 1;
    }
    $keresett_szavak = keresett_szo($search);
    $result_per_page = 3;
    $skip = ($cur_page -1)*$result_per_page;
    $num_pages = num_pages_total($result_per_page, $keresett_szavak);
    $result = kereses($sort, $result_per_page, $skip, $keresett_szavak);
    $sort=sorted($search, $sort, $cur_page);
    $tomb = array();
    while ($row = mysqli_fetch_array($result)) {
        $tomb[] = $row;
    }
    foreach ($tomb as $item) {
        $title = $item['title'];
        $description = substr($item['description'], 0, 10) . "...";
        $state = $item['state'];
        $date_posted = $item['date_posted'];
        echo "<div class=\"col-3 col-sm-3\">$title</div>";
        echo "<div class=\"col-5 col-sm-5\">$description</div>";
        echo "<div class=\"col-1 col-sm-1\">$state</div>";
        echo "<div class=\"col-3 col-sm-3\">$date_posted</div>";
        echo "<div class='w-100'></div>";
    }
    if($num_pages > 1) {
        $cur_page = oldalhivatkozas($search, $sort, $num_pages, $cur_page);
    }

    ?>

    <div>
    </div>
    </div>
    </div>
    </body>
    </html>

    <?php


function keresett_szo($search){
    $search_szavak = explode(' ', $search);
    $search_szavak = preg_replace("/[^a-zA-Z 0-9]+/", "", $search_szavak);
    $keresett_szavak = implode("%' OR description LIKE '%", $search_szavak);
    return $keresett_szavak;
}


function num_pages_total($result_per_page, $keresett_szavak){
    global $kapcs;
    $query_alap =  "SELECT job_id, title, description, state, date_posted FROM riskyjobs WHERE description LIKE '%" . $keresett_szavak . "%'";
    $result1 = mysqli_query($kapcs, $query_alap);
    $total = mysqli_num_rows($result1);
    $num_pages = ceil($total/$result_per_page);
    return $num_pages;
}


function kereses( $sort='', $result_per_page, $skip, $keresett_szavak){
    global $kapcs;
    if ($sort == 1) $sort = 'ORDER BY title';
    if ($sort == 2) $sort = 'ORDER BY title DESC';
    if ($sort == 3) $sort = 'ORDER BY state';
    if ($sort == 4) $sort = 'ORDER BY state DESC';
    if ($sort == 5) $sort = 'ORDER BY date_posted';
    if ($sort == 6) $sort = 'ORDER BY date_posted DESC';
    $query = "SELECT job_id, title, description, state, date_posted FROM riskyjobs WHERE description LIKE '%" . $keresett_szavak . "%'".$sort . " LIMIT " .$skip. "," .$result_per_page;
    $result = mysqli_query($kapcs, $query);
    return $result;
}


function sorted($search, $sort='', $cur_page){

    switch ($sort) {
        case 1:
            echo '<div class="row">';
            echo '<div class="col-3 col-sm-3"><strong><a href = "' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=2&cur_page='.$cur_page.'">Állás</a></strong></div>';
            echo '<div class="col-5 col-sm-5"><strong>Leírás</strong></div>';
            echo '<div class="col-1 col-sm-1"><strong><a href = "' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=3&cur_page='.$cur_page.'">Állam</a></strong></div>';
            echo '<div class="col-3 col-sm-3"><strong><a href = "' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=5&cur_page='.$cur_page.'">Meghirdetés napja</a></strong></div>';
            echo '<div class="w-100"></div>';
            break;
        case 3:
            echo '<div class="row">';
            echo '<div class="col-3 col-sm-3"><strong><a href = "' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=1&cur_page='.$cur_page.'">Állás</a></strong></div>';
            echo '<div class="col-5 col-sm-5"><strong>Leírás</strong></div>';
            echo '<div class="col-1 col-sm-1"><strong><a href = "' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=4&cur_page='.$cur_page.'">Állam</a></strong></div>';
            echo '<div class="col-3 col-sm-3"><strong><a href = "' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=5&cur_page='.$cur_page.'">Meghirdetés napja</a></strong></div>';
            echo '<div class="w-100"></div>';
            break;
        case 5:
            echo '<div class="row">';
            echo '<div class="col-3 col-sm-3"><strong><a href = "' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=1&cur_page='.$cur_page.'">Állás</a></strong></div>';
            echo '<div class="col-5 col-sm-5"><strong>Leírás</strong></div>';
            echo '<div class="col-1 col-sm-1"><strong><a href = "' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=3&cur_page='.$cur_page.'">Állam</a></strong></div>';
            echo '<div class="col-3 col-sm-3"><strong><a href = "' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=6&cur_page='.$cur_page.'">Meghirdetés napja</a></strong></div>';
            echo '<div class="w-100"></div>';
            break;
        default:
            echo '<div class="row">';
            echo '<div class="col-3 col-sm-3"><strong><a href = "' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=1&cur_page='.$cur_page.'">Állás</a></strong></div>';
            echo '<div class="col-5 col-sm-5"><strong>Leírás</strong></div>';
            echo '<div class="col-1 col-sm-1"><strong><a href = "' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=3&cur_page='.$cur_page.'">Állam</a></strong></div>';
            echo '<div class="col-3 col-sm-3"><strong><a href = "' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=5&cur_page='.$cur_page.'">Meghirdetés napja</a></strong></div>';
            echo '<div class="w-100"></div>';
    }
    return $sort;
}


function oldalhivatkozas($search, $sort='', $num_pages, $cur_page)
{
    if($cur_page > 1) {
        echo '<a href="' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=' . $sort . '&cur_page=' . ($cur_page - 1) . '"> <-  </a>';
    }
    else {
        echo '<- ';
    }
    for ($i = 1; $i <= $num_pages; $i++) {
        if($cur_page == $i) {
            echo ' '. ($i);
        }
        else{
            echo '<a href="' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=' . $sort . '&cur_page=' . $i . '">' . $i . ' </a>';
        }
    }
    if($cur_page < $num_pages){
        echo '<a href="' . $_SERVER['PHP_SELF'] . '?search=' . $search . '&sort=' . $sort . '&cur_page=' . ($cur_page + 1) . '">  -> </a>';
    }
    else{
        echo '->';
    }
    return $cur_page;
}


