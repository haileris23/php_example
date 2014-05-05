<?php

try{
$db = new PDO(
'mysql:host=localhost;dbname=blandc','blandc','hunter2');

$db->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die("Could not connect to database: {$e->getMessage()}");
}
