<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if (!isLoggedIn() || currentRole() != 'customer') {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$pageTitle = "My Dashboard";

$customerId = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM customers WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $customerId);
mysqli_stmt_execute($stmt);
$customer = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$phone = $customer['phone'];
$stmt2 = mysqli_prepare($conn, "SELECT * FROM couriers WHERE sender_phone = ? OR receiver_phone = ? ORDER BY created_at DESC");
mysqli_stmt_bind_param($stmt2, "ss", $phone, $phone);
mysqli_stmt_execute($stmt2);
$shipments = mysqli_stmt_get_result($stmt2);
$shipments = mysqli_fetch_all($shipments, MYSQLI_ASSOC);

$total = count($shipments);
$delivered = 0;
$inTransit = 0;
$booked = 0;
foreach ($shipments as $s) {
    if ($s['status'] == 'Delivered') $delivered++;
    if ($s['status'] == 'In Transit' || $s['status'] == 'Out for Delivery') $inTransit++;
    if ($s['status'] == 'Booked') $booked++;
}

function statusBadge($status) {
    if ($status == 'Delivered') return 'bg-success';
    if ($status == 'In Transit' || $status == 'Out for Delivery') return 'bg-warning text-dark';
    return 'bg-secondary';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/../includes/links.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <main class="py-5">
        <div class="container">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1">Welcome, <?php echo htmlspecialchars($customer['name']); ?></h2>
                    <p class="text-muted mb-0">Here's an overview of your consignments.</p>
                </div>
                <a href="<?php echo BASE_URL; ?>/track.php" class="btn btn-brand mt-3 mt-md-0">
                    <i class="fa-solid fa-magnifying-glass-location"></i> Track New Consignment
                </a>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card stat-card p-3 text-center">
                        <div class="h3 mb-0"><?php echo $total; ?></div>
                        <div class="text-muted small">Total Shipments</div>
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

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card form-card p-4">
                        <h3 class="h5 mb-3">Your Consignments</h3>

                        <?php if ($total == 0): ?>
                            <div class="text-center py-5">
                                <i class="fa-solid fa-box-open fs-1 text-muted mb-3 d-block"></i>
                                <p class="text-muted mb-3">No consignments linked to your account yet.</p>
                                <a href="<?php echo BASE_URL; ?>/track.php" class="btn btn-brand">Track a Consignment</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Consignment No.</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Status</th>
                                            <th>Booked On</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($shipments as $s): ?>
                                        <tr>
                                            <td class="fw-semibold"><?php echo htmlspecialchars($s['consignment_no']); ?></td>
                                            <td><?php echo htmlspecialchars($s['from_location']); ?></td>
                                            <td><?php echo htmlspecialchars($s['to_location']); ?></td>
                                            <td><span class="badge <?php echo statusBadge($s['status']); ?>"><?php echo htmlspecialchars($s['status']); ?></span></td>
                                            <td><?php echo htmlspecialchars($s['created_at']); ?></td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>/track.php?consignment_no=<?php echo urlencode($s['consignment_no']); ?>" class="btn btn-sm btn-outline-dark">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card form-card p-4">
                        <h3 class="h5 mb-3">Your Profile</h3>
                        <p class="mb-2"><i class="fa-solid fa-user text-warning me-2"></i><?php echo htmlspecialchars($customer['name']); ?></p>
                        <p class="mb-2"><i class="fa-solid fa-envelope text-warning me-2"></i><?php echo htmlspecialchars($customer['email']); ?></p>
                        <p class="mb-2"><i class="fa-solid fa-phone text-warning me-2"></i><?php echo htmlspecialchars($customer['phone']); ?></p>
                        <p class="mb-0"><i class="fa-solid fa-location-dot text-warning me-2"></i><?php echo htmlspecialchars($customer['address']); ?></p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
