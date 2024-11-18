<?php
include('includes/authentication.php');
include('includes/dbcon.php');

// Check if the required POST data is present
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $clinic_id = $_POST['clinic_id'];
    $patient_name = $_POST['patient_name'];
    $patient_email = $_POST['patient_email'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $consultation_type = $_POST['consultation_type'];

    // Check if the appointment date is in the future
    if (strtotime($appointment_date) < strtotime('today')) {
        $_SESSION['error'] = "You cannot book an appointment for a past date.";
        header('Location: clinic_details.php?clinic_id=' . $clinic_id);
        exit;
    }

    // Check if the clinic allows the selected consultation type
    $clinic_query = "SELECT * FROM clinics WHERE clinic_id = ?";
    $stmt = mysqli_prepare($con, $clinic_query);
    mysqli_stmt_bind_param($stmt, "i", $clinic_id);
    mysqli_stmt_execute($stmt);
    $clinic_result = mysqli_stmt_get_result($stmt);
    $clinic = mysqli_fetch_assoc($clinic_result);

    if ($consultation_type == 'online' && $clinic['allow_online_consultation'] == 0) {
        $_SESSION['error'] = "This clinic does not allow online consultations.";
        header('Location: clinic_details.php?clinic_id=' . $clinic_id);
        exit;
    }

    // Check if the selected time slot is available for the given date
    $appointment_query = "SELECT * FROM appointments WHERE clinic_id = ? AND appointment_date = ? AND appointment_time = ?";
    $stmt_appointment = mysqli_prepare($con, $appointment_query);
    mysqli_stmt_bind_param($stmt_appointment, "iss", $clinic_id, $appointment_date, $appointment_time);
    mysqli_stmt_execute($stmt_appointment);
    $appointment_result = mysqli_stmt_get_result($stmt_appointment);

    if (mysqli_num_rows($appointment_result) > 0) {
        $_SESSION['error'] = "The selected time slot is already booked. Please choose a different time.";
        header('Location: clinic_details.php?clinic_id=' . $clinic_id);
        exit;
    }

    // Insert the new appointment into the database
    $insert_query = "INSERT INTO appointments (clinic_id, patient_name, patient_email, appointment_date, appointment_time, consultation_type, status) 
                     VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    $stmt_insert = mysqli_prepare($con, $insert_query);
    mysqli_stmt_bind_param($stmt_insert, "isssss", $clinic_id, $patient_name, $patient_email, $appointment_date, $appointment_time, $consultation_type);

    if (mysqli_stmt_execute($stmt_insert)) {
        $_SESSION['success'] = "Your appointment has been successfully booked.";
    } else {
        $_SESSION['error'] = "Failed to book your appointment. Please try again later.";
    }

    // Redirect back to the clinic details page
    header('Location: clinic_details.php?clinic_id=' . $clinic_id);
    exit;
} else {
    // Redirect to clinic search if the form is accessed directly
    $_SESSION['error'] = "Invalid request.";
    header('Location: search_clinics.php');
    exit;
}
?>
