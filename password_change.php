<?php
session_start();
$page_title = 'Password Change';
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
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
                        <h5>
                            Change Password
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="password_reset_code.php" method="POST">
                            <!-- Hidden Token -->
                            <input type="hidden" name="password_token" value="<?php if (isset($_GET['token'])) { echo $_GET['token']; } ?>">

                            <!-- Email Address -->
                            <div class="form-group mb-3">
                                <label>Email Address</label>
                                <input type="email" name="email" value="<?php if (isset($_GET['email'])) { echo $_GET['email']; } ?>" class="form-control" placeholder="Email Address" required>
                            </div>

                            <!-- New Password -->
                            <div class="form-group mb-3">
                                <label>New Password</label>
                                <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group mb-3">
                                <label>Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm New Password" required>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group mb-3">
                                <button type="submit" name="password_update" class="btn btn-success w-100">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
