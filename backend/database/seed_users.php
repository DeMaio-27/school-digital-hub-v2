<?php
require_once __DIR__.'/../config/db.php';
$exists=$pdo->query("SELECT COUNT(*) FROM utenti")->fetchColumn();
if($exists>0)exit('Seed non eseguito: esistono già utenti nel database.');
$hash=password_hash('password123',PASSWORD_DEFAULT);
$pdo->prepare('INSERT INTO utenti(nome,email,password,ruolo,classe) VALUES(?,?,?,?,?)')->execute(['Mario Rossi','mario@studente.it',$hash,'studente','5A']);
$pdo->prepare('INSERT INTO utenti(nome,email,password,ruolo,classe) VALUES(?,?,?,?,?)')->execute(['Laura Bianchi','laura@prof.it',$hash,'professore',null]);
$pdo->exec("INSERT INTO materie(nome,professore_id) VALUES ('Informatica',2),('Matematica',2),('Italiano',NULL),('Inglese',NULL)");
$pdo->exec("INSERT INTO presenze(studente_id,materia_id,data,stato,note) VALUES (1,1,'2026-09-25','presente',NULL),(1,2,'2026-09-24','presente',NULL),(1,3,'2026-09-23','assente','Giustificato'),(1,4,'2026-09-20','presente',NULL)");
$pdo->exec("INSERT INTO voti(studente_id,materia_id,voto,tipo,data) VALUES (1,1,8.0,'Orale','2026-09-15'),(1,2,7.5,'Verifica','2026-09-10'),(1,3,6.5,'Compito','2026-09-18')");
$pdo->exec("INSERT INTO orario(classe,materia_id,giorno_settimana,ora_inizio,ora_fine) VALUES ('5A',1,1,'08:00','09:00'),('5A',2,1,'09:00','10:00'),('5A',4,1,'10:00','11:00'),('5A',3,2,'08:00','09:00'),('5A',1,4,'08:00','09:00')");
$pdo->exec("INSERT INTO verifiche(classe,materia_id,descrizione,data) VALUES ('5A',2,'Analisi matematica','2026-10-01'),('5A',1,'Progetto database','2026-10-04')");
$pdo->exec("INSERT INTO messaggi(mittente_id,destinatario_id,oggetto,corpo,letto) VALUES (2,1,'Compiti per la prossima lezione','Completare gli esercizi di Matematica.',0),(NULL,1,'Avviso segreteria','Il consiglio di classe si terrà il 2 ottobre.',0)");
echo 'Seed completato. Elimina o rinomina questo file. Credenziali: mario@studente.it / password123 e laura@prof.it / password123';
?>