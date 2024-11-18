<?php
include('includes/authentication.php');
include('includes/dbcon.php');

// Get the post ID from the URL
$post_id = $_GET['post_id'];

// Fetch the post details from the database
$query = "SELECT * FROM clinic_posts WHERE post_id = '$post_id' LIMIT 1";
$result = mysqli_query($con, $query);
$post = mysqli_fetch_assoc($result);

// Check if post exists
if (!$post) {
    $_SESSION['error'] = "Post not found.";
    header('Location: clinic_dashboard.php');
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $content = mysqli_real_escape_string($con, $_POST['content']);

    // Update the post in the database
    $update_query = "UPDATE clinic_posts SET title = '$title', content = '$content' WHERE post_id = '$post_id'";

    if (mysqli_query($con, $update_query)) {
        $_SESSION['success'] = "Post updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update post. Please try again.";
    }

    header('Location: clinic_dashboard.php?clinic_code=' . $_GET['clinic_code']);
    exit;
}
?>

<div class="container">
    <h2>Edit Post</h2>
    <form method="POST">
        <div class="form-group mb-3">
            <label for="title">Post Title</label>
            <input type="text" name="title" id="title" class="form-control" value="<?php echo $post['title']; ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="content">Post Content</label>
            <textarea name="content" id="content" class="form-control" rows="5" required><?php echo $post['content']; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
</div>
