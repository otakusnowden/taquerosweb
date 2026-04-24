<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/app.php';

use App\Controllers\OrderController;

$ctrl   = new OrderController();
$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Routes:
//   GET  /api/orders          → index
//   POST /api/orders          → store
//   POST /api/orders/confirm  → confirm (body: orden_id)
//   POST /api/orders/pay      → pay (body: orden_id)
//   GET  /api/packages        → packages  (handled separately)

if (preg_match('#/api/orders/(\d+)/confirm#', $uri, $m)) {
    $ctrl->confirm((int)$m[1]);
} elseif (preg_match('#/api/orders/(\d+)/pay#', $uri, $m)) {
    $ctrl->pay((int)$m[1]);
} elseif ($method === 'GET') {
    $ctrl->index();
} elseif ($method === 'POST') {
    $ctrl->store();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
