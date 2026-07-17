<?php $adminPage = basename($_SERVER['PHP_SELF']); ?>
<div class="bg-white border-bottom">
    <div class="container">
        <ul class="nav nav-pills py-3 gap-2 flex-wrap">
            <li class="nav-item">
                <a class="nav-link <?php if ($adminPage == 'dashboard.php') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/admin/dashboard.php"><i class="fa-solid fa-gauge me-1"></i>Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($adminPage == 'couriers.php' || $adminPage == 'courier_add.php' || $adminPage == 'courier_edit.php') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/admin/couriers.php"><i class="fa-solid fa-box me-1"></i>Couriers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($adminPage == 'agents.php') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/admin/agents.php"><i class="fa-solid fa-user-tie me-1"></i>Agents</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($adminPage == 'customers.php') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/admin/customers.php"><i class="fa-solid fa-address-book me-1"></i>Customers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($adminPage == 'export_report.php') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/admin/export_report.php"><i class="fa-solid fa-download me-1"></i>Reports</a>
            </li>
        </ul>
    </div>
</div>
