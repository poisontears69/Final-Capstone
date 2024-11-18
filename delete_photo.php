<?php
include('includes/authentication.php');
include('includes/dbcon.php');

// Get the photo ID from the URL
$photo_id = $_GET['photo_id'];

// Fetch photo URL from the database
$query = "SELECT photo_url FROM clinic_photos WHERE photo_id = '$photo_id' LIMIT 1";
$result = mysqli_query($con, $query);
$photo = mysqli_fetch_assoc($result);

if ($photo) {
    $photo_url = $photo['photo_url'];
    
    // Delete the photo file from the server
    if (file_exists($photo_url)) {
        unlink($photo_url);
    }
    
    // Delete the photo record from the database
    $delete_query = "DELETE FROM clinic_photos WHERE photo_id = '$photo_id'";

    if (mysqli_query($con, $delete_query)) {
        $_SESSION['success'] = "Photo deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete photo from database. Please try again.";
    }
} else {
    $_SESSION['error'] = "Photo not found.";
}

header('Location: clinic_dashboard.php?clinic_code=' . $_GET['clinic_code']);
exit;
?>
