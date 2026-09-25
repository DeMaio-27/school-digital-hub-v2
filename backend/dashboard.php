<?php
/**
 * Dashboard - School Digital Hub v2
 * Pagina principale dopo il login
 */

require_once 'config.php';

// Verifica che l'utente sia loggato
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

// Recupera dati utente
$user_id = $_SESSION['user_id'];
$user_nome = $_SESSION['user_nome'];
$user_cognome = $_SESSION['user_cognome'];
$user_ruolo = $_SESSION['user_ruolo'];
$user_classe = $_SESSION['user_classe'];
$user_email = $_SESSION['user_email'];

// Recupera statistiche in base al ruolo
try {
    if ($user_ruolo === 'studente') {
        // Voti dello studente
        $stmt = $pdo->prepare("
            SELECT materia, AVG(voto) as media, COUNT(*) as count 
            FROM voti WHERE studente_id = :id 
            GROUP BY materia ORDER BY media DESC
        ");
        $stmt->execute([':id' => $user_id]);
        $voti_per_materia = $stmt->fetchAll();
        
        // Media generale
        $stmt = $pdo->prepare("
            SELECT AVG(voto) as media_generale FROM voti WHERE studente_id = :id
        ");
        $stmt->execute([':id' => $user_id]);
        $media_generale = $stmt->fetchColumn() ?: 0;
        
        // Presenze ultime 2 settimane
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as presenti FROM presenze 
            WHERE studente_id = :id AND stato = 'presente' 
            AND data >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
        ");
        $stmt->execute([':id' => $user_id]);
        $presenze_recenti = $stmt->fetchColumn() ?: 0;
        
    } elseif ($user_ruolo === 'professore') {
        // Numero studenti totali
        $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE ruolo = 'studente'");
        $totale_studenti = $stmt->fetchColumn();
        
        // Voti inseriti dal professore (ultimi 30 giorni)
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM voti 
            WHERE data_voto >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        ");
        $stmt->execute();
        $voti_recenti = $stmt->fetchColumn() ?: 0;
        
    } else { // admin
        // Statistiche generali
        $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE ruolo = 'studente'");
        $totale_studenti = $stmt->fetchColumn();
        
        $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE ruolo = 'professore'");
        $totale_professori = $stmt->fetchColumn();
        
        $stmt = $pdo->query("SELECT COUNT(*) FROM voti");
        $totale_voti = $stmt->fetchColumn();
    }
} catch (PDOException $e) {
    $error = "Errore nel caricamento dati: " . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - School Digital Hub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: #f5f6fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar h1 {
            font-size: 20px;
        }
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-info {
            text-align: right;
        }
        .user-info .name {
            font-weight: 600;
            font-size: 14px;
        }
        .user-info .role {
            font-size: 12px;
            opacity: 0.9;
        }
        .btn-logout {
            padding: 8px 20px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }
        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .welcome-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .welcome-card h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .welcome-card p {
            color: #666;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #667eea;
        }
        .stat-card h3 {
            color: #999;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .stat-card .value {
            font-size: 32px;
            font-weight: 700;
            color: #333;
        }
        .stat-card .subtext {
            font-size: 13px;
            color: #999;
            margin-top: 5px;
        }
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }
        .content-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .content-card h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 18px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-studente { background: #3498db; color: white; }
        .badge-professore { background: #9b59b6; color: white; }
        .badge-admin { background: #e74c3c; color: white; }
        .grade-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .grade-item:last-child {
            border-bottom: none;
        }
        .grade-item .materia {
            font-weight: 500;
            color: #333;
        }
        .grade-item .voto {
            font-weight: 700;
            color: #667eea;
        }
        .grade-item .media {
            font-size: 13px;
            color: #999;
        }
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            .navbar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .navbar-user {
                flex-direction: column;
            }
            .user-info {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🏫 School Digital Hub</h1>
        <div class="navbar-user">
            <div class="user-info">
                <div class="name"><?= htmlspecialchars($user_nome . ' ' . $user_cognome) ?></div>
                <div class="role">
                    <span class="badge badge-<?= $user_ruolo ?>"><?= ucfirst($user_ruolo) ?></span>
                    <?php if ($user_classe): ?>
                        - Classe <?= htmlspecialchars($user_classe) ?>
                    <?php endif; ?>
                </div>
            </div>
            <a href="logout.php" class="btn-logout">🚪 Esci</a>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome-card">
            <h2>Bentornato, <?= htmlspecialchars($user_nome) ?>! 👋</h2>
            <p>Ecco una panoramica della tua attività scolastica.</p>
        </div>
        
        <div class="stats-grid">
            <?php if ($user_ruolo === 'studente'): ?>
                <div class="stat-card">
                    <h3>Media Generale</h3>
                    <div class="value"><?= number_format($media_generale, 2) ?>/10</div>
                    <div class="subtext">Calcolata su tutti i voti</div>
                </div>
                <div class="stat-card">
                    <h3>Materie</h3>
                    <div class="value"><?= count($voti_per_materia) ?></div>
                    <div class="subtext">Con voti registrati</div>
                </div>
                <div class="stat-card">
                    <h3>Presenze</h3>
                    <div class="value"><?= $presenze_recenti ?></div>
                    <div class="subtext">Ultimi 14 giorni</div>
                </div>
            <?php elseif ($user_ruolo === 'professore'): ?>
                <div class="stat-card">
                    <h3>Studenti Totali</h3>
                    <div class="value"><?= $totale_studenti ?></div>
                    <div class="subtext">Nel sistema</div>
                </div>
                <div class="stat-card">
                    <h3>Voti Recenti</h3>
                    <div class="value"><?= $voti_recenti ?></div>
                    <div class="subtext">Ultimi 30 giorni</div>
                </div>
            <?php else: ?>
                <div class="stat-card">
                    <h3>Studenti</h3>
                    <div class="value"><?= $totale_studenti ?></div>
                    <div class="subtext">Registrati</div>
                </div>
                <div class="stat-card">
                    <h3>Professori</h3>
                    <div class="value"><?= $totale_professori ?></div>
                    <div class="subtext">Nel sistema</div>
                </div>
                <div class="stat-card">
                    <h3>Voti Totali</h3>
                    <div class="value"><?= $totale_voti ?></div>
                    <div class="subtext">Nel database</div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="content-grid">
            <div class="content-card">
                <h3>📊 Ultime Attività</h3>
                <?php if ($user_ruolo === 'studente' && !empty($voti_per_materia)): ?>
                    <?php foreach (array_slice($voti_per_materia, 0, 5) as $voto): ?>
                        <div class="grade-item">
                            <div>
                                <div class="materia"><?= htmlspecialchars($voto['materia']) ?></div>
                                <div class="media"><?= $voto['count'] ?> voti</div>
                            </div>
                            <div class="voto"><?= number_format($voto['media'], 2) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #999; text-align: center; padding: 40px 0;">
                        Nessuna attività recente da mostrare.
                    </p>
                <?php endif; ?>
            </div>
            
            <div class="content-card">
                <h3>ℹ️ Il tuo profilo</h3>
                <div style="padding: 15px 0;">
                    <div style="margin-bottom: 15px;">
                        <strong style="color: #999; font-size: 12px;">EMAIL</strong>
                        <div style="color: #333;"><?= htmlspecialchars($user_email) ?></div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <strong style="color: #999; font-size: 12px;">RUOLO</strong>
                        <div>
                            <span class="badge badge-<?= $user_ruolo ?>"><?= ucfirst($user_ruolo) ?></span>
                        </div>
                    </div>
                    <?php if ($user_classe): ?>
                    <div style="margin-bottom: 15px;">
                        <strong style="color: #999; font-size: 12px;">CLASSE</strong>
                        <div style="color: #333;"><?= htmlspecialchars($user_classe) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
