<?php

$conn = pg_connect(
    "host=" . getenv("PGHOST") .
    " port=" . getenv("PGPORT") .
    " dbname=" . getenv("PGDATABASE") .
    " user=" . getenv("PGUSER") .
    " password=" . getenv("PGPASSWORD")
);

if (!$conn)
{
    die(pg_last_error());
}