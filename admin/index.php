<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

if (admin_logged_in()) {
    redirect('admin/dashboard.php');
}

redirect('admin/login.php');
