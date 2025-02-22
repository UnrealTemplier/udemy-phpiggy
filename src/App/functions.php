<?php

declare(strict_types=1);

function dump_die(mixed $value): void
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

function escape(mixed $value): string
{
    return htmlspecialchars((string)$value);
}

function redirectTo(string $path): void
{
    header("Location: {$path}");
    http_response_code(302);
    exit();
}
