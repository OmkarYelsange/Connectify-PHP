<?php
require_once __DIR__ . '/config/database.php';

if (isset($pdo)) {
    echo 'Database connection successful!';
}