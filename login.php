<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
$pageTitle = "Login";
$error = "";

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $tables = ['admins' => 'admin', 'agents' => 'agent', 'customers' => 'customer'];
    $loggedIn = false;

    foreach ($tables as $table => $role) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM $table WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res);

        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $role;
            $_SESSION['name'] = $row['name'];
            $loggedIn = true;

            if ($role == 'admin') header('Location: ' . BASE_URL . '/admin/dashboard.php');
            if ($role == 'agent') header('Location: ' . BASE_URL . '/agent/dashboard.php');
            if ($role == 'customer') header('Location: ' . BASE_URL . '/user/dashboard.php');
            exit;
        }
    }

    if (!$loggedIn) {
        $error = "Invalid email or password.";
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
                <div class="col-12 col-md-8 col-lg-5">
                    <div class="card form-card p-4 p-md-5">
                        <h2 class="h4 mb-1"><i class="fa-solid fa-right-to-bracket text-warning"></i> Login</h2>
                        <p class="text-muted small mb-4">Admin, Agent and Customer accounts all sign in here.</p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo BASE_URL; ?>/login.php">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control form-control-lg" required>
                            </div>
                            <button type="submit" class="btn btn-brand w-100 btn-lg">Login</button>
                        </form>

                        <p class="text-center text-muted small mt-4 mb-0">
                            Don't have an account? <a href="<?php echo BASE_URL; ?>/register.php">Register here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
