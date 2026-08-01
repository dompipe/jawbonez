<?php
declare(strict_types=1);
require dirname(__DIR__, 3) . '/src/bootstrap.php';
require dirname(__DIR__, 3) . '/src/ControlPlane/bootstrap.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        control_json_response(['error' => 'METHOD_NOT_ALLOWED'], 405);
    }

    $request = control_read_json_request();
    $record = control_enroll_device(
        trim((string)($request['account_id'] ?? '')),
        trim((string)($request['public_key'] ?? '')),
        trim((string)($request['device_name'] ?? 'Windows PC'))
    );

    control_audit('device.enrolled', [
        'device_id' => $record['device_id'],
        'account_id' => $record['account_id'],
    ]);

    control_json_response(['ok' => true, 'device_id' => $record['device_id']]);
} catch (Throwable $error) {
    control_json_response(['error' => 'DEVICE_ENROLLMENT_ERROR', 'message' => $error->getMessage()], 400);
}
