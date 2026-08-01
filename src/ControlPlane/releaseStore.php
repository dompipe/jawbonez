<?php
declare(strict_types=1);

function control_release_root(): string
{
    return app_config()['release_dir'];
}

function control_release_index_path(): string
{
    return control_release_root() . '/index.json';
}

function control_read_release_index(): array
{
    $path = control_release_index_path();
    if (!is_file($path)) {
        return ['schema' => 'spotpulse-release-index/v1', 'revision' => 0, 'cells' => []];
    }

    $decoded = json_decode((string)file_get_contents($path), true);
    return is_array($decoded) ? $decoded : ['schema' => 'spotpulse-release-index/v1', 'revision' => 0, 'cells' => []];
}

function control_write_release_index(array $index): void
{
    $path = control_release_index_path();
    $temporaryPath = $path . '.tmp.' . bin2hex(random_bytes(5));
    $json = json_encode($index, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

    if (file_put_contents($temporaryPath, $json, LOCK_EX) === false || !rename($temporaryPath, $path)) {
        @unlink($temporaryPath);
        throw new RuntimeException('Unable to atomically write release index.');
    }
}

function control_manifest_for_client(array $index, array $installed): array
{
    $updates = [];
    foreach (($index['cells'] ?? []) as $cellId => $cell) {
        if (!is_array($cell)) {
            continue;
        }

        if ((int)($cell['revision'] ?? 0) > (int)($installed[$cellId] ?? 0)) {
            $updates[] = $cell;
        }
    }

    return [
        'schema' => 'spotpulse-client-manifest/v1',
        'server_revision' => (int)($index['revision'] ?? 0),
        'generated_at' => gmdate(DATE_ATOM),
        'updates' => $updates,
    ];
}
