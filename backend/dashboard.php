<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: ../login.html'); exit; }
$nome=htmlspecialchars($_SESSION['nome']); $ruolo=htmlspecialchars(ucfirst($_SESSION['ruolo'])); $classe=htmlspecialchars($_SESSION['classe'] ?? '');
?>
<!doctype html><html lang="it"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Dashboard</title><style>body{font-family:system-ui;background:#0a0e17;color:#e6e9f0;margin:0;padding:40px}main{max-width:900px;margin:auto}a{color:#4f8cff}.card{border:1px solid #ffffff20;border-radius:14px;padding:20px;background:#ffffff08;margin-top:20px}</style></head><body><main><h1>Benvenuto, <?= $nome ?></h1><p><?= $ruolo ?><?= $classe ? ' · Classe '.$classe : '' ?></p><div class="card"><h2>Dashboard protetta</h2><p>Sessione attiva e autenticazione completata correttamente.</p></div><p><a href="logout.php">Esci</a></p></main></body></html>
