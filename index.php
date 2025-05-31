<?php
include('database.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GamesTP</title>

    <!-- CSS + icons -->
    <link rel="stylesheet" href="./CSS/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="./logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">

    <!-- JS -->
    <script src="app.js" defer></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
</head>
<body>

<!-- HEADER -->
<header class="nav-down">
     <div class="header-logo">
        <a href="./index.php">
            <h1 class="desktop" title="Home">GTP</h1>
        </a>
    </div>

    <div class="header-content">
        <!-- Search -->
        <div class="header-searchbar">
            <form class="example" action="index.php" method="post">
                <input type="text" name="search" placeholder="Search a game...">
                <button type="submit" name="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>

        <!-- Filter-chips -->
        <div class="buttons">
            <form action="index.php" method="post">
                <input type="hidden" name="action" value="finished">
                <button type="submit" class="btn-class">Finished</button>
            </form>
            <form action="index.php" method="post">
                <input type="hidden" name="action" value="unfinished">
                <button type="submit" class="btn-class">Unfinished</button>
            </form>
            <form action="index.php" method="post">
                <input type="hidden" name="action" value="played">
                <button type="submit" class="btn-class">Played</button>
            </form>
        </div>
    </div>

    <div class="header-contact">
        <button id="uploadBtn" class="btn-class upload-btn">Upload&nbsp;Game</button>
    </div>
</header>

<!-- TOAST -->
<div id="messageBox" class="message-box"></div>

<script>
const params  = new URLSearchParams(window.location.search);
const message = params.get('message');
if (message){
    const box = document.getElementById('messageBox');
    box.textContent = decodeURIComponent(message);
    box.classList.add('show');
    setTimeout(()=>{window.history.replaceState(null,null,window.location.pathname);},5000);
}
</script>

<!-- MODALE UPLOAD  -->
<div id="adminLoginOverlay" class="modal-overlay">
    <div class="modal-content">
        <form method="post" action="upload_game.php">
            <h3>Enter Admin Password</h3>
            <input type="password" id="adminPassword" name="adminPassword" required placeholder="Enter password">
            <button type="submit">Submit</button>
        </form>
    </div>
</div>

<div id="gameUploadOverlay" class="modal-overlay">
    <div class="modal-content">
        <form method="post" action="upload_game.php" enctype="multipart/form-data">
            <h3>Upload Game</h3>

            <input type="text" id="title" name="title" required placeholder="Game Title">

            <select id="status" name="status" required>
                <option value="STORY COMPLETED">STORY COMPLETED</option>
                <option value="STORY NOT COMPLETED">STORY NOT COMPLETED</option>
                <option value="JUST PLAYED">JUST PLAYED</option>
            </select>

            <div class="upload-section">
                <label for="avatar">Avatar Upload (420×200):</label>
                <input type="file" id="avatar" name="avatar" accept="image/*" required>
            </div>

            <div class="upload-section">
                <label for="wallpaper">Wallpaper Upload (1920×1080):</label>
                <input type="file" id="wallpaper" name="wallpaper" accept="image/*" required>
            </div>

            <input type="url" id="trailer" name="trailer" required placeholder="Trailer Link">
            <button type="submit" name="uploadGame">Upload Game</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',()=>{
    const uploadBtn          = document.getElementById('uploadBtn');
    const adminOverlay       = document.getElementById('adminLoginOverlay');
    const uploadOverlay      = document.getElementById('gameUploadOverlay');
    const adminPasswordForm  = adminOverlay.querySelector('form');

    uploadBtn?.addEventListener('click',()=>{adminOverlay.style.display='flex';});

    adminPasswordForm.addEventListener('submit',e=>{
        e.preventDefault();
        if(document.getElementById('adminPassword').value === 'you_thought...'){
            adminOverlay.style.display='none';
            uploadOverlay.style.display='flex';
        }else{alert('Invalid password!');}
    });

    [adminOverlay,uploadOverlay].forEach(ov=>{
        ov.addEventListener('click',e=>{if(e.target===ov) ov.style.display='none';});
    });
});
</script>

