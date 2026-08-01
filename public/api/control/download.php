<?php
declare(strict_types=1);
require dirname(__DIR__, 3) . '/src/bootstrap.php';
require dirname(__DIR__, 3) . '/src/ControlPlane/bootstrap.php';

try {
    $cellId = control_safe_cell_id((string)($_GET['cell'] ?? ''));
    $revision = max(1, (int)($_GET['revision'] ?? 0));
    $path = control_release_root() . '/releases/' . $cellId . '/' . sprintf('%06d', $revision) . '/package.zip';

    if (!is_file($path)) {
        control_json_response(['error' => 'RELEASE_NOT_FOUND'], 404);
    }

    control_audit('release.downloaded', ['cell_id' => $cellId, 'revision' => $revision]);
    header('Content-Type: application/zip');
    header('Content-Length: ' . filesize($path));
    header('Content-Disposition: attachment; filename="' . $cellId . '-' . $revision . '.zip"');
    header('X-Content-Type-Options: nosniff');
    readfile($path);
    exit;
} catch (Throwable $error) {
    control_json_response(['error' => 'DOWNLOAD_ERROR', 'message' => $error->getMessage()], 400);
}
