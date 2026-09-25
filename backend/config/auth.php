<?php
if(session_status()===PHP_SESSION_NONE)session_start();
function e($value){return htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8');}
function require_login(){if(empty($_SESSION['user_id'])){header('Location: ../login.php');exit;}}
function require_role($roles){require_login();$roles=(array)$roles;if(!in_array($_SESSION['ruolo']??'', $roles,true)){http_response_code(403);exit('Accesso non autorizzato.');}}
function flash($key,$message=null){if($message!==null){$_SESSION['flash'][$key]=$message;return;} $out=$_SESSION['flash'][$key]??null;unset($_SESSION['flash'][$key]);return $out;}
?>