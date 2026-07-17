<?php $agentPage = basename($_SERVER['PHP_SELF']); ?>
<div class="bg-white border-bottom">
    <div class="container">
        <ul class="nav nav-pills py-3 gap-2 flex-wrap">
            <li class="nav-item">
                <a class="nav-link <?php if ($agentPage == 'dashboard.php') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/agent/dashboard.php"><i class="fa-solid fa-gauge me-1"></i>Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($agentPage == 'couriers.php' || $agentPage == 'courier_add.php' || $agentPage == 'courier_edit.php') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/agent/couriers.php"><i class="fa-solid fa-box me-1"></i>Couriers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($agentPage == 'export_report.php') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/agent/export_report.php"><i class="fa-solid fa-download me-1"></i>Reports</a>
            </li>
        </ul>
    </div>
</div>
