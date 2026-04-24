<?php
declare(strict_types=1);
require_once __DIR__ . '/config/app.php';

use App\Controllers\AuthController;

(new AuthController())->logout();
