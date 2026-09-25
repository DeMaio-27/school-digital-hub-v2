<?php
declare(strict_types=1);

/**
 * School Digital Hub v2 - installer idempotente per XAMPP.
 * Può essere eseguito più volte senza creare utenti o dati duplicati.
 */

$config = [
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'school_hub',
    'username' => 'root',
    'password' => '',
];

$step = 'checking';
$error = '';
$success = [];
$pdo = null;

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};charset=utf8mb4",
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    $pdo->exec(
        "CREATE DATABASE IF NOT EXISTS `{$config['database']}` " .
        "CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
    );
    $pdo->exec("USE `{$config['database']}`");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        nome VARCHAR(100) NOT NULL,
        cognome VARCHAR(100) NOT NULL,
        ruolo ENUM('studente','professore','admin') NOT NULL DEFAULT 'studente',
        classe VARCHAR(20) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS voti (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        studente_id INT UNSIGNED NOT NULL,
        materia VARCHAR(100) NOT NULL,
        voto DECIMAL(4,2) NOT NULL,
        data_voto DATE NOT NULL,
        tipo ENUM('scritto','orale','pratico') NOT NULL DEFAULT 'scritto',
        descrizione TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_voti_studente FOREIGN KEY (studente_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS presenze (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        studente_id INT UNSIGNED NOT NULL,
        data DATE NOT NULL,
        stato ENUM('presente','assente','giustificato') NOT NULL DEFAULT 'assente',
        motivazione TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_presenze_studente FOREIGN KEY (studente_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS orario (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        classe VARCHAR(20) NOT NULL,
        giorno ENUM('lunedi','martedi','mercoledi','giovedi','venerdi','sabato') NOT NULL,
        ora TINYINT UNSIGNED NOT NULL,
        materia VARCHAR(100) NOT NULL,
        aula VARCHAR(20) NULL,
        UNIQUE KEY unique_orario (classe, giorno, ora)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS messaggi (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        mittente_id INT UNSIGNED NOT NULL,
        destinatario_id INT UNSIGNED NOT NULL,
        oggetto VARCHAR(255) NOT NULL,
        contenuto TEXT NOT NULL,
        letto TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_messaggi_mittente FOREIGN KEY (mittente_id) REFERENCES users(id) ON DELETE CASCADE,
        CONSTRAINT fk_messaggi_destinatario FOREIGN KEY (destinatario_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $users = [
        ['studente@school.com', 'studente123', 'Mario', 'Rossi', 'studente', '3A'],
        ['studente2@school.com', 'studente123', 'Luca', 'Bianchi', 'studente', '3A'],
        ['professore@school.com', 'professore123', 'Giuseppe', 'Verdi', 'professore', null],
        ['professore2@school.com', 'professore123', 'Anna', 'Neri', 'professore', null],
        ['admin@school.com', 'admin123', 'Admin', 'System', 'admin', null],
    ];

    $userStmt = $pdo->prepare("INSERT INTO users (email,password,nome,cognome,ruolo,classe)
        VALUES (:email,:password,:nome,:cognome,:ruolo,:classe)
        ON DUPLICATE KEY UPDATE nome=VALUES(nome), cognome=VALUES(cognome), ruolo=VALUES(ruolo), classe=VALUES(classe)");

    foreach ($users as [$email, $password, $nome, $cognome, $ruolo, $classe]) {
        $userStmt->execute([
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':nome' => $nome,
            ':cognome' => $cognome,
            ':ruolo' => $ruolo,
            ':classe' => $classe,
        ]);
    }

    $studentId = (int) $pdo->query("SELECT id FROM users WHERE email='studente@school.com'")->fetchColumn();

    $seedGrade = $pdo->prepare("INSERT INTO voti (studente_id,materia,voto,data_voto,tipo,descrizione)
        SELECT :student_id,:materia,:voto,:data_voto,:tipo,:descrizione
        WHERE NOT EXISTS (SELECT 1 FROM voti WHERE studente_id=:student_id2 AND materia=:materia2 AND data_voto=:data_voto2)");
    foreach ([
        ['Matematica', 7.5, '2026-09-15', 'scritto', 'Algebra'],
        ['Italiano', 8.0, '2026-09-18', 'orale', 'Dante'],
        ['Inglese', 6.5, '2026-09-20', 'scritto', 'Grammar'],
        ['Informatica', 9.0, '2026-09-22', 'pratico', 'PHP Project'],
    ] as [$materia, $voto, $data, $tipo, $descrizione]) {
        $seedGrade->execute([
            ':student_id' => $studentId, ':materia' => $materia, ':voto' => $voto,
            ':data_voto' => $data, ':tipo' => $tipo, ':descrizione' => $descrizione,
            ':student_id2' => $studentId, ':materia2' => $materia, ':data_voto2' => $data,
        ]);
    }

    $seedAttendance = $pdo->prepare("INSERT INTO presenze (studente_id,data,stato)
        SELECT :student_id,:data,:stato WHERE NOT EXISTS
        (SELECT 1 FROM presenze WHERE studente_id=:student_id2 AND data=:data2)");
    foreach ([['2026-09-21', 'presente'], ['2026-09-22', 'presente'], ['2026-09-23', 'assente']] as [$data, $stato]) {
        $seedAttendance->execute([
            ':student_id' => $studentId, ':data' => $data, ':stato' => $stato,
            ':student_id2' => $studentId, ':data2' => $data,
        ]);
    }

    $schedule = [
        ['lunedi', 1, 'Matematica', 'A1'], ['lunedi', 2, 'Italiano', 'A1'],
        ['lunedi', 3, 'Inglese', 'A1'], ['martedi', 1, 'Informatica', 'Lab1'],
        ['martedi', 2, 'Informatica', 'Lab1'], ['mercoledi', 1, 'Scienze', 'A2'],
    ];
    $scheduleStmt = $pdo->prepare("INSERT INTO orario (classe,giorno,ora,materia,aula)
        VALUES ('3A',:giorno,:ora,:materia,:aula)
        ON DUPLICATE KEY UPDATE materia=VALUES(materia), aula=VALUES(aula)");
    foreach ($schedule as [$giorno, $ora, $materia, $aula]) {
        $scheduleStmt->execute([':giorno' => $giorno, ':ora' => $ora, ':materia' => $materia, ':aula' => $aula]);
    }

    $step = 'complete';
    $success[] = 'Connessione a MySQL riuscita';
    $success[] = 'Database e tabelle verificati';
    $success[] = 'Utenti di test verificati';
    $success[] = 'Dati demo verificati';
} catch (Throwable $exception) {
    $step = 'error';
    $error = $exception->getMessage();
}
?>
<!doctype html>
<html lang="it">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Setup - School Digital Hub</title>
<style>
body{font-family:Arial,sans-serif;background:#eef1ff;margin:0;padding:24px;color:#202124}.box{max-width:720px;margin:auto;background:#fff;border-radius:14px;padding:32px;box-shadow:0 12px 40px #0002}h1{margin-top:0;color:#3949ab}.ok{background:#e8f5e9;color:#1b5e20;padding:14px;border-radius:8px}.err{background:#ffebee;color:#b71c1c;padding:14px;border-radius:8px;white-space:pre-wrap;overflow:auto}.creds{width:100%;border-collapse:collapse;margin-top:18px}.creds th,.creds td{border-bottom:1px solid #ddd;padding:10px;text-align:left}.btn{display:inline-block;margin-top:22px;background:#3949ab;color:#fff;text-decoration:none;padding:12px 18px;border-radius:8px}
</style>
</head>
<body><main class="box"><h1>🚀 School Digital Hub</h1>
<?php if ($step === 'complete'): ?>
<div class="ok"><strong>Installazione completata.</strong><br><?= implode('<br>', array_map('htmlspecialchars', $success)) ?></div>
<h2>Credenziali demo</h2>
<table class="creds"><tr><th>Ruolo</th><th>Email</th><th>Password</th></tr>
<tr><td>Studente</td><td>studente@school.com</td><td>studente123</td></tr>
<tr><td>Professore</td><td>professore@school.com</td><td>professore123</td></tr>
<tr><td>Admin</td><td>admin@school.com</td><td>admin123</td></tr></table>
<a class="btn" href="../login.php">Vai al login</a>
<?php elseif ($step === 'error'): ?>
<div class="err"><strong>Setup non riuscito.</strong>
<?= htmlspecialchars($error) ?></div>
<p>Controlla che MySQL sia avviato in XAMPP e riprova.</p>
<a class="btn" href="setup.php">Riprova</a>
<?php endif; ?></main></body></html>
