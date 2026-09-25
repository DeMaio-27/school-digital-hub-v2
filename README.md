# 🏫 School Digital Hub v2

Piattaforma web di digitalizzazione scolastica con sistema di login a ruoli, dashboard personalizzate e backend PHP/MySQL per la gestione di voti, presenze, orario scolastico e comunicazioni.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black)

---

## 📋 Indice

- [Funzionalità](#-funzionalità)
- [Tecnologie](#-tecnologie)
- [Struttura del progetto](#-struttura-del-progetto)
- [Screenshot](#-screenshot)
- [Installazione](#-installazione)
- [Utilizzo](#-utilizzo)
- [Crediti](#-crediti)

---

## ✨ Funzionalità

### Sistema di autenticazione
- Login sicuro con sessioni PHP
- Tre ruoli utente: **Studente**, **Professore**, **Amministratore**
- Dashboard personalizzate in base al ruolo
- Logout con invalidazione sessione

### Dashboard Studente
- Visualizzazione voti per materia
- Registro presenze/assenze
- Orario scolastico settimanale
- Elenco verifiche e scadenze
- Messaggi dai docenti

### Dashboard Professore
- Inserimento e modifica voti
- Registro presenze per classe
- Gestione orario delle lezioni
- Invio messaggi agli studenti
- Programmazione verifiche

### Dashboard Amministratore
- Gestione utenti (CRUD completo)
- Assegnazione ruoli e classi
- Report e statistiche
- Configurazione sistema

---

## 🛠️ Tecnologie

| Frontend | Backend | Database |
|----------|---------|----------|
| HTML5 | PHP 8.x | MySQL 8.0 |
| CSS3 | Sessioni PHP | MariaDB |
| JavaScript | PDO (PHP Data Objects) | |
| Bootstrap 5 | Password hashing (bcrypt) | |

**Strumenti di sviluppo:**
- XAMPP per ambiente di sviluppo locale
- phpMyAdmin per gestione database
- Git per versionamento

---

## 📁 Struttura del progetto

```
school-digital-hub-v2/
├── assets/                 # Risorse statiche
│   ├── css/               # Fogli di stile
│   ├── js/                # Script JavaScript
│   └── images/            # Immagini e logo
│
├── backend/               # Logica server-side
│   ├── config/           # File di configurazione
│   ├── database/         # Script SQL e migrazioni
│   ├── includes/         # Funzioni e utility
│   ├── dashboard.php     # Gestione dashboard
│   ├── db.php            # Connessione database
│   ├── login.php         # Autenticazione
│   ├── logout.php        # Logout
│   ├── messaggi.php      # Sistema messaggistica
│   ├── orario.php        # Orario scolastico
│   ├── presenze.php      # Registro presenze
│   ├── profile.php       # Profilo utente
│   ├── verifiche.php     # Gestione verifiche
│   └── voti.php          # Registro voti
│
├── index.html            # Landing page
├── login.html            # Pagina di login
├── dashboard.html        # Dashboard principale
└── README.md             # Documentazione
```

---

## 📸 Screenshot

### Pagina di Login
![Login](assets/images/login-screenshot.png)
*Interfaccia di accesso con selezione ruolo*

### Dashboard Studente
![Dashboard Studente](assets/images/dashboard-studente.png)
*Panoramica voti, presenze e orario*

### Dashboard Professore
![Dashboard Professore](assets/images/dashboard-professore.png)
*Gestione voti e presenze per classe*

> 💡 **Nota:** Gli screenshot sono placeholder. Sostituisci i file nella cartella `assets/images/` con catture reali dell'applicazione.

---

## 🚀 Installazione

### Prerequisiti
- XAMPP (o WAMP/MAMP) installato
- PHP 8.x o superiore
- MySQL 8.0 / MariaDB
- Browser moderno (Chrome, Firefox, Edge)

### Passaggi

1. **Clona la repository**
   ```bash
   git clone https://github.com/DeMaio-27/school-digital-hub-v2.git
   cd school-digital-hub-v2
   ```

2. **Configura XAMPP**
   - Avvia Apache e MySQL da XAMPP Control Panel
   - Sposta la cartella del progetto in `htdocs/`

3. **Importa il database**
   - Apri phpMyAdmin (`http://localhost/phpmyadmin`)
   - Crea un nuovo database chiamato `school_hub`
   - Importa lo script SQL da `backend/database/schema.sql`

4. **Configura la connessione**
   - Modifica `backend/db.php` con le tue credenziali:
   ```php
   $host = 'localhost';
   $dbname = 'school_hub';
   $username = 'root';
   $password = ''; // La tua password MySQL
   ```

5. **Avvia l'applicazione**
   - Apri il browser e vai su `http://localhost/school-digital-hub-v2/`

---

## 💻 Utilizzo

### Account di test predefiniti

| Ruolo | Username | Password |
|-------|----------|----------|
| Studente | studente@test.it | student123 |
| Professore | prof@test.it | teacher123 |
| Admin | admin@test.it | admin123 |

> ⚠️ **Importante:** Cambia le password di default prima di usare l'applicazione in produzione!

### Navigazione
1. Accedi con le credenziali di test
2. Esplora la dashboard in base al tuo ruolo
3. Prova le diverse funzionalità (voti, presenze, messaggi)

---

## 📝 Note di sviluppo

Questo progetto è stato realizzato come parte del percorso di studi in Informatica presso un istituto tecnico italiano. Dimostra competenze in:

- Sviluppo web full-stack con PHP e MySQL
- Progettazione di database relazionali
- Implementazione di sistemi di autenticazione sicuri
- Creazione di interfacce utente responsive

---

## 🔐 Sicurezza

- Password hashate con `password_hash()` (bcrypt)
- Sessioni PHP con rigenerazione ID
- Prepared statements per prevenire SQL injection
- Validazione input lato server

---

## 📄 License

Questo progetto è open source e disponibile sotto licenza MIT. Vedi il file [LICENSE](LICENSE) per i dettagli.

---

## 👨‍💻 Autore

**Federico De Maio**  
Studente di Informatica - Istituto Tecnico, Roma

- GitHub: [@DeMaio-27](https://github.com/DeMaio-27)
- Portfolio: [Link al portfolio]

---

## 🤝 Contributi

I contributi sono benvenuti! Sentiti libero di:

1. Fare un fork del progetto
2. Creare un branch per la tua feature (`git checkout -b feature/AmazingFeature`)
3. Commit delle modifiche (`git commit -m 'Add some AmazingFeature'`)
4. Push sul branch (`git push origin feature/AmazingFeature`)
5. Aprire una Pull Request

---

## 📧 Contatti

Per domande o collaborazioni: [tua-email@example.com]

---

<p align="center">
  <strong>⭐ Se ti piace questo progetto, lascia una stella su GitHub!</strong>
</p>
