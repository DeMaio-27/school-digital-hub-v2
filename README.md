# 🏫 School Digital Hub v2 ⚠️WORK IN PROGRESS⚠️

Piattaforma web di digitalizzazione scolastica con sistema di login a ruoli, dashboard personalizzate e backend PHP/MySQL.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)

---

## 🚀 Quick Start (5 minuti)

### 1. Installa XAMPP
Scarica e installa [XAMPP](https://www.apachefriends.org/download.html)

### 2. Copia il progetto
Metti la cartella `school-digital-hub-v2` in:
- **Windows:** `C:\xampp\htdocs\`
- **Mac:** `/Applications/XAMPP/htdocs/`

### 3. Esegui il Setup
1. Avvia **Apache** e **MySQL** su XAMPP
2. Apri: `http://localhost/school-digital-hub-v2/backend/setup.php`
3. Attendi 5 secondi (setup automatico)
4. Clicca **"Vai al Login"**

### 4. Accedi
| Ruolo | Email | Password |
|-------|-------|----------|
| Studente | studente@school.com | studente123 |
| Professore | professore@school.com | professore123 |
| Admin | admin@school.com | admin123 |

**Fatto!** 🎉

---

## 📋 Cosa include

- ✅ **Login sicuro** con password hashate (bcrypt)
- ✅ **3 ruoli:** Studente, Professore, Admin
- ✅ **Dashboard personalizzate** per ogni ruolo
- ✅ **Gestione voti** con medie automatiche
- ✅ **Registro presenze**
- ✅ **Orario scolastico**
- ✅ **Sistema messaggi**
- ✅ **Setup automatico** in 1 click

---

## 🛠️ Tecnologie

| Frontend | Backend | Database |
|----------|---------|----------|
| HTML5 | PHP 8.x | MySQL 8.0 |
| CSS3 | Sessioni PHP | MariaDB |
| JavaScript | PDO | |

**Strumenti:** XAMPP, phpMyAdmin, Git

---

## 📁 Struttura

```
school-digital-hub-v2/
├── backend/
│   ├── config.php       # Configurazione DB
│   ├── setup.php        # Installer automatico ⭐
│   ├── login.php        # Login handler
│   ├── dashboard.php    # Dashboard
│   ├── logout.php       # Logout
│   └── database/
│       ├── schema.sql   # Struttura DB
│       └── seed_users.php
├── assets/              # CSS, JS, immagini
├── index.php            # Homepage
├── login.html           # Pagina login
├── dashboard.html       # Dashboard UI
├── INSTALL.md           # Guida completa
└── README.md            # Questo file
```

---

## 📸 Screenshot

> 💡 Gli screenshot sono placeholder. Sostituisci `assets/images/` con screenshot reali.

---

## 🔐 Sicurezza

- Password hashate con `password_hash()` (bcrypt)
- Prepared statements (anti SQL injection)
- Sessioni PHP con rigenerazione ID
- Validazione input lato server

---

## 📄 License

MIT License - vedi file [LICENSE](LICENSE)

---

## 👨‍💻 Autore

**Federico De Maio**
Studente di Informatica - Roma, Italia

- GitHub: [@DeMaio-27](https://github.com/DeMaio-27)

---

## 📚 Documentazione

- [INSTALL.md](INSTALL.md) - Guida completa all'installazione
- [backend/setup.php](backend/setup.php) - Esegui il setup automatico

---

<p align="center">
  <strong>⭐ Lascia una stella se ti piace!</strong>
</p>
