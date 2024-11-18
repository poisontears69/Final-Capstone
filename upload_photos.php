<?php
include('includes/authentication.php');
include('includes/dbcon.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $clinic_code = $_GET['clinic_code'];
    $photo = $_FILES['photo'];

    // Validate photo
    if ($photo['error'] == 0) {
        $photo_name = $photo['name'];
        $photo_tmp = $photo['tmp_name'];
        $photo_ext = pathinfo($photo_name, PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($photo_ext), $allowed_extensions)) {
            $new_photo_name = uniqid('photo_') . '.' . $photo_ext;
            $upload_path = 'uploads/' . $new_photo_name;

            // Move the uploaded file
            if (move_uploaded_file($photo_tmp, $upload_path)) {
                // Insert photo into the database
                $insert_query = "INSERT INTO clinic_photos (clinic_code, photo_url) 
                                 VALUES ('$clinic_code', '$upload_path')";

                if (mysqli_query($con, $insert_query)) {
                    $_SESSION['success'] = "Photo uploaded successfully.";
                } else {
                    $_SESSION['error'] = "Failed to upload photo to the database. Please try again.";
                }
            } else {
                $_SESSION['error'] = "Failed to upload photo. Please try again.";
            }
        } else {
            $_SESSION['error'] = "Invalid file format. Only JPG, PNG, and GIF files are allowed.";
        }
    } else {
        $_SESSION['error'] = "Please select a valid photo to upload.";
    }

    header('Location: clinic_dashboard.php?clinic_code=' . $clinic_code);
    exit;
}
?>

<div class="container">
    <h2>Upload Photo</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label for="photo">Select Photo</label>
            <input type="file" name="photo" id="photo" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload Photo</button>
    </form>
</div>
