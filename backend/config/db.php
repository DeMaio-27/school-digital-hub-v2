<?php
const DB_HOST='localhost'; const DB_NAME='school_hub'; const DB_USER='root'; const DB_PASS='';
try{$pdo=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);}
catch(PDOException $e){error_log($e->getMessage());exit('Errore di connessione al database. Controlla backend/config/db.php');}
?>