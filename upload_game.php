<?php
include('database.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['uploadGame'])) {
        $title = mysqli_real_escape_string($connection_string, $_POST['title']);
        $status = mysqli_real_escape_string($connection_string, $_POST['status']);
        $trailer = mysqli_real_escape_string($connection_string, $_POST['trailer']);


        $avatarName = basename($_FILES['avatar']['name']);
        $avatarDir = "images/";
        $avatarFile = $avatarDir . $avatarName;


        $wallpaperName = basename($_FILES['wallpaper']['name']);
        $wallpaperDir = "wallpapers/";
        $wallpaperFile = $wallpaperDir . $wallpaperName;


        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarFile) &&
            move_uploaded_file($_FILES['wallpaper']['tmp_name'], $wallpaperFile)) {
            

            $query = "INSERT INTO games (title, image, status, trailer) 
                      VALUES ('$title', '$avatarName', '$status', '$trailer')";
            if (mysqli_query($connection_string, $query)) {
                header("Location: index.php?message=Game+uploaded+successfully");
                exit();
            }
            else {
                echo "<script>alert('Database error: " . mysqli_error($connection_string) . "');</script>";
            }
        } else {
            echo "<script>alert('Error uploading images.');</script>";
        }
    }
}
?>
