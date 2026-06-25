<?php

if (file_exists(__DIR__ . "/../.env"))
{
    $env = parse_ini_file(__DIR__ . "/../.env");
}
else
{
    $env = [
        "DB_HOST" => getenv("DB_HOST"),
        "DB_PORT" => getenv("DB_PORT"),
        "DB_NAME" => getenv("DB_NAME"),
        "DB_USER" => getenv("DB_USER"),
        "DB_PASSWORD" => getenv("DB_PASSWORD"),
    ];
}

$conn = pg_connect(
    "host={$env["DB_HOST"]} " .
    "port={$env["DB_PORT"]} " .
    "dbname={$env["DB_NAME"]} " .
    "user={$env["DB_USER"]} " .
    "password={$env["DB_PASSWORD"]}"
);

if (!$conn)
{
    die("Ошибка подключения к БД");
}