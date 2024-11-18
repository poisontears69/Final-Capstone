<?php
include('includes/authentication.php');
$page_title = 'Dashboard Page';
include('includes/header.php');
include('includes/navbar.php'); 
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
                        <h5>Username: <?php echo $_SESSION['auth_user']['username']; ?></h5>
                        <h5>Phone: <?php echo $_SESSION['auth_user']['phone']; ?></h5>
                        <h5>Email: <?php echo $_SESSION['auth_user']['email']; ?></h5>
                    </div>
                </div>            
            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>