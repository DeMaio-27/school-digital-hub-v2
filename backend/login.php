<?php
require_once __DIR__.'/config/db.php';require_once __DIR__.'/config/auth.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: ../login.php');exit;}
$email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);$password=$_POST['password']??'';
$stmt=$pdo->prepare('SELECT id,nome,email,password,ruolo,classe FROM utenti WHERE email=? LIMIT 1');$stmt->execute([$email]);$user=$stmt->fetch();
if(!$user||!password_verify($password,$user['password'])){$_SESSION['login_error']='Email o password non validi.';header('Location: ../login.php');exit;}
session_regenerate_id(true);$_SESSION['user_id']=$user['id'];$_SESSION['nome']=$user['nome'];$_SESSION['email']=$user['email'];$_SESSION['ruolo']=$user['ruolo'];$_SESSION['classe']=$user['classe'];header('Location: dashboard.php');exit;
?>