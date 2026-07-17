<?php
function statusBadge($status) {
    if ($status == 'Delivered') return 'bg-success';
    if ($status == 'In Transit' || $status == 'Out for Delivery') return 'bg-warning text-dark';
    return 'bg-secondary';
}
?>
