<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/bootstrap.php';
require dirname(__DIR__) . '/src/ControlPlane/bootstrap.php';

control_json_response([
    'service' => 'jawbonez-spotpulse-control-plane',
    'status' => 'ready',
    'privacy_boundary' => 'No broker credentials, balances, orders, fills, P&L, or wallet state are accepted by this service.',
    'endpoints' => [
        'GET /api/control/health',
        'POST /api/control/manifest',
        'GET /api/control/download?cell={id}&revision={n}',
        'POST /api/device/enroll',
    ],
]);
