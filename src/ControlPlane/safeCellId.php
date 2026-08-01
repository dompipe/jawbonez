<?php
declare(strict_types=1);

function control_safe_cell_id(string $value): string
{
    $value = trim($value);
    if ($value === '' || preg_match('/^[a-z0-9][a-z0-9._-]{1,127}$/', $value) !== 1) {
        throw new InvalidArgumentException('Invalid cell identifier.');
    }

    return $value;
}
