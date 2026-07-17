<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color:#10182b;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>/index.php">
            <i class="fa-solid fa-truck-fast text-warning"></i> CourierMS
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link <?php if ($currentPage == 'index.php') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($currentPage == 'track.php') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/track.php">Track</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($currentPage == 'about.php') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($currentPage == 'contact.php') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/contact.php">Contact</a>
                </li>

                <?php if (isLoggedIn()): ?>
                    <li class="nav-item mt-2 mt-lg-0 ms-lg-2">
                        <a class="btn btn-warning btn-sm text-dark fw-semibold" href="<?php echo BASE_URL; ?>/includes/auth.php?logout=1">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item mt-2 mt-lg-0">
                        <a class="btn btn-outline-light btn-sm" href="<?php echo BASE_URL; ?>/login.php">Login</a>
                    </li>
                    <li class="nav-item mt-2 mt-lg-0 ms-lg-2">
                        <a class="btn btn-warning btn-sm text-dark fw-semibold" href="<?php echo BASE_URL; ?>/register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
