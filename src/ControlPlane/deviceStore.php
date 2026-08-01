<?php
declare(strict_types=1);

function control_device_root(): string
{
    $root = app_config()['data_dir'] . '/devices';
    if (!is_dir($root) && !mkdir($root, 0750, true) && !is_dir($root)) {
        throw new RuntimeException('Unable to create device store.');
    }
    return $root;
}

function control_device_id(string $accountId, string $publicKey): string
{
    return hash('sha256', $accountId . "\0" . $publicKey);
}

function control_enroll_device(string $accountId, string $publicKey, string $name): array
{
    if ($accountId === '' || $publicKey === '') {
        throw new InvalidArgumentException('Account ID and public key are required.');
    }

    $deviceId = control_device_id($accountId, $publicKey);
    $record = [
        'schema' => 'spotpulse-device/v1',
        'device_id' => $deviceId,
        'account_id' => $accountId,
        'device_name' => mb_substr(trim($name), 0, 120),
        'public_key' => $publicKey,
        'enrolled_at' => gmdate(DATE_ATOM),
        'last_seen_at' => gmdate(DATE_ATOM),
        'revoked' => false,
    ];

    $path = control_device_root() . '/' . $deviceId . '.json';
    file_put_contents($path, json_encode($record, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR), LOCK_EX);
    return $record;
}
