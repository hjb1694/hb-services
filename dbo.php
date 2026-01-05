<?php

function createDBInstance() {
    $DB_HOST = '';
    $DB_USER = '';
    $DB_PASSWORD = '';
    $DB_NAME = '';

    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

    if($conn->connect_errno){
        throw new Exception('MYSQL_CONNECT_ERR');
    }

    return $conn;
}


?>