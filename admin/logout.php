<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

unset(
    $_SESSION['admin_id'],
    $_SESSION['admin_name'],
    $_SESSION['admin_email']
);

session_regenerate_id(true);

redirect('admin/login.php');
