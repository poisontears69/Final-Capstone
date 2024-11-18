<?php
include('includes/authentication.php');
include('includes/dbcon.php');
$page_title = 'Clinic Details';
include('includes/header.php');
// Check if clinic_id is provided in the URL
if (!isset($_GET['clinic_id']) || empty($_GET['clinic_id'])) {
    $_SESSION['error'] = "Clinic ID is missing.";
    header('Location: search_clinics.php');
    exit;
}

// Get clinic details from the database
$clinic_id = $_GET['clinic_id'];

// Query for clinic info (including business hours and workdays)
$clinic_query = "SELECT * FROM clinics WHERE clinic_id = ?";
$stmt = mysqli_prepare($con, $clinic_query);
mysqli_stmt_bind_param($stmt, "i", $clinic_id);
mysqli_stmt_execute($stmt);
$clinic_result = mysqli_stmt_get_result($stmt);
$clinic = mysqli_fetch_assoc($clinic_result);

// Query for clinic posts
$posts_query = "SELECT * FROM clinic_posts WHERE clinic_id = ? ORDER BY created_at DESC";
$stmt_posts = mysqli_prepare($con, $posts_query);
mysqli_stmt_bind_param($stmt_posts, "i", $clinic_id);
mysqli_stmt_execute($stmt_posts);
$posts_result = mysqli_stmt_get_result($stmt_posts);
$posts = mysqli_fetch_all($posts_result, MYSQLI_ASSOC);

// Query for clinic photos
$photos_query = "SELECT * FROM clinic_photos WHERE clinic_id = ?";
$stmt_photos = mysqli_prepare($con, $photos_query);
mysqli_stmt_bind_param($stmt_photos, "i", $clinic_id);
mysqli_stmt_execute($stmt_photos);
$photos_result = mysqli_stmt_get_result($stmt_photos);
$photos = mysqli_fetch_all($photos_result, MYSQLI_ASSOC);

// Query for the doctor (creator) who created the clinic (using creator_user_id)
$doctor_query = "SELECT * FROM users WHERE user_id = ?";
$stmt_doctor = mysqli_prepare($con, $doctor_query);
mysqli_stmt_bind_param($stmt_doctor, "i", $clinic['creator_user_id']);
mysqli_stmt_execute($stmt_doctor);
$doctor_result = mysqli_stmt_get_result($stmt_doctor);
$doctor = mysqli_fetch_assoc($doctor_result);

// Get the clinic's business hours for available time slots
$business_hours = $clinic['business_hours']; // Assuming business_hours is a string like '9:00 AM - 5:00 PM'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($clinic['clinic_name']); ?> - Clinic Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .timeline-item {
            border-left: 2px solid #007bff;
            margin-left: 20px;
            padding-left: 15px;
            margin-bottom: 15px;
        }
        .timeline-item img {
            max-width: 100%;
            height: auto;
        }
        .timeline-item .post-content {
            margin-top: 10px;
        }
        .timeline-item .post-header {
            font-weight: bold;
        }
        .appointment-form {
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row">
        <!-- Clinic Information and Timeline -->
        <div class="col-md-8">
            <h1><?php echo htmlspecialchars($clinic['clinic_name']); ?></h1>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($clinic['description']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($clinic['phone']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($clinic['location']); ?></p>
            <p><strong>Business Hours:</strong> <?php echo htmlspecialchars($clinic['business_hours']); ?></p>
            <p><strong>Workdays:</strong> <?php echo htmlspecialchars($clinic['workdays']); ?></p>
            <p><strong>Created by:</strong> Dr. <?php echo htmlspecialchars($doctor['name']); ?></p>

            <hr>

            <!-- Clinic Photos -->
            <div class="mb-4">
                <h3>Clinic Photos</h3>
                <div class="row">
                    <?php foreach ($photos as $photo): ?>
                        <div class="col-md-4 mb-3">
                            <img src="uploads/<?php echo $photo['photo']; ?>" alt="Clinic Photo" class="img-fluid">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Clinic Posts (Timeline) -->
            <div class="timeline">
                <h3>Clinic Posts</h3>
                <?php foreach ($posts as $post): ?>
                    <div class="timeline-item">
                        <div class="post-header"><?php echo htmlspecialchars($post['post_title']); ?></div>
                        <div class="post-content"><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></div>
                        <p class="text-muted"><small>Posted on: <?php echo date("F j, Y, g:i a", strtotime($post['created_at'])); ?></small></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Appointment Booking Form -->
        <div class="col-md-4">
            <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success text-center">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger text-center">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
            <div class="appointment-form">
                <h3>Book an Appointment</h3>
                <form action="book_appointment.php" method="POST">
                    <input type="hidden" name="clinic_id" value="<?php echo $clinic['clinic_id']; ?>">
                    <div class="mb-3">
                        <label for="patient_name" class="form-label">Your Name</label>
                        <input type="text" name="patient_name" id="patient_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="patient_email" class="form-label">Your Email</label>
                        <input type="email" name="patient_email" id="patient_email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="appointment_date" class="form-label">Preferred Appointment Date</label>
                        <input type="date" name="appointment_date" id="appointment_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="consultation_type" class="form-label">Consultation Type</label>
                        <select name="consultation_type" id="consultation_type" class="form-control" required>
                            <option value="online">Online</option>
                            <option value="walk-in">Walk-in</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="appointment_time" class="form-label">Available Appointment Time</label>
                        <select name="appointment_time" id="appointment_time" class="form-control" required>
                            <!-- Populating with predefined 1-hour intervals -->
                            <?php
                                // Define available times based on business hours (e.g., 9:00 AM - 5:00 PM)
                                $time_slots = [];
                                $start_time = strtotime('9:00 AM');
                                $end_time = strtotime('5:00 PM');
                                
                                while ($start_time < $end_time) {
                                    $time_slots[] = date('H:i', $start_time);
                                    $start_time = strtotime('+1 hour', $start_time);
                                }

                                foreach ($time_slots as $time) {
                                    echo "<option value='$time'>$time</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Book Appointment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
