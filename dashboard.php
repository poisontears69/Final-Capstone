<?php
include('includes/authentication.php');
include('includes/dbcon.php'); // Include the database connection

$page_title = 'Dashboard Page';
include('includes/header.php');
include('includes/navbar.php'); 

// Check if the logged-in user has a clinic
$user_id = $_SESSION['auth_user']['user_id']; // Assuming user_id is stored in session
$query = "SELECT * FROM clinics WHERE creator_user_id = '$user_id' LIMIT 1";
$result = mysqli_query($con, $query);

// If the user already has a clinic, redirect to the clinic dashboard
if (mysqli_num_rows($result) > 0) {
    $clinic = mysqli_fetch_assoc($result); // Fetch the clinic details
    header('Location: clinic_dashboard.php?clinic_id=' . $clinic['clinic_id']);
    exit;
}
?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
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
                <div class="card">
                    <div class="card-header">
                        <h5>Dashboard</h5>
                    </div>
                    <div class="card-body">
                        <h2>Access when you are logged in</h2>
                        <hr>
                        <h5>User Id: <?php echo $_SESSION['auth_user']['user_id']; ?></h5>
                        <h5>Username: <?php echo $_SESSION['auth_user']['username']; ?></h5>
                        <h5>Phone: <?php echo $_SESSION['auth_user']['phone']; ?></h5>
                        <h5>Email: <?php echo $_SESSION['auth_user']['email']; ?></h5>
                        
                        <!-- Show Create Clinic button only if user doesn't have a clinic -->
                        <?php if (mysqli_num_rows($result) == 0): ?>
                            <a href="create_clinic.php" class="btn btn-primary mt-3">Create Virtual Clinic</a>
                        <?php endif; ?>
                    </div>
                </div>            
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
