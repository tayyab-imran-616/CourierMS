<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if (!isLoggedIn() || currentRole() != 'admin') {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$pageTitle = "Manage Customers";

$q = trim($_GET['q'] ?? '');

if ($q != '') {
    $like = "%$q%";
    $stmt = mysqli_prepare($conn, "SELECT * FROM customers WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? ORDER BY created_at DESC");
    mysqli_stmt_bind_param($stmt, "sss", $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $customers = mysqli_stmt_get_result($stmt);
} else {
    $customers = mysqli_query($conn, "SELECT * FROM customers ORDER BY created_at DESC");
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
            <h2 class="h4 mb-4">Manage Customers</h2>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Customer removed.</div>
            <?php endif; ?>

            <div class="card form-card p-3 mb-4">
                <form method="GET" class="row g-2 align-items-end">
                    <div class="col-md-9">
                        <label class="form-label small fw-semibold">Search</label>
                        <input type="text" name="q" class="form-control" placeholder="Name, email or phone" value="<?php echo htmlspecialchars($q); ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-dark w-100">Search</button>
                    </div>
                </form>
            </div>

            <div class="card form-card p-4">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Registered</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($customers) == 0): ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">No customers found.</td></tr>
                            <?php endif; ?>
                            <?php while ($c = mysqli_fetch_assoc($customers)): ?>
                            <tr>
                                <td class="fw-semibold"><?php echo htmlspecialchars($c['name']); ?></td>
                                <td><?php echo htmlspecialchars($c['email']); ?></td>
                                <td><?php echo htmlspecialchars($c['phone']); ?></td>
                                <td><?php echo htmlspecialchars($c['address']); ?></td>
                                <td><?php echo htmlspecialchars($c['created_at']); ?></td>
                                <td>
                                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/customer_delete.php" onsubmit="return confirm('Remove this customer?');">
                                        <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
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
