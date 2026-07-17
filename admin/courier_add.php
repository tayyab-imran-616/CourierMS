<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if (!isLoggedIn() || currentRole() != 'admin') {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$pageTitle = "New Courier";
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

    if ($senderName == '' || $receiverName == '' || $fromLocation == '' || $toLocation == '') {
        $error = "Please fill in all required fields.";
    } else {
        do {
            $consignmentNo = "CMS-" . date('Y') . "-" . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $check = mysqli_prepare($conn, "SELECT id FROM couriers WHERE consignment_no = ?");
            mysqli_stmt_bind_param($check, "s", $consignmentNo);
            mysqli_stmt_execute($check);
            mysqli_stmt_store_result($check);
        } while (mysqli_stmt_num_rows($check) > 0);

        $bookedBy = $_SESSION['user_id'];
        $bookedByRole = 'admin';

        $insert = mysqli_prepare($conn, "INSERT INTO couriers (consignment_no, sender_name, sender_phone, receiver_name, receiver_phone, from_location, to_location, courier_type, delivery_date, booked_by, booked_by_role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert, "sssssssssis", $consignmentNo, $senderName, $senderPhone, $receiverName, $receiverPhone, $fromLocation, $toLocation, $courierType, $deliveryDate, $bookedBy, $bookedByRole);

        if (mysqli_stmt_execute($insert)) {
            header('Location: ' . BASE_URL . '/admin/couriers.php?added=1');
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
                        <h2 class="h4 mb-1"><i class="fa-solid fa-box text-warning"></i> New Courier</h2>
                        <p class="text-muted small mb-4">Book a new consignment.</p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo BASE_URL; ?>/admin/courier_add.php">
                            <h3 class="h6 text-muted text-uppercase small mb-3">Sender Details</h3>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Sender Name</label>
                                    <input type="text" name="sender_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Sender Phone</label>
                                    <input type="text" name="sender_phone" class="form-control">
                                </div>
                            </div>

                            <h3 class="h6 text-muted text-uppercase small mb-3">Receiver Details</h3>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Receiver Name</label>
                                    <input type="text" name="receiver_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Receiver Phone</label>
                                    <input type="text" name="receiver_phone" class="form-control">
                                </div>
                            </div>

                            <h3 class="h6 text-muted text-uppercase small mb-3">Shipment Details</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">From Location</label>
                                    <input type="text" name="from_location" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">To Location</label>
                                    <input type="text" name="to_location" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Courier Type</label>
                                    <select name="courier_type" class="form-select">
                                        <option value="Standard">Standard</option>
                                        <option value="Express">Express</option>
                                        <option value="Overnight">Overnight</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Expected Delivery Date</label>
                                    <input type="date" name="delivery_date" class="form-control">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-brand w-100 btn-lg mt-4">Book Courier</button>
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
