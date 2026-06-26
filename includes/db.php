<?php

if (file_exists(__DIR__ . '/../.env'))
{
    $env = parse_ini_file(__DIR__ . '/../.env');

    $host = $env['DB_HOST'];
    $port = $env['DB_PORT'];
    $dbname = $env['DB_NAME'];
    $user = $env['DB_USER'];
    $password = $env['DB_PASSWORD'];
}
else
{
    $host = getenv('PGHOST');
    $port = getenv('PGPORT');
    $dbname = getenv('PGDATABASE');
    $user = getenv('PGUSER');
    $password = getenv('PGPASSWORD');
}

$conn = pg_connect(
    "host=$host port=$port dbname=$dbname user=$user password=$password"
);

if (!$conn)
{
    die("Не удалось подключиться к PostgreSQL ($host:$port)");
}