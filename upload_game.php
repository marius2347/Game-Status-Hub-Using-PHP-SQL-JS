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



        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarFile)) {
            

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
