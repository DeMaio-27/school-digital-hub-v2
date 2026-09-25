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
CREATE TABLE IF NOT EXISTS presenze (
 id INT AUTO_INCREMENT PRIMARY KEY,
 studente_id INT NOT NULL,
 data DATE NOT NULL,
 stato ENUM('presente','assente','giustificato') NOT NULL DEFAULT 'assente',
 note TEXT,
 FOREIGN KEY(studente_id) REFERENCES utenti(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- Password: password123 (hash bcrypt generato con PHP password_hash())
INSERT INTO utenti(nome,email,password,ruolo,classe) VALUES
('Mario Rossi','mario@studente.it','$2y$10$8K1p/wGvCqQ7H9jN5rL4tOxY2vZ3mW6nU8sR1qT0uV9wX4yA7bC5d','studente','5A'),
('Laura Bianchi','laura@prof.it','$2y$10$8K1p/wGvCqQ7H9jN5rL4tOxY2vZ3mW6nU8sR1qT0uV9wX4yA7bC5d','professore',NULL);