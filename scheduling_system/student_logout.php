<?php
require_once __DIR__ . '/config.php';
session_start();
session_unset();
session_destroy();

header("Location: " . FRONTEND_URL);
exit;
