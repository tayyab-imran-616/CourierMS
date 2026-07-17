<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if (!isLoggedIn() || currentRole() != 'admin') {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$pageTitle = "Manage Agents";
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $city = trim($_POST['city']);
    $password = $_POST['password'];

    if ($name == '' || $email == '' || $city == '' || $password == '') {
        $error = "Please fill in all fields.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $check = mysqli_prepare($conn, "SELECT id FROM agents WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $error = "An agent with this email already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_prepare($conn, "INSERT INTO agents (name, email, password, city) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert, "ssss", $name, $email, $hashedPassword, $city);

            if (mysqli_stmt_execute($insert)) {
                $success = "Agent created successfully.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

$agents = mysqli_query($conn, "SELECT * FROM agents ORDER BY created_at DESC");
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
            <h2 class="h4 mb-4">Manage Agents</h2>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Agent removed.</div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card form-card p-4">
                        <h3 class="h5 mb-3"><i class="fa-solid fa-user-plus text-warning"></i> Create Agent</h3>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo BASE_URL; ?>/admin/agents.php">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Full Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">City / Branch</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-brand w-100">Create Agent</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card form-card p-4">
                        <h3 class="h5 mb-3">All Agents</h3>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>City</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($agents) == 0): ?>
                                    <tr><td colspan="4" class="text-center text-muted py-4">No agents yet.</td></tr>
                                    <?php endif; ?>
                                    <?php while ($a = mysqli_fetch_assoc($agents)): ?>
                                    <tr>
                                        <td class="fw-semibold"><?php echo htmlspecialchars($a['name']); ?></td>
                                        <td><?php echo htmlspecialchars($a['email']); ?></td>
                                        <td><?php echo htmlspecialchars($a['city']); ?></td>
                                        <td>
                                            <form method="POST" action="<?php echo BASE_URL; ?>/admin/agent_delete.php" onsubmit="return confirm('Remove this agent?');">
                                                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
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
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
