<?php
include('includes/authentication.php');
include('includes/dbcon.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $content = mysqli_real_escape_string($con, $_POST['content']);
    $clinic_code = $_GET['clinic_code'];

    // Insert post into the database
    $query = "INSERT INTO clinic_posts (clinic_code, title, content) 
              VALUES ('$clinic_code', '$title', '$content')";

    if (mysqli_query($con, $query)) {
        $_SESSION['success'] = "Post uploaded successfully.";
    } else {
        $_SESSION['error'] = "Failed to upload post. Please try again.";
    }

    header('Location: clinic_dashboard.php?clinic_code=' . $clinic_code);
    exit;
}
?>

<div class="container">
    <h2>Upload New Post</h2>
    <form method="POST">
        <div class="form-group mb-3">
            <label for="title">Post Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="content">Post Content</label>
            <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Upload Post</button>
    </form>
</div>
