<?php

$env = parse_ini_file(__DIR__ . "/../.env");

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