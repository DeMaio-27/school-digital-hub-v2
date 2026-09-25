CREATE DATABASE IF NOT EXISTS school_hub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school_hub;

CREATE TABLE IF NOT EXISTS utenti (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  ruolo ENUM('studente','professore','genitore','admin') NOT NULL DEFAULT 'studente',
  classe VARCHAR(20),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS materie (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(80) NOT NULL,
  professore_id INT NULL,
  FOREIGN KEY (professore_id) REFERENCES utenti(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS presenze (
  id INT AUTO_INCREMENT PRIMARY KEY,
  studente_id INT NOT NULL,
  materia_id INT NULL,
  data DATE NOT NULL,
  stato ENUM('presente','assente','giustificato') NOT NULL DEFAULT 'assente',
  note TEXT,
  FOREIGN KEY (studente_id) REFERENCES utenti(id) ON DELETE CASCADE,
  FOREIGN KEY (materia_id) REFERENCES materie(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  classe VARCHAR(20) NOT NULL,
  materia_id INT NOT NULL,
  giorno_settimana TINYINT NOT NULL COMMENT '1=Lunedi ... 7=Domenica',
  ora_inizio TIME NOT NULL,
  ora_fine TIME NOT NULL,
  FOREIGN KEY (materia_id) REFERENCES materie(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS voti (
  id INT AUTO_INCREMENT PRIMARY KEY,
  studente_id INT NOT NULL,
  materia_id INT NOT NULL,
  voto DECIMAL(3,1) NOT NULL,
  tipo VARCHAR(50) DEFAULT 'Verifica',
  data DATE NOT NULL,
  FOREIGN KEY (studente_id) REFERENCES utenti(id) ON DELETE CASCADE,
  FOREIGN KEY (materia_id) REFERENCES materie(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS verifiche (
  id INT AUTO_INCREMENT PRIMARY KEY,
  classe VARCHAR(20) NOT NULL,
  materia_id INT NOT NULL,
  descrizione VARCHAR(200) NOT NULL,
  data DATE NOT NULL,
  FOREIGN KEY (materia_id) REFERENCES materie(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS messaggi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  mittente_id INT NULL,
  destinatario_id INT NOT NULL,
  oggetto VARCHAR(150) NOT NULL,
  corpo TEXT,
  letto TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (mittente_id) REFERENCES utenti(id) ON DELETE SET NULL,
  FOREIGN KEY (destinatario_id) REFERENCES utenti(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO utenti(nome,email,password,ruolo,classe) VALUES
('Mario Rossi','mario@studente.it','$2y$10$8K1p/wGvCqQ7H9jN5rL4tOxY2vZ3mW6nU8sR1qT0uV9wX4yA7bC5d','studente','5A'),
('Laura Bianchi','laura@prof.it','$2y$10$8K1p/wGvCqQ7H9jN5rL4tOxY2vZ3mW6nU8sR1qT0uV9wX4yA7bC5d','professore',NULL);

INSERT INTO materie(nome, professore_id) VALUES
('Informatica', 2),('Matematica', 2),('Italiano', NULL),('Inglese', NULL),('Storia', NULL);

INSERT INTO presenze(studente_id, materia_id, data, stato, note) VALUES
(1,1,'2026-09-25','presente',NULL),(1,2,'2026-09-24','presente',NULL),
(1,3,'2026-09-23','assente','Giustificato'),(1,5,'2026-09-23','assente','Giustificato'),
(1,4,'2026-09-20','presente',NULL),(1,1,'2026-09-19','presente',NULL),(1,2,'2026-09-18','presente',NULL);

INSERT INTO orario(classe, materia_id, giorno_settimana, ora_inizio, ora_fine) VALUES
('5A',1,1,'08:00','09:00'),('5A',2,1,'09:00','10:00'),('5A',4,1,'10:00','11:00'),
('5A',3,2,'08:00','09:00'),('5A',5,2,'09:00','10:00'),('5A',2,3,'08:00','09:00'),
('5A',1,4,'08:00','09:00'),('5A',2,5,'08:00','09:00');

INSERT INTO voti(studente_id, materia_id, voto, tipo, data) VALUES
(1,2,7.5,'Verifica','2026-09-10'),(1,1,8.0,'Orale','2026-09-15'),
(1,3,6.5,'Verifica','2026-09-18'),(1,2,8.5,'Compito','2026-09-20');

INSERT INTO verifiche(classe, materia_id, descrizione, data) VALUES
('5A',2,'Analisi matematica','2026-10-01'),('5A',1,'Progetto database','2026-10-04');

INSERT INTO messaggi(mittente_id, destinatario_id, oggetto, corpo, letto) VALUES
(2,1,'Compiti per la prossima lezione','Ricordo di completare gli esercizi di Matematica per lunedi.',0),
(NULL,1,'Avviso segreteria','Il consiglio di classe si terra il 2 ottobre.',0),
(2,1,'Materiale caricato','Ho caricato le slide di Informatica sul portale.',1);