<!-- COUNTERS -->
<div class="box">
<?php
    $db = $connection_string;

    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'finished':
                $query = "SELECT COUNT(*) FROM games WHERE status = 'STORY COMPLETED'";
                $label = "Finished";
                break;
            case 'unfinished':
                $query = "SELECT COUNT(*) FROM games WHERE status = 'STORY NOT COMPLETED'";
                $label = "Unfinished";
                break;
            case 'played':
                $query = "SELECT COUNT(*) FROM games WHERE status = 'JUST PLAYED'";
                $label = "Played";
                break;
            default:
                $query = ""; 
                $label = "";
        }

        if ($query !== "") {
            $res = mysqli_query($db, $query);
            $cnt = mysqli_fetch_row($res)[0];
            echo '<h1 style="font-size:18px">'.$label.': '.$cnt.'</h1>';
        }

    } elseif (!isset($_POST['submit'])) {
        // 1. STO­RY COMPLETED
        $query_SC = "SELECT COUNT(*) FROM games WHERE status = 'STORY COMPLETED'";
        $result_SC = mysqli_query($db, $query_SC);
        $rows_SC = mysqli_fetch_row($result_SC);
        echo '<h1 style="font-size:18px">Finished: ' . $rows_SC[0] . '</h1>';

        // 2. STO­RY NOT COMPLETED
        $query_SNC = "SELECT COUNT(*) FROM games WHERE status = 'STORY NOT COMPLETED'";
        $result_SNC = mysqli_query($db, $query_SNC);
        $rows_SNC = mysqli_fetch_row($result_SNC);
        echo '<h1 style="font-size:18px">Unfinished: ' . $rows_SNC[0] . '</h1>';

        // 3. JUST PLAYED
        $query_JP = "SELECT COUNT(*) FROM games WHERE status = 'JUST PLAYED'";
        $result_JP = mysqli_query($db, $query_JP);
        $rows_JP = mysqli_fetch_row($result_JP);
        echo '<h1 style="font-size:18px">Played: ' . $rows_JP[0] . '</h1>';
    }
?>
</div>


<!--THUMBNAILS -->
<div class="container">
<?php
if (isset($_POST['submit'])){

    $search      = trim(htmlentities($_POST['search']));
    $searchParam = '%'.mysqli_real_escape_string($connection_string,$search).'%';

    $stmt = $connection_string->prepare("SELECT * FROM games WHERE title LIKE ?");
    $stmt->bind_param('s',$searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows===0){
        echo "<h1>No game found.</h1>";
    }else{
        while($row=$result->fetch_assoc()){
            showGame($row);
        }
    }


}elseif (isset($_POST['action'])){

    switch($_POST['action']){
        case 'finished'  : $sql="SELECT * FROM games WHERE status='STORY COMPLETED'"; break;
        case 'unfinished': $sql="SELECT * FROM games WHERE status='STORY NOT COMPLETED'"; break;
        case 'played'    : $sql="SELECT * FROM games WHERE status='JUST PLAYED'"; break;
        default          : $sql="SELECT * FROM games";
    }
    $result = $connection_string->query($sql);
    if ($result->num_rows===0){
        echo "<h1>No games found.</h1>";
    }else{
        while($row=$result->fetch_assoc()){
            showGame($row);
        }
    }

/* --------------------------- HOME --------------------------- */
}else{
    $result = $connection_string->query("SELECT * FROM games");
    while($row=$result->fetch_assoc()){
        showGame($row);
    }
}

function showGame(array $row):void{
    $image   = $row['image'];
    $status  = $row['status'];
    $title   = $row['title'];
    $trailer = $row['trailer'];

    $wrapperClass = match($status){
        'STORY COMPLETED'   => 'content-green',
        'STORY NOT COMPLETED'=> 'content-red',
        'JUST PLAYED'       => 'content-orange',
        default             => ''
    };

    echo '<a href="'.$trailer.'" target="_blank">';
        echo '<div class="'.$wrapperClass.'">';
            echo '<img class="img-box" src="./images/'.$image.'" alt="'.ucwords($title).' thumbnail" title="Watch trailer for '.ucwords($title).'">';
            if ($wrapperClass) echo '<div class="bottom-center">'.$status.'</div>';
        echo '</div>';
    echo '</a>';
}
?>
</div>

</body>
</html>
