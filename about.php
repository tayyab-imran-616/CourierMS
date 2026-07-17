<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = "About Us";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/includes/links.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <main class="py-5 bg-white">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width:640px;">
                <span class="text-warning fw-bold text-uppercase small">About Us</span>
                <h2 class="mt-2 mb-3">Simplifying courier management</h2>
                <p class="text-muted">
                    CourierMS replaces manual, paper-based courier tracking with a
                    fast, web-based system for managing bookings, agents and customers.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card p-4 text-center">
                        <div class="feature-icon mb-3 mx-auto"><i class="fa-solid fa-bullseye"></i></div>
                        <h3 class="h6">Our Mission</h3>
                        <p class="text-muted small mb-0">Make courier operations simple and transparent.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card p-4 text-center">
                        <div class="feature-icon mb-3 mx-auto"><i class="fa-solid fa-eye"></i></div>
                        <h3 class="h6">Our Vision</h3>
                        <p class="text-muted small mb-0">A digital, real-time logistics network for everyone.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card p-4 text-center">
                        <div class="feature-icon mb-3 mx-auto"><i class="fa-solid fa-handshake"></i></div>
                        <h3 class="h6">Our Promise</h3>
                        <p class="text-muted small mb-0">Reliable delivery and honest tracking.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
