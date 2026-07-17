<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if (!isLoggedIn() || currentRole() != 'admin') {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$pageTitle = "Manage Couriers";

$q = trim($_GET['q'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');

$sql = "SELECT * FROM couriers WHERE 1=1";
$params = [];
$types = "";

if ($q != '') {
    $sql .= " AND (consignment_no LIKE ? OR sender_name LIKE ? OR receiver_name LIKE ?)";
    $like = "%$q%";
    $params[] = $like; $params[] = $like; $params[] = $like;
    $types .= "sss";
}
if ($statusFilter != '') {
    $sql .= " AND status = ?";
    $params[] = $statusFilter;
    $types .= "s";
}
$sql .= " ORDER BY created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
if ($types != '') {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$couriers = mysqli_stmt_get_result($stmt);
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
                <h2 class="h4 mb-0">Manage Couriers</h2>
                <a href="<?php echo BASE_URL; ?>/admin/courier_add.php" class="btn btn-brand">
                    <i class="fa-solid fa-plus"></i> New Courier
                </a>
            </div>

            <?php if (isset($_GET['added'])): ?>
                <div class="alert alert-success">Courier booked successfully.</div>
            <?php endif; ?>
            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success">Courier updated successfully.</div>
            <?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Courier deleted.</div>
            <?php endif; ?>
            <?php if (isset($_GET['smssent'])): ?>
                <div class="alert alert-success">SMS sent successfully.</div>
            <?php endif; ?>

            <div class="card form-card p-3 mb-4">
                <form method="GET" class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">Search</label>
                        <input type="text" name="q" class="form-control" placeholder="Consignment no, sender or receiver" value="<?php echo htmlspecialchars($q); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="Booked" <?php if ($statusFilter == 'Booked') echo 'selected'; ?>>Booked</option>
                            <option value="In Transit" <?php if ($statusFilter == 'In Transit') echo 'selected'; ?>>In Transit</option>
                            <option value="Out for Delivery" <?php if ($statusFilter == 'Out for Delivery') echo 'selected'; ?>>Out for Delivery</option>
                            <option value="Delivered" <?php if ($statusFilter == 'Delivered') echo 'selected'; ?>>Delivered</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-dark w-100">Filter</button>
                    </div>
                </form>
            </div>

            <div class="card form-card p-4">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Consignment No.</th>
                                <th>Sender</th>
                                <th>Receiver</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Status</th>
                                <th>Booked On</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($couriers) == 0): ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">No couriers found.</td></tr>
                            <?php endif; ?>
                            <?php while ($c = mysqli_fetch_assoc($couriers)): ?>
                            <tr>
                                <td class="fw-semibold"><?php echo htmlspecialchars($c['consignment_no']); ?></td>
                                <td><?php echo htmlspecialchars($c['sender_name']); ?></td>
                                <td><?php echo htmlspecialchars($c['receiver_name']); ?></td>
                                <td><?php echo htmlspecialchars($c['from_location']); ?></td>
                                <td><?php echo htmlspecialchars($c['to_location']); ?></td>
                                <td><span class="badge <?php echo statusBadge($c['status']); ?>"><?php echo htmlspecialchars($c['status']); ?></span></td>
                                <td><?php echo htmlspecialchars($c['created_at']); ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">Actions</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/courier_edit.php?id=<?php echo $c['id']; ?>"><i class="fa-solid fa-pen me-2"></i>Edit</a></li>
                                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/track.php?consignment_no=<?php echo urlencode($c['consignment_no']); ?>"><i class="fa-solid fa-eye me-2"></i>View / Print</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="<?php echo BASE_URL; ?>/admin/sms_send.php">
                                                    <input type="hidden" name="courier_id" value="<?php echo $c['id']; ?>">
                                                    <input type="hidden" name="type" value="pickup">
                                                    <button type="submit" class="dropdown-item"><i class="fa-solid fa-comment-sms me-2"></i>Send Pickup SMS</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="<?php echo BASE_URL; ?>/admin/sms_send.php">
                                                    <input type="hidden" name="courier_id" value="<?php echo $c['id']; ?>">
                                                    <input type="hidden" name="type" value="delivery">
                                                    <button type="submit" class="dropdown-item"><i class="fa-solid fa-comment-sms me-2"></i>Send Delivery SMS</button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="<?php echo BASE_URL; ?>/admin/courier_delete.php" onsubmit="return confirm('Delete this courier?');">
                                                    <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                                    <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-trash me-2"></i>Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
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
