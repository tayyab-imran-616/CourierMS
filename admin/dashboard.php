<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if (!isLoggedIn() || currentRole() != 'admin') {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$pageTitle = "Admin Dashboard";

$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM couriers"))['c'];
$booked = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM couriers WHERE status='Booked'"))['c'];
$inTransit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM couriers WHERE status IN ('In Transit','Out for Delivery')"))['c'];
$delivered = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM couriers WHERE status='Delivered'"))['c'];
$totalAgents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM agents"))['c'];
$totalCustomers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM customers"))['c'];

$recent = mysqli_query($conn, "SELECT * FROM couriers ORDER BY created_at DESC LIMIT 8");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/../includes/links.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    <?php include __DIR__ . '/../includes/admin_nav.php'; ?>

    <main class="py-5">
        <div class="container">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h2>
                    <p class="text-muted mb-0">Here's what's happening across the system.</p>
                </div>
                <a href="<?php echo BASE_URL; ?>/admin/courier_add.php" class="btn btn-brand mt-3 mt-md-0">
                    <i class="fa-solid fa-plus"></i> New Courier
                </a>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-2">
                    <div class="card stat-card p-3 text-center">
                        <div class="h3 mb-0"><?php echo $total; ?></div>
                        <div class="text-muted small">Total</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="card stat-card p-3 text-center">
                        <div class="h3 mb-0 text-secondary"><?php echo $booked; ?></div>
                        <div class="text-muted small">Booked</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="card stat-card p-3 text-center">
                        <div class="h3 mb-0 text-warning"><?php echo $inTransit; ?></div>
                        <div class="text-muted small">In Transit</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="card stat-card p-3 text-center">
                        <div class="h3 mb-0 text-success"><?php echo $delivered; ?></div>
                        <div class="text-muted small">Delivered</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="card stat-card p-3 text-center">
                        <div class="h3 mb-0"><?php echo $totalAgents; ?></div>
                        <div class="text-muted small">Agents</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="card stat-card p-3 text-center">
                        <div class="h3 mb-0"><?php echo $totalCustomers; ?></div>
                        <div class="text-muted small">Customers</div>
                    </div>
                </div>
            </div>

            <div class="card form-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h5 mb-0">Recent Couriers</h3>
                    <a href="<?php echo BASE_URL; ?>/admin/couriers.php" class="btn btn-sm btn-outline-dark">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Consignment No.</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Status</th>
                                <th>Booked On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($c = mysqli_fetch_assoc($recent)): ?>
                            <tr>
                                <td class="fw-semibold"><?php echo htmlspecialchars($c['consignment_no']); ?></td>
                                <td><?php echo htmlspecialchars($c['from_location']); ?></td>
                                <td><?php echo htmlspecialchars($c['to_location']); ?></td>
                                <td><span class="badge <?php echo statusBadge($c['status']); ?>"><?php echo htmlspecialchars($c['status']); ?></span></td>
                                <td><?php echo htmlspecialchars($c['created_at']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
