<?php
/**
 * Login Handler - School Digital Hub v2
 * Gestisce l'autenticazione degli utenti
 */

require_once 'config.php';

// Se già loggato, redirect alla dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$email = '';

// Processa form di login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Inserisci email e password';
    } else {
        try {
            // Cerca utente per email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successo - salva dati in sessione
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_nome'] = $user['nome'];
                $_SESSION['user_cognome'] = $user['cognome'];
                $_SESSION['user_ruolo'] = $user['ruolo'];
                $_SESSION['user_classe'] = $user['classe'];
                
                // Regenera session ID per sicurezza
                session_regenerate_id(true);
                
                // Redirect alla dashboard
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Email o password non corretti';
            }
        } catch (PDOException $e) {
            $error = 'Errore di connessione al database. Contatta l\'amministratore.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - School Digital Hub</title>
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
        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 100%;
            padding: 40px;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 {
            color: #667eea;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .logo p {
            color: #666;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .error-message {
            background: #fee;
            color: #c00;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #fcc;
        }
        .help-text {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #666;
        }
        .help-text a {
            color: #667eea;
            text-decoration: none;
        }
        .help-text a:hover {
            text-decoration: underline;
        }
        .setup-link {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
        }
        .setup-link a {
            color: #27ae60;
            text-decoration: none;
            font-weight: 500;
        }
        .setup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>🏫 School Digital Hub</h1>
            <p>Accedi alla tua area personale</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">📧 Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?= htmlspecialchars($email) ?>" 
                    required 
                    placeholder="studente@school.com"
                    autocomplete="email"
                >
            </div>
            
            <div class="form-group">
                <label for="password">🔒 Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    placeholder="••••••••"
                    autocomplete="current-password"
                >
            </div>
            
            <button type="submit" class="btn-login">🔐 Accedi</button>
        </form>
        
        <div class="help-text">
            <p>Non hai un account? <a href="setup.php">Esegui il setup</a></p>
        </div>
        
        <div class="setup-link">
            <a href="setup.php">🚀 Clicca qui per il setup automatico</a>
        </div>
    </div>
</body>
</html>
