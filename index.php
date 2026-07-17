<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = "Home";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/includes/links.php'; ?>
</head>
<body>

    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <main>
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-7">
                        <h1 class="display-5 mb-3">Ship it. Track it. <span class="highlight">Deliver it.</span></h1>
                        <p class="lead mb-4">
                            A simple courier management system — book consignments,
                            manage agents, and let customers track their packages online.
                        </p>
                        <div class="d-flex flex-wrap gap-3 mb-5">
                            <?php if (!isLoggedIn()): ?>
                                <a href="<?php echo BASE_URL; ?>/register.php" class="btn btn-brand btn-lg">Get Started</a>
                                <a href="<?php echo BASE_URL; ?>/about.php" class="btn btn-outline-light btn-lg">Learn More</a>
                            <?php else: ?>
                                <a href="<?php echo BASE_URL; ?>/track.php" class="btn btn-brand btn-lg">Track a Consignment</a>
                                <a href="<?php echo BASE_URL; ?>/about.php" class="btn btn-outline-light btn-lg">Learn More</a>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex flex-wrap gap-4">
                            <div class="stat-block"><strong>25K+</strong><span>Consignments Delivered</span></div>
                            <div class="stat-block"><strong>120+</strong><span>Branches Nationwide</span></div>
                            <div class="stat-block"><strong>99.2%</strong><span>On-time Delivery</span></div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card track-card p-4">
                            <div class="card-body">
                                <h3 class="h5 mb-1"><i class="fa-solid fa-magnifying-glass-location text-warning"></i> Track Your Consignment</h3>
                                <p class="text-muted small mb-3">Enter your tracking number to see live status.</p>

                                <form class="d-flex gap-2" action="<?php echo BASE_URL; ?>/track.php" method="GET">
                                    <input type="text" name="consignment_no" class="form-control form-control-lg" placeholder="e.g. CMS-2026-00452" required>
                                    <button type="submit" class="btn btn-brand btn-lg">Track</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5" style="max-width:620px;">
                    <span class="text-warning fw-bold text-uppercase small">Why CourierMS</span>
                    <h2 class="mt-2 mb-3">Everything you need to run courier operations</h2>
                    <p class="text-muted">Built for admins, agents, and customers.</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="card feature-card p-4">
                            <div class="feature-icon mb-3"><i class="fa-solid fa-box-open"></i></div>
                            <h3 class="h6">Consignment Booking</h3>
                            <p class="text-muted small mb-0">Create courier bills with sender, receiver and package details.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card feature-card p-4">
                            <div class="feature-icon mb-3"><i class="fa-solid fa-location-crosshairs"></i></div>
                            <h3 class="h6">Real-time Tracking</h3>
                            <p class="text-muted small mb-0">Track any consignment by number and view live status.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card feature-card p-4">
                            <div class="feature-icon mb-3"><i class="fa-solid fa-comment-sms"></i></div>
                            <h3 class="h6">SMS Notifications</h3>
                            <p class="text-muted small mb-0">SMS alerts on dispatch and delivery.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card feature-card p-4">
                            <div class="feature-icon mb-3"><i class="fa-solid fa-users-gear"></i></div>
                            <h3 class="h6">Agent Management</h3>
                            <p class="text-muted small mb-0">Admins create and manage agent logins per city.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card feature-card p-4">
                            <div class="feature-icon mb-3"><i class="fa-solid fa-chart-column"></i></div>
                            <h3 class="h6">Reports</h3>
                            <p class="text-muted small mb-0">Download shipment reports date-wise or city-wise.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card feature-card p-4">
                            <div class="feature-icon mb-3"><i class="fa-solid fa-address-book"></i></div>
                            <h3 class="h6">Customer Database</h3>
                            <p class="text-muted small mb-0">A secure, searchable customer database.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-white">
            <div class="container">
                <div class="text-center mx-auto mb-5" style="max-width:620px;">
                    <span class="text-warning fw-bold text-uppercase small">Process</span>
                    <h2 class="mt-2">How it works</h2>
                </div>
                <div class="row g-4 text-center">
                    <div class="col-md-3">
                        <div class="step-num">1</div>
                        <h4 class="h6">Book</h4>
                        <p class="text-muted small">Agent creates a courier bill.</p>
                    </div>
                    <div class="col-md-3">
                        <div class="step-num">2</div>
                        <h4 class="h6">Notify</h4>
                        <p class="text-muted small">SMS confirms pickup and tracking number.</p>
                    </div>
                    <div class="col-md-3">
                        <div class="step-num">3</div>
                        <h4 class="h6">Transit</h4>
                        <p class="text-muted small">Status updates as package moves.</p>
                    </div>
                    <div class="col-md-3">
                        <div class="step-num">4</div>
                        <h4 class="h6">Deliver</h4>
                        <p class="text-muted small">Delivery SMS and printable status.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 role-section text-white">
            <div class="container">
                <div class="text-center mx-auto mb-5" style="max-width:620px;">
                    <span class="text-warning fw-bold text-uppercase small">Access</span>
                    <h2 class="mt-2 mb-3">Built for every role</h2>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card role-card p-4 text-white">
                            <i class="fa-solid fa-user-shield fs-3 mb-3"></i>
                            <h3 class="h5">Admin</h3>
                            <p class="text-secondary small">Manage couriers, agents, customers and reports.</p>
                            <a href="<?php echo BASE_URL; ?>/login.php" class="text-warning text-decoration-none">Admin Login &rarr;</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card role-card p-4 text-white">
                            <i class="fa-solid fa-user-tie fs-3 mb-3"></i>
                            <h3 class="h5">Agent</h3>
                            <p class="text-secondary small">Create couriers and update status for your branch.</p>
                            <a href="<?php echo BASE_URL; ?>/login.php" class="text-warning text-decoration-none">Agent Login &rarr;</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card role-card p-4 text-white">
                            <i class="fa-solid fa-user fs-3 mb-3"></i>
                            <h3 class="h5">Customer</h3>
                            <p class="text-secondary small">Track consignments and view delivery status.</p>
                            <a href="<?php echo BASE_URL; ?>/register.php" class="text-warning text-decoration-none">Create Account &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php if (!isLoggedIn()): ?>
        <section class="py-5 cta-band text-center">
            <div class="container">
                <h2 class="mb-2">Ready to get started?</h2>
                <p class="mb-4">Create a free account and start tracking in minutes.</p>
                <a href="<?php echo BASE_URL; ?>/register.php" class="btn btn-light btn-lg">Register Now</a>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
