<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'school_hub');
define('DB_USER', 'root');
define('DB_PASS', '');
try {
  $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
} catch (PDOException $e) {
  error_log($e->getMessage());
  exit('Errore di connessione al database.');
}
?>
