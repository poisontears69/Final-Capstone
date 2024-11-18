<?php
include('includes/authentication.php');
include('includes/dbcon.php');
$page_title = 'Clinic Dashboard';
include('includes/header.php');
include('includes/navbar.php'); 

// Get the clinic id from the URL
$clinic_id = $_GET['clinic_id'];

// Fetch the clinic details using the clinic id
$query = "SELECT * FROM clinics WHERE clinic_id = '$clinic_id' LIMIT 1";
$result = mysqli_query($con, $query);
$clinic = mysqli_fetch_assoc($result);

// Check if the clinic exists
if (!$clinic) {
    $_SESSION['error'] = "Clinic not found.";
    header('Location: dashboard.php');
    exit;
}

// Fetch members of the clinic
$members_query = "SELECT * FROM clinic_members WHERE clinic_id = '$clinic_id'";
$members_result = mysqli_query($con, $members_query);

// Fetch bookings for the clinic (approvals, denials)
$bookings_query = "SELECT * FROM appointments WHERE clinic_id = '$clinic_id'";
$bookings_result = mysqli_query($con, $bookings_query);

// Fetch posts for the clinic
$posts_query = "SELECT * FROM clinic_posts WHERE clinic_id = '$clinic_id'";
$posts_result = mysqli_query($con, $posts_query);

// Fetch photos for the clinic
$photos_query = "SELECT * FROM clinic_photos WHERE clinic_id = '$clinic_id'";
$photos_result = mysqli_query($con, $photos_query);

?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="card">
                    <div class="card-header">
                        <h5>Clinic Dashboard: <?php echo $clinic['clinic_name']; ?></h5>
                    </div>
                    <div class="card-body">
                        <h2>Welcome to Your Clinic Management Dashboard</h2>
                        <hr>
                        <p><strong>Clinic ID:</strong> <?php echo $clinic['clinic_id']; ?></p>

                        <!-- Management Options -->
                        <div class="mb-3">
                            <a href="approve_bookings.php?clinic_id=<?php echo $clinic['clinic_id']; ?>" class="btn btn-primary">Approve/Deny Bookings</a>
                            <a href="manage_members.php?clinic_id=<?php echo $clinic['clinic_id']; ?>" class="btn btn-warning">Manage Clinic Members</a>
                            <a href="upload_posts.php?clinic_id=<?php echo $clinic['clinic_id']; ?>" class="btn btn-success">Upload Posts</a>
                            <a href="upload_photos.php?clinic_id=<?php echo $clinic['clinic_id']; ?>" class="btn btn-info">Upload Photos</a>
                        </div>

                        <!-- Bookings List -->
                        <h4>Bookings</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Patient Name</th>
                                    <th>Consultation Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($booking = mysqli_fetch_assoc($bookings_result)) { ?>
                                    <tr>
                                        <td><?php echo $booking['booking_id']; ?></td>
                                        <td><?php echo $booking['patient_name']; ?></td>
                                        <td><?php echo $booking['consultation_type']; ?></td>
                                        <td><?php echo $booking['status']; ?></td>
                                        <td>
                                            <?php if ($booking['status'] == 'Pending') { ?>
                                                <a href="approve_booking.php?booking_id=<?php echo $booking['booking_id']; ?>&action=approve" class="btn btn-success btn-sm">Approve</a>
                                                <a href="approve_booking.php?booking_id=<?php echo $booking['booking_id']; ?>&action=deny" class="btn btn-danger btn-sm">Deny</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- Clinic Members List -->
                        <h4>Clinic Members</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Member Name</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($member = mysqli_fetch_assoc($members_result)) { ?>
                                    <tr>
                                        <td><?php echo $member['member_id']; ?></td>
                                        <td><?php echo $member['member_name']; ?></td>
                                        <td><?php echo $member['role']; ?></td>
                                        <td><?php echo $member['status']; ?></td>
                                        <td>
                                            <?php if ($member['status'] == 'Pending') { ?>
                                                <a href="approve_member.php?member_id=<?php echo $member['member_id']; ?>&action=approve" class="btn btn-success btn-sm">Approve</a>
                                                <a href="approve_member.php?member_id=<?php echo $member['member_id']; ?>&action=deny" class="btn btn-danger btn-sm">Deny</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- Posts List -->
                        <h4>Posts</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($post = mysqli_fetch_assoc($posts_result)) { ?>
                                    <tr>
                                        <td><?php echo $post['post_id']; ?></td>
                                        <td><?php echo $post['title']; ?></td>
                                        <td><?php echo $post['content']; ?></td>
                                        <td>
                                            <a href="edit_post.php?post_id=<?php echo $post['post_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="delete_post.php?post_id=<?php echo $post['post_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- Photos List -->
                        <h4>Photos</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($photo = mysqli_fetch_assoc($photos_result)) { ?>
                                    <tr>
                                        <td><?php echo $photo['photo_id']; ?></td>
                                        <td><img src="<?php echo $photo['photo_url']; ?>" width="100" alt="Photo"></td>
                                        <td>
                                            <a href="delete_photo.php?photo_id=<?php echo $photo['photo_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </div>            
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
