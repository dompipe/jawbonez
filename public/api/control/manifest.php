<?php
declare(strict_types=1);
require dirname(__DIR__, 3) . '/src/bootstrap.php';
require dirname(__DIR__, 3) . '/src/ControlPlane/bootstrap.php';

try {
    $request = $_SERVER['REQUEST_METHOD'] === 'POST' ? control_read_json_request() : [];
    $installed = is_array($request['installed_cells'] ?? null) ? $request['installed_cells'] : [];
    control_audit('manifest.requested', ['cell_count' => count($installed)]);
    control_json_response(control_manifest_for_client(control_read_release_index(), $installed));
} catch (Throwable $error) {
    control_json_response(['error' => 'MANIFEST_ERROR', 'message' => $error->getMessage()], 400);
}
