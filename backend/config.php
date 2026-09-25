<?php
/**
 * Configurazione Database
 * Modifica questi parametri in base al tuo ambiente
 */

// Configurazione predefinita per XAMPP
$db_host = 'localhost';
$db_name = 'school_hub';
$db_user = 'root';
$db_pass = '';  // Lascia vuoto per XAMPP default

// Creazione connessione PDO
try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    // Se il database non esiste, mostro un errore utile
    if (strpos($e->getMessage(), 'Unknown database') !== false) {
        die(
            '<div style="font-family: Arial; padding: 40px; text-align: center;">' .
            '<h1 style="color: #e74c3c;">⚠️ Database non trovato</h1>' .
            '<p style="color: #666;">Il database <strong>school_hub</strong> non esiste.</p>' .
            '<p style="color: #666;">Per creare automaticamente il database e gli utenti, vai su:</p>' .
            '<a href="setup.php" style="display: inline-block; padding: 15px 30px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0;">🚀 Esegui Setup Automatico</a>' .
            '<p style="color: #999; font-size: 14px;">Oppure esegui manualmente: http://localhost/school-digital-hub-v2/backend/setup.php</p>' .
            '</div>'
        );
    }
    die("Errore di connessione al database: " . htmlspecialchars($e->getMessage()));
}

// Avvio sessione
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
