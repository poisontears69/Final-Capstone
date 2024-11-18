<?php
include('includes/authentication.php');
include('includes/dbcon.php'); // This will now use the $con variable
$page_title = 'Search Clinic Page';
include('includes/header.php');
include('includes/navbar.php'); 
// Handle search query
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

// Prepare the query to search clinics
$query = "SELECT * FROM clinics WHERE clinic_name LIKE ? OR description LIKE ?";
$stmt = mysqli_prepare($con, $query);

// Bind parameters
$search_param = "%" . $search_query . "%";
mysqli_stmt_bind_param($stmt, "ss", $search_param, $search_param);

// Execute the query
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$clinics = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Free the result
mysqli_free_result($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Clinics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .clinic-card {
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- Hero Section with Search Bar -->
<section class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4">Search for Clinics</h1>
        <form action="search_clinics.php" method="GET" class="d-flex justify-content-center mt-4">
            <input type="text" name="search" class="form-control w-50" placeholder="Search clinics by name or description" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-light ms-2">Search</button>
        </form>
    </div>
</section>

<!-- Clinics Cards Section -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4">Clinics</h2>
        <div class="row">
            <?php if (count($clinics) > 0): ?>
                <?php foreach ($clinics as $clinic): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card clinic-card" onclick="window.location.href='clinic_details.php?clinic_id=<?php echo $clinic['clinic_id']; ?>'">
                            <img src="https://via.placeholder.com/300" class="card-img-top" alt="Clinic Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($clinic['clinic_name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($clinic['description']); ?></p>
                                <ul class="list-unstyled">
                                    <li><strong>Phone:</strong> <?php echo htmlspecialchars($clinic['phone']); ?></li>
                                    <li><strong>Location:</strong> <?php echo htmlspecialchars($clinic['location']); ?></li>
                                    <li><strong>Business Hours:</strong> <?php echo htmlspecialchars($clinic['business_hours']); ?></li>
                                    <li><strong>Workdays:</strong> <?php echo htmlspecialchars($clinic['workdays']); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No clinics found matching your search criteria.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
