<?php
declare(strict_types=1);

function control_audit(string $event, array $context = []): void
{
    unset($context['secret'], $context['api_secret'], $context['private_key'], $context['authorization']);
    $path = app_config()['data_dir'] . '/audit/control-plane-' . gmdate('Y-m') . '.jsonl';
    if (!is_dir(dirname($path))) {
        mkdir(dirname($path), 0750, true);
    }

    $line = json_encode([
        'time' => gmdate(DATE_ATOM),
        'event' => $event,
        'context' => $context,
    ], JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . PHP_EOL;

    file_put_contents($path, $line, FILE_APPEND | LOCK_EX);
}
