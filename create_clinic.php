<?php
include('includes/dbcon.php');
include('includes/authentication.php');
$page_title = 'Create Clinic';
include('includes/header.php');
include('includes/navbar.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input data
    $clinic_name = mysqli_real_escape_string($con, $_POST['clinic_name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $allow_online_consultation = isset($_POST['allow_online_consultation']) ? 1 : 0;
    
    // Collect workdays
    $workdays = isset($_POST['workdays']) ? implode(",", $_POST['workdays']) : ''; // Store workdays as a comma-separated string
    
    // Collect business hours
    $business_hours_start = mysqli_real_escape_string($con, $_POST['business_hours_start']);
    $business_hours_end = mysqli_real_escape_string($con, $_POST['business_hours_end']);
    $business_hours = $business_hours_start . ' - ' . $business_hours_end;

    // Generate a unique clinic code (e.g., random string)
    $clinic_code = strtoupper(bin2hex(random_bytes(3))); // Generates a random 6-character code

    // Get the user_id from session
    $user_id = $_SESSION['auth_user']['user_id']; // Assuming user_id is stored in session

    // Check if clinic already exists (optional)
    $check_clinic_query = "SELECT * FROM clinics WHERE creator_user_id = '$user_id' LIMIT 1";
    $check_clinic_result = mysqli_query($con, $check_clinic_query);
    if (mysqli_num_rows($check_clinic_result) > 0) {
        $_SESSION['error'] = "You have already created a clinic.";
        header('Location: dashboard.php');
        exit;
    }

    // Insert clinic details into the clinics table
    $query = "INSERT INTO clinics (creator_user_id, clinic_name, phone, location, business_hours, workdays, allow_online_consultation, clinic_code) 
              VALUES ('$user_id', '$clinic_name', '$phone', '$location', '$business_hours', '$workdays', '$allow_online_consultation', '$clinic_code')";
    
    if (mysqli_query($con, $query)) {
        $_SESSION['success'] = "Your clinic has been created successfully.";
        $_SESSION['clinic_code'] = $clinic_code; // Store clinic code in session to display it
        header('Location: dashboard.php');
    } else {
        $_SESSION['error'] = "Failed to create clinic. Please try again.";
        header('Location: create_clinic.php');
    }
    exit;
}
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Create Your Virtual Clinic</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="">
                            <div class="form-group mb-3">
                                <label for="clinic_name">Clinic Name</label>
                                <input type="text" name="clinic_name" id="clinic_name" class="form-control" placeholder="Enter Clinic Name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone">Phone Number</label>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter Phone Number" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="location">Location</label>
                                <input type="text" name="location" id="location" class="form-control" placeholder="Enter Clinic Location" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="business_hours_start">Business Hours Start</label>
                                <select name="business_hours_start" id="business_hours_start" class="form-control" required>
                                    <option value="08:00">08:00 AM</option>
                                    <option value="09:00">09:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="business_hours_end">Business Hours End</label>
                                <select name="business_hours_end" id="business_hours_end" class="form-control" required>
                                    <option value="17:00">05:00 PM</option>
                                    <option value="18:00">06:00 PM</option>
                                    <option value="19:00">07:00 PM</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label>Work Days</label><br>
                                <input type="checkbox" name="workdays[]" value="Monday"> Monday<br>
                                <input type="checkbox" name="workdays[]" value="Tuesday"> Tuesday<br>
                                <input type="checkbox" name="workdays[]" value="Wednesday"> Wednesday<br>
                                <input type="checkbox" name="workdays[]" value="Thursday"> Thursday<br>
                                <input type="checkbox" name="workdays[]" value="Friday"> Friday<br>
                                <input type="checkbox" name="workdays[]" value="Saturday"> Saturday<br>
                                <input type="checkbox" name="workdays[]" value="Sunday"> Sunday<br>
                            </div>
                            <div class="form-group mb-3">
                                <label for="allow_online_consultation">Allow Online Consultation</label>
                                <input type="checkbox" name="allow_online_consultation" id="allow_online_consultation">
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-success w-100">Create Clinic</button>
                            </div>
                        </form>
                        <?php if (isset($_SESSION['clinic_code'])): ?>
                            <div class="alert alert-info mt-3">
                                <strong>Clinic Code:</strong> <?php echo $_SESSION['clinic_code']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
