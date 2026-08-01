<?php
declare(strict_types=1);
require dirname(__DIR__, 3) . '/src/bootstrap.php';
require dirname(__DIR__, 3) . '/src/ControlPlane/bootstrap.php';

control_json_response([
    'ok' => true,
    'service' => 'jawbonez-spotpulse-control-plane',
    'time' => gmdate(DATE_ATOM),
    'release_revision' => (int)(control_read_release_index()['revision'] ?? 0),
]);
