<?php
include('includes/authentication.php');
include('includes/dbcon.php');

// Get the post ID from the URL
$post_id = $_GET['post_id'];

// Delete the post from the database
$delete_query = "DELETE FROM clinic_posts WHERE post_id = '$post_id'";

if (mysqli_query($con, $delete_query)) {
    $_SESSION['success'] = "Post deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete post. Please try again.";
}

header('Location: clinic_dashboard.php?clinic_code=' . $_GET['clinic_code']);
exit;
?>
