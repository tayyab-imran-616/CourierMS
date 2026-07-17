<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
$pageTitle = "Track Consignment";

$consignmentNo = trim($_GET['consignment_no'] ?? '');
$result = null;
$error = null;

if ($consignmentNo != '') {
    $stmt = mysqli_prepare($conn, "SELECT * FROM couriers WHERE consignment_no = ?");
    mysqli_stmt_bind_param($stmt, "s", $consignmentNo);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res && mysqli_num_rows($res) > 0) {
        $result = mysqli_fetch_assoc($res);
    } else {
        $error = "No consignment found with number " . htmlspecialchars($consignmentNo);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/includes/links.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card form-card p-4 p-md-5">
                        <h2 class="h4 mb-1"><i class="fa-solid fa-magnifying-glass-location text-warning"></i> Track Consignment</h2>
                        <p class="text-muted small mb-4">Enter your consignment number to view live status.</p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="GET" action="<?php echo BASE_URL; ?>/track.php" class="mb-2">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Consignment Number</label>
                                <input type="text" name="consignment_no" class="form-control form-control-lg"
                                       value="<?php echo htmlspecialchars($consignmentNo); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-brand w-100">Track</button>
                        </form>

                        <?php if ($result): ?>
                            <hr class="my-4">
                            <h3 class="h5 mb-3">Status: <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($result['status']); ?></span></h3>
                            <p class="mb-1"><strong>From:</strong> <?php echo htmlspecialchars($result['from_location']); ?></p>
                            <p class="mb-1"><strong>To:</strong> <?php echo htmlspecialchars($result['to_location']); ?></p>
                            <p class="mb-3"><strong>Booked On:</strong> <?php echo htmlspecialchars($result['created_at']); ?></p>
                            <button onclick="window.print()" class="btn btn-outline-dark">
                                <i class="fa-solid fa-print"></i> Print Status
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
