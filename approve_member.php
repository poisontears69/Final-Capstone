<?php
include('includes/authentication.php');
include('includes/dbcon.php');

// Get the member ID and action (approve or deny) from the URL
$member_id = $_GET['member_id'];
$action = $_GET['action'];

// Validate the action (only approve or deny are valid)
if (!in_array($action, ['approve', 'deny'])) {
    $_SESSION['error'] = "Invalid action.";
    header('Location: clinic_dashboard.php');
    exit;
}

// Update the member status in the database
$update_query = "UPDATE clinic_member SET status = '$action' WHERE member_id = '$member_id'";

if (mysqli_query($con, $update_query)) {
    $_SESSION['success'] = "Member has been $action successfully.";
} else {
    $_SESSION['error'] = "Failed to update member status. Please try again.";
}

header('Location: clinic_dashboard.php?clinic_code=' . $_GET['clinic_code']);
exit;
?>
