<?php

class Baza
{
    const HOST = "localhost";
    const DB_USER = "root";
    const DB_PASS = "";
    const DB_NAME = "gold_calc";
    protected $sql;

    public function __construct()
    {
        $this->sql = mysqli_connect(self::HOST, self::DB_USER, self::DB_PASS, self::DB_NAME);
    }
}