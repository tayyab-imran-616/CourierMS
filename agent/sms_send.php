<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if (!isLoggedIn() || currentRole() != 'agent') {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courierId = $_POST['courier_id'];
    $type = $_POST['type'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM couriers WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $courierId);
    mysqli_stmt_execute($stmt);
    $courier = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($courier) {
        if ($type == 'delivery') {
            $message = "Your consignment " . $courier['consignment_no'] . " has been delivered.";
        } else {
            $message = "Your consignment " . $courier['consignment_no'] . " has been picked up from " . $courier['from_location'] . " and is on its way to " . $courier['to_location'] . ".";
        }

        $insert = mysqli_prepare($conn, "INSERT INTO sms_logs (courier_id, type, message) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($insert, "iss", $courierId, $type, $message);
        mysqli_stmt_execute($insert);
    }
}

header('Location: ' . BASE_URL . '/agent/couriers.php?smssent=1');
exit;
?>
