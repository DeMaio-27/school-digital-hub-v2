<?php
session_start();
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ../login.html'); exit; }
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';
$stmt = $pdo->prepare('SELECT id,nome,email,password,ruolo,classe FROM utenti WHERE email=?');
$stmt->execute([$email]);
$user = $stmt->fetch();
if ($user && password_verify($password, $user['password'])) {
  $_SESSION['user_id']=$user['id']; $_SESSION['nome']=$user['nome']; $_SESSION['ruolo']=$user['ruolo']; $_SESSION['classe']=$user['classe'];
  header('Location: dashboard.php'); exit;
}
header('Location: ../login.html'); exit;
?>
