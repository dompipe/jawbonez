<?php
declare(strict_types=1);

function spotpulse_env(string $name, ?string $default = null): ?string
{
    $value = getenv($name);
    return $value === false || $value === '' ? $default : $value;
}

function app_config(): array
{
    static $config;
    if (is_array($config)) {
        return $config;
    }

    $root = dirname(__DIR__);
    $dataDir = spotpulse_env('SPOTPULSE_DATA_DIR', $root . '/data');
    $releaseDir = spotpulse_env('SPOTPULSE_RELEASE_DIR', $root . '/releases');

    foreach ([$dataDir, $releaseDir] as $directory) {
        if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
            throw new RuntimeException('Unable to create runtime directory: ' . $directory);
        }
    }

    return $config = [
        'base_url' => spotpulse_env('SPOTPULSE_BASE_URL', 'http://127.0.0.1:8787'),
        'data_dir' => $dataDir,
        'release_dir' => $releaseDir,
    ];
}
