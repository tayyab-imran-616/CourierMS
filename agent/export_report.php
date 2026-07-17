<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if (!isLoggedIn() || currentRole() != 'agent') {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$agentId = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM agents WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $agentId);
mysqli_stmt_execute($stmt);
$agent = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$city = $agent['city'];

$dateFrom = trim($_GET['date_from'] ?? '');
$dateTo = trim($_GET['date_to'] ?? '');

if (isset($_GET['export'])) {
    $sql = "SELECT * FROM couriers WHERE (from_location = ? OR to_location = ?)";
    $params = [$city, $city];
    $types = "ss";

    if ($dateFrom != '') { $sql .= " AND DATE(created_at) >= ?"; $params[] = $dateFrom; $types .= "s"; }
    if ($dateTo != '') { $sql .= " AND DATE(created_at) <= ?"; $params[] = $dateTo; $types .= "s"; }
    $sql .= " ORDER BY created_at DESC";

    $stmt2 = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt2, $types, ...$params);
    mysqli_stmt_execute($stmt2);
    $result = mysqli_stmt_get_result($stmt2);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="branch_report.csv"');

    $out = fopen('php://output', 'w');
    fputcsv($out, ['Consignment No', 'Sender', 'Receiver', 'From', 'To', 'Type', 'Status', 'Booked On', 'Delivery Date']);

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($out, [
            $row['consignment_no'], $row['sender_name'], $row['receiver_name'],
            $row['from_location'], $row['to_location'], $row['courier_type'],
            $row['status'], $row['created_at'], $row['delivery_date']
        ]);
    }
    fclose($out);
    exit;
}

$pageTitle = "Export Report";
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
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card form-card p-4 p-md-5">
                        <h2 class="h4 mb-1"><i class="fa-solid fa-download text-warning"></i> Export Branch Report</h2>
                        <p class="text-muted small mb-4">Download a CSV report for <?php echo htmlspecialchars($city); ?> (opens in Excel).</p>

                        <form method="GET" action="<?php echo BASE_URL; ?>/agent/export_report.php">
                            <input type="hidden" name="export" value="1">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">From Date</label>
                                    <input type="date" name="date_from" class="form-control" value="<?php echo htmlspecialchars($dateFrom); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">To Date</label>
                                    <input type="date" name="date_to" class="form-control" value="<?php echo htmlspecialchars($dateTo); ?>">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-brand w-100 btn-lg">Download CSV</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
