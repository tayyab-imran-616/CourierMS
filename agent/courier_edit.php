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

$id = $_GET['id'] ?? $_POST['id'] ?? 0;

$stmt2 = mysqli_prepare($conn, "SELECT * FROM couriers WHERE id = ? AND (from_location = ? OR to_location = ?)");
mysqli_stmt_bind_param($stmt2, "iss", $id, $city, $city);
mysqli_stmt_execute($stmt2);
$courier = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2));

if (!$courier) {
    header('Location: ' . BASE_URL . '/agent/couriers.php');
    exit;
}

$pageTitle = "Update Courier";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $deliveryDate = $_POST['delivery_date'];

    $update = mysqli_prepare($conn, "UPDATE couriers SET status = ?, delivery_date = ? WHERE id = ?");
    mysqli_stmt_bind_param($update, "ssi", $status, $deliveryDate, $id);

    if (mysqli_stmt_execute($update)) {
        header('Location: ' . BASE_URL . '/agent/couriers.php?updated=1');
        exit;
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
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
                <div class="col-lg-6">
                    <div class="card form-card p-4 p-md-5">
                        <h2 class="h4 mb-1"><i class="fa-solid fa-pen text-warning"></i> Update Courier</h2>
                        <p class="text-muted small mb-4">Consignment No: <strong><?php echo htmlspecialchars($courier['consignment_no']); ?></strong></p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <p class="mb-1"><strong>Sender:</strong> <?php echo htmlspecialchars($courier['sender_name']); ?></p>
                            <p class="mb-1"><strong>Receiver:</strong> <?php echo htmlspecialchars($courier['receiver_name']); ?></p>
                            <p class="mb-0"><strong>Route:</strong> <?php echo htmlspecialchars($courier['from_location']); ?> &rarr; <?php echo htmlspecialchars($courier['to_location']); ?></p>
                        </div>

                        <form method="POST" action="<?php echo BASE_URL; ?>/agent/courier_edit.php?id=<?php echo $courier['id']; ?>">
                            <input type="hidden" name="id" value="<?php echo $courier['id']; ?>">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select form-select-lg">
                                    <?php foreach (['Booked', 'In Transit', 'Out for Delivery', 'Delivered'] as $s): ?>
                                        <option value="<?php echo $s; ?>" <?php if ($courier['status'] == $s) echo 'selected'; ?>><?php echo $s; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Expected Delivery Date</label>
                                <input type="date" name="delivery_date" class="form-control" value="<?php echo htmlspecialchars($courier['delivery_date']); ?>">
                            </div>

                            <button type="submit" class="btn btn-brand w-100 btn-lg">Save Changes</button>
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
