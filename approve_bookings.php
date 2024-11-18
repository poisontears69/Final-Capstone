<?php
include('includes/authentication.php');
include('includes/dbcon.php');

// Get the booking ID and action (approve or deny) from the URL
$booking_id = $_GET['booking_id'];
$action = $_GET['action'];

// Validate the action (only approve or deny are valid)
if (!in_array($action, ['approve', 'deny'])) {
    $_SESSION['error'] = "Invalid action.";
    header('Location: clinic_dashboard.php');
    exit;
}

// Update the booking status in the database
$update_query = "UPDATE bookings SET status = '$action' WHERE booking_id = '$booking_id'";

if (mysqli_query($con, $update_query)) {
    $_SESSION['success'] = "Booking has been $action successfully.";
} else {
    $_SESSION['error'] = "Failed to update booking status. Please try again.";
}

header('Location: clinic_dashboard.php?clinic_code=' . $_GET['clinic_code']);
exit;
?>
