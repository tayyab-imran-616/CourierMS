<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if (!isLoggedIn() || currentRole() != 'admin') {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$id = $_GET['id'] ?? $_POST['id'] ?? 0;

$stmt = mysqli_prepare($conn, "SELECT * FROM couriers WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$courier = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$courier) {
    header('Location: ' . BASE_URL . '/admin/couriers.php');
    exit;
}

$pageTitle = "Edit Courier";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senderName = trim($_POST['sender_name']);
    $senderPhone = trim($_POST['sender_phone']);
    $receiverName = trim($_POST['receiver_name']);
    $receiverPhone = trim($_POST['receiver_phone']);
    $fromLocation = trim($_POST['from_location']);
    $toLocation = trim($_POST['to_location']);
    $courierType = trim($_POST['courier_type']);
    $deliveryDate = $_POST['delivery_date'];
    $status = $_POST['status'];

    if ($senderName == '' || $receiverName == '' || $fromLocation == '' || $toLocation == '') {
        $error = "Please fill in all required fields.";
    } else {
        $update = mysqli_prepare($conn, "UPDATE couriers SET sender_name=?, sender_phone=?, receiver_name=?, receiver_phone=?, from_location=?, to_location=?, courier_type=?, delivery_date=?, status=? WHERE id=?");
        mysqli_stmt_bind_param($update, "sssssssssi", $senderName, $senderPhone, $receiverName, $receiverPhone, $fromLocation, $toLocation, $courierType, $deliveryDate, $status, $id);

        if (mysqli_stmt_execute($update)) {
            header('Location: ' . BASE_URL . '/admin/couriers.php?updated=1');
            exit;
        } else {
            $error = "Something went wrong. Please try again.";
        }
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
    <?php include __DIR__ . '/../includes/admin_nav.php'; ?>

    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card form-card p-4 p-md-5">
                        <h2 class="h4 mb-1"><i class="fa-solid fa-pen text-warning"></i> Edit Courier</h2>
                        <p class="text-muted small mb-4">Consignment No: <strong><?php echo htmlspecialchars($courier['consignment_no']); ?></strong></p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo BASE_URL; ?>/admin/courier_edit.php?id=<?php echo $courier['id']; ?>">
                            <input type="hidden" name="id" value="<?php echo $courier['id']; ?>">

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select form-select-lg">
                                    <?php foreach (['Booked', 'In Transit', 'Out for Delivery', 'Delivered'] as $s): ?>
                                        <option value="<?php echo $s; ?>" <?php if ($courier['status'] == $s) echo 'selected'; ?>><?php echo $s; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <h3 class="h6 text-muted text-uppercase small mb-3">Sender Details</h3>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Sender Name</label>
                                    <input type="text" name="sender_name" class="form-control" value="<?php echo htmlspecialchars($courier['sender_name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Sender Phone</label>
                                    <input type="text" name="sender_phone" class="form-control" value="<?php echo htmlspecialchars($courier['sender_phone']); ?>">
                                </div>
                            </div>

                            <h3 class="h6 text-muted text-uppercase small mb-3">Receiver Details</h3>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Receiver Name</label>
                                    <input type="text" name="receiver_name" class="form-control" value="<?php echo htmlspecialchars($courier['receiver_name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Receiver Phone</label>
                                    <input type="text" name="receiver_phone" class="form-control" value="<?php echo htmlspecialchars($courier['receiver_phone']); ?>">
                                </div>
                            </div>

                            <h3 class="h6 text-muted text-uppercase small mb-3">Shipment Details</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">From Location</label>
                                    <input type="text" name="from_location" class="form-control" value="<?php echo htmlspecialchars($courier['from_location']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">To Location</label>
                                    <input type="text" name="to_location" class="form-control" value="<?php echo htmlspecialchars($courier['to_location']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Courier Type</label>
                                    <select name="courier_type" class="form-select">
                                        <?php foreach (['Standard', 'Express', 'Overnight'] as $t): ?>
                                            <option value="<?php echo $t; ?>" <?php if ($courier['courier_type'] == $t) echo 'selected'; ?>><?php echo $t; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Expected Delivery Date</label>
                                    <input type="date" name="delivery_date" class="form-control" value="<?php echo htmlspecialchars($courier['delivery_date']); ?>">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-brand w-100 btn-lg mt-4">Save Changes</button>
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
