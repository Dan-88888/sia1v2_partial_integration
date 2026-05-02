<?php
require_once __DIR__ . '/inc/session_handler.php';
session_start();
session_unset();
session_destroy();

require_once __DIR__ . '/config.php';
header("Location: " . FRONTEND_URL);
exit;
