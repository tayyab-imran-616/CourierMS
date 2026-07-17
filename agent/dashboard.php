<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if (!isLoggedIn() || currentRole() != 'agent') {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$pageTitle = "Agent Dashboard";

$agentId = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM agents WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $agentId);
mysqli_stmt_execute($stmt);
$agent = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$city = $agent['city'];

$stmt2 = mysqli_prepare($conn, "SELECT COUNT(*) c FROM couriers WHERE from_location = ? OR to_location = ?");
mysqli_stmt_bind_param($stmt2, "ss", $city, $city);
mysqli_stmt_execute($stmt2);
$total = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2))['c'];

$stmt3 = mysqli_prepare($conn, "SELECT COUNT(*) c FROM couriers WHERE (from_location = ? OR to_location = ?) AND status = 'Booked'");
mysqli_stmt_bind_param($stmt3, "ss", $city, $city);
mysqli_stmt_execute($stmt3);
$booked = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt3))['c'];

$stmt4 = mysqli_prepare($conn, "SELECT COUNT(*) c FROM couriers WHERE (from_location = ? OR to_location = ?) AND status IN ('In Transit','Out for Delivery')");
mysqli_stmt_bind_param($stmt4, "ss", $city, $city);
mysqli_stmt_execute($stmt4);
$inTransit = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt4))['c'];

$stmt5 = mysqli_prepare($conn, "SELECT COUNT(*) c FROM couriers WHERE (from_location = ? OR to_location = ?) AND status = 'Delivered'");
mysqli_stmt_bind_param($stmt5, "ss", $city, $city);
mysqli_stmt_execute($stmt5);
$delivered = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt5))['c'];

$stmt6 = mysqli_prepare($conn, "SELECT * FROM couriers WHERE from_location = ? OR to_location = ? ORDER BY created_at DESC LIMIT 8");
mysqli_stmt_bind_param($stmt6, "ss", $city, $city);
mysqli_stmt_execute($stmt6);
$recent = mysqli_stmt_get_result($stmt6);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/../includes/links.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    <?php include __DIR__ . '/../includes/agent_nav.php'; ?>

    <main class="py-5">
        <div class="container">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h2>
                    <p class="text-muted mb-0">Branch: <span class="badge bg-secondary"><?php echo htmlspecialchars($city); ?></span></p>
                </div>
                <a href="<?php echo BASE_URL; ?>/agent/courier_add.php" class="btn btn-brand mt-3 mt-md-0">
                    <i class="fa-solid fa-plus"></i> New Courier
                </a>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card stat-card p-3 text-center">
                        <div class="h3 mb-0"><?php echo $total; ?></div>
                        <div class="text-muted small">Total</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card p-3 text-center">
                        <div class="h3 mb-0 text-secondary"><?php echo $booked; ?></div>
                        <div class="text-muted small">Booked</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card p-3 text-center">
                        <div class="h3 mb-0 text-warning"><?php echo $inTransit; ?></div>
                        <div class="text-muted small">In Transit</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card p-3 text-center">
                        <div class="h3 mb-0 text-success"><?php echo $delivered; ?></div>
                        <div class="text-muted small">Delivered</div>
                    </div>
                </div>
            </div>

            <div class="card form-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h5 mb-0">Recent Couriers - <?php echo htmlspecialchars($city); ?></h3>
                    <a href="<?php echo BASE_URL; ?>/agent/couriers.php" class="btn btn-sm btn-outline-dark">View All</a>
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
                            <?php if (mysqli_num_rows($recent) == 0): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">No couriers for your branch yet.</td></tr>
                            <?php endif; ?>
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
