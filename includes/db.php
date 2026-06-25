<?php

var_dump(getenv("PGHOST"));
var_dump(getenv("PGDATABASE"));
die();

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