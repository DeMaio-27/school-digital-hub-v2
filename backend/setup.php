<?php
/**
 * Installer Automatico - School Digital Hub v2
 * Questo file crea il database, le tabelle e gli utenti di default
 */

// Configurazione XAMPP default
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'school_hub';

$step = $_GET['step'] ?? '1';
$message = '';
$messageType = '';

// Passo 1: Verifica connessione
if ($step === '1') {
    try {
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $step = '2';
    } catch (PDOException $e) {
        $message = '❌ Impossibile connettersi a MySQL. Assicurati che XAMPP (MySQL) sia avviato.';
        $messageType = 'error';
    }
}

// Passo 2: Crea database
if ($step === '2') {
    try {
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Crea database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$dbname`");
        $step = '3';
        $message = '✅ Database creato con successo!';
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = '❌ Errore nella creazione del database: ' . htmlspecialchars($e->getMessage());
        $messageType = 'error';
    }
}

// Passo 3: Crea tabelle
if ($step === '3') {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Tabella users
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                nome VARCHAR(100) NOT NULL,
                cognome VARCHAR(100) NOT NULL,
                ruolo ENUM('studente', 'professore', 'admin') NOT NULL DEFAULT 'studente',
                classe VARCHAR(20) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        // Tabella voti
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS voti (
                id INT AUTO_INCREMENT PRIMARY KEY,
                studente_id INT NOT NULL,
                materia VARCHAR(100) NOT NULL,
                voto DECIMAL(4,2) NOT NULL,
                data_voto DATE NOT NULL,
                tipo ENUM('scritto', 'orale', 'pratico') DEFAULT 'scritto',
                descrizione TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (studente_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        // Tabella presenze
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS presenze (
                id INT AUTO_INCREMENT PRIMARY KEY,
                studente_id INT NOT NULL,
                data DATE NOT NULL,
                stato ENUM('presente', 'assente', 'giustificato') NOT NULL DEFAULT 'assente',
                motivazione TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (studente_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        // Tabella orario
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS orario (
                id INT AUTO_INCREMENT PRIMARY KEY,
                classe VARCHAR(20) NOT NULL,
                giorno ENUM('lunedi', 'martedi', 'mercoledi', 'giovedi', 'venerdi', 'sabato') NOT NULL,
                ora INT NOT NULL,
                materia VARCHAR(100) NOT NULL,
                aula VARCHAR(20),
                UNIQUE KEY unique_orario (classe, giorno, ora)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        // Tabella messaggi
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS messaggi (
                id INT AUTO_INCREMENT PRIMARY KEY,
                mittente_id INT NOT NULL,
                destinatario_id INT NOT NULL,
                oggetto VARCHAR(255) NOT NULL,
                contenuto TEXT NOT NULL,
                letto BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (mittente_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (destinatario_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        $step = '4';
        $message = '✅ Tabelle create con successo!';
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = '❌ Errore nella creazione delle tabelle: ' . htmlspecialchars($e->getMessage());
        $messageType = 'error';
    }
}

// Passo 4: Inserisci utenti di test
if ($step === '4') {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Password hashate per sicurezza
        $pwd_studente = password_hash('studente123', PASSWORD_DEFAULT);
        $pwd_prof = password_hash('professore123', PASSWORD_DEFAULT);
        $pwd_admin = password_hash('admin123', PASSWORD_DEFAULT);
        
        // Inserisci utenti
        $stmt = $pdo->prepare("
            INSERT INTO users (email, password, nome, cognome, ruolo, classe) 
            VALUES (:email, :password, :nome, :cognome, :ruolo, :classe)
        ");
        
        $utenti = [
            ['studente@school.com', $pwd_studente, 'Mario', 'Rossi', 'studente', '3A'],
            ['studente2@school.com', $pwd_studente, 'Luca', 'Bianchi', 'studente', '3A'],
            ['professore@school.com', $pwd_prof, 'Giuseppe', 'Verdi', 'professore', null],
            ['professore2@school.com', $pwd_prof, 'Anna', 'Neri', 'professore', null],
            ['admin@school.com', $pwd_admin, 'Admin', 'System', 'admin', null]
        ];
        
        foreach ($utenti as $u) {
            $stmt->execute([
                ':email' => $u[0],
                ':password' => $u[1],
                ':nome' => $u[2],
                ':cognome' => $u[3],
                ':ruolo' => $u[4],
                ':classe' => $u[5]
            ]);
        }
        
        $step = '5';
        $message = '✅ Utenti di test inseriti con successo!';
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = '❌ Errore nell\'inserimento utenti: ' . htmlspecialchars($e->getMessage());
        $messageType = 'error';
    }
}

// Passo 5: Inserisci dati di esempio
if ($step === '5') {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Prendi ID studente
        $stmt = $pdo->query("SELECT id FROM users WHERE email = 'studente@school.com'");
        $studente_id = $stmt->fetchColumn();
        
        // Inserisci voti di esempio
        $pdo->exec("
            INSERT INTO voti (studente_id, materia, voto, data_voto, tipo, descrizione) VALUES
            ($studente_id, 'Matematica', 7.5, '2026-09-15', 'scritto', 'Algebra'),
            ($studente_id, 'Italiano', 8.0, '2026-09-18', 'orale', 'Dante'),
            ($studente_id, 'Inglese', 6.5, '2026-09-20', 'scritto', 'Grammar'),
            ($studente_id, 'Informatica', 9.0, '2026-09-22', 'pratico', 'PHP Project')
        ");
        
        // Inserisci presenze
        $pdo->exec("
            INSERT INTO presenze (studente_id, data, stato) VALUES
            ($studente_id, '2026-09-21', 'presente'),
            ($studente_id, '2026-09-22', 'presente'),
            ($studente_id, '2026-09-23', 'assente')
        ");
        
        // Inserisci orario
        $pdo->exec("
            INSERT INTO orario (classe, giorno, ora, materia, aula) VALUES
            ('3A', 'lunedi', 1, 'Matematica', 'A1'),
            ('3A', 'lunedi', 2, 'Italiano', 'A1'),
            ('3A', 'lunedi', 3, 'Inglese', 'A1'),
            ('3A', 'martedi', 1, 'Informatica', 'Lab1'),
            ('3A', 'martedi', 2, 'Informatica', 'Lab1'),
            ('3A', 'mercoledi', 1, 'Scienze', 'A2')
        ");
        
        $step = '6';
        $message = '✅ Dati di esempio inseriti con successo!';
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = '❌ Errore nell\'inserimento dati: ' . htmlspecialchars($e->getMessage());
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - School Digital Hub v2</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        .progress::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #e0e0e0;
            transform: translateY(-50%);
            z-index: 1;
        }
        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            position: relative;
            z-index: 2;
        }
        .step.active {
            background: #667eea;
            color: white;
        }
        .step.completed {
            background: #27ae60;
            color: white;
        }
        .message {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 14px;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5568d3;
        }
        .btn-success {
            background: #27ae60;
        }
        .btn-success:hover {
            background: #219a52;
        }
        .credentials {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .cred-table {
            width: 100%;
            border-collapse: collapse;
        }
        .cred-table th,
        .cred-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        .cred-table th {
            background: #f1f3f5;
            font-weight: 600;
            color: #333;
        }
        .cred-table tr:last-child td {
            border-bottom: none;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-studente { background: #3498db; color: white; }
        .badge-professore { background: #9b59b6; color: white; }
        .badge-admin { background: #e74c3c; color: white; }
        .checklist {
            list-style: none;
            margin: 20px 0;
        }
        .checklist li {
            padding: 8px 0;
            color: #666;
        }
        .checklist li::before {
            content: '✓';
            color: #27ae60;
            font-weight: bold;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Setup Automatico</h1>
        <p class="subtitle">School Digital Hub v2 - Installazione guidata</p>
        
        <div class="progress">
            <div class="step <?= $step >= '1' ? 'active' : '' ?> <?= $step > '1' ? 'completed' : '' ?>">1</div>
            <div class="step <?= $step >= '2' ? 'active' : '' ?> <?= $step > '2' ? 'completed' : '' ?>">2</div>
            <div class="step <?= $step >= '3' ? 'active' : '' ?> <?= $step > '3' ? 'completed' : '' ?>">3</div>
            <div class="step <?= $step >= '4' ? 'active' : '' ?> <?= $step > '4' ? 'completed' : '' ?>">4</div>
            <div class="step <?= $step >= '5' ? 'active' : '' ?> <?= $step > '5' ? 'completed' : '' ?>">5</div>
            <div class="step <?= $step >= '6' ? 'active' : '' ?> <?= $step > '6' ? 'completed' : '' ?>">6</div>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?= $messageType ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <?php if ($step === '1'): ?>
            <p>Connessione a MySQL in corso...</p>
            <meta http-equiv="refresh" content="1;url=?step=2">
            
        <?php elseif ($step === '2'): ?>
            <p>Creazione database <strong>school_hub</strong>...</p>
            <meta http-equiv="refresh" content="1;url=?step=3">
            
        <?php elseif ($step === '3'): ?>
            <p>Creazione tabelle (users, voti, presenze, orario, messaggi)...</p>
            <meta http-equiv="refresh" content="1;url=?step=4">
            
        <?php elseif ($step === '4'): ?>
            <p>Inserimento utenti di test...</p>
            <meta http-equiv="refresh" content="1;url=?step=5">
            
        <?php elseif ($step === '5'): ?>
            <p>Inserimento dati di esempio (voti, presenze, orario)...</p>
            <meta http-equiv="refresh" content="1;url=?step=6">
            
        <?php elseif ($step === '6'): ?>
            <div style="text-align: center; padding: 20px 0;">
                <div style="font-size: 60px; margin-bottom: 20px;">🎉</div>
                <h2 style="color: #27ae60; margin-bottom: 10px;">Installazione Completata!</h2>
                <p style="color: #666; margin-bottom: 30px;">Il tuo School Digital Hub è pronto all'uso.</p>
                
                <div class="credentials">
                    <h3>📋 Credenziali di accesso</h3>
                    <table class="cred-table">
                        <tr>
                            <th>Ruolo</th>
                            <th>Email</th>
                            <th>Password</th>
                        </tr>
                        <tr>
                            <td><span class="badge badge-studente">Studente</span></td>
                            <td>studente@school.com</td>
                            <td>studente123</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-studente">Studente 2</span></td>
                            <td>studente2@school.com</td>
                            <td>studente123</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-professore">Professore</span></td>
                            <td>professore@school.com</td>
                            <td>professore123</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-professore">Professore 2</span></td>
                            <td>professore2@school.com</td>
                            <td>professore123</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-admin">Admin</span></td>
                            <td>admin@school.com</td>
                            <td>admin123</td>
                        </tr>
                    </table>
                </div>
                
                <ul class="checklist">
                    <li>Database <strong>school_hub</strong> creato</li>
                    <li>5 tabelle create (users, voti, presenze, orario, messaggi)</li>
                    <li>5 utenti di test inseriti</li>
                    <li>Dati di esempio aggiunti</li>
                </ul>
                
                <div style="margin-top: 30px;">
                    <a href="../login.html" class="btn btn-success">🔐 Vai al Login</a>
                </div>
                
                <p style="color: #999; font-size: 13px; margin-top: 20px;">
                    ⚠️ <strong>Importante:</strong> Dopo il primo accesso, cambia le password di default!
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
