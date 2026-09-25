# 🚀 Guida all'Installazione - School Digital Hub v2

Questa guida ti accompagna passo-passo nell'installazione del progetto.

---

## ⏱️ Tempo richiesto: 5 minuti

---

## ✅ Prerequisiti

Prima di iniziare, assicurati di avere:

- [ ] **XAMPP** installato ([scarica qui](https://www.apachefriends.org/download.html))
- [ ] Un browser moderno (Chrome, Firefox, Edge)
- [ ] Questa repository scaricata o clonata

---

## 📦 Passo 1: Installa XAMPP

1. Scarica XAMPP dal sito ufficiale
2. Esegui l'installer
3. Installa in `C:\xampp` (Windows) o `/Applications/XAMPP` (Mac)

---

## 📦 Passo 2: Posiziona il progetto

### Su Windows:
1. Copia la cartella `school-digital-hub-v2` in:
   ```
   C:\xampp\htdocs\school-digital-hub-v2
   ```

### Su Mac:
1. Copia la cartella in:
   ```
   /Applications/XAMPP/htdocs/school-digital-hub-v2
   ```

---

## 📦 Passo 3: Avvia XAMPP

1. Apri il **XAMPP Control Panel**
2. Clicca **Start** su **Apache**
3. Clicca **Start** su **MySQL**
4. Entrambi devono essere verdi ✅

---

## 📦 Passo 4: Esegui il Setup Automatico

1. Apri il browser
2. Vai su:
   ```
   http://localhost/school-digital-hub-v2/backend/setup.php
   ```
3. Attendi che il setup completi tutti i passaggi (circa 5 secondi)
4. Vedrai una schermata di conferma con le credenziali

---

## 📦 Passo 5: Accedi al Sistema

1. Clicca su **"🔐 Vai al Login"**
2. Usa una di queste credenziali:

| Ruolo | Email | Password |
|-------|-------|----------|
| 👨‍🎓 Studente | studente@school.com | studente123 |
| 👨‍🎓 Studente 2 | studente2@school.com | studente123 |
| 👨‍🏫 Professore | professore@school.com | professore123 |
| 👨‍🏫 Professore 2 | professore2@school.com | professore123 |
| 👤 Admin | admin@school.com | admin123 |

3. Clicca **Accedi**
4. Benvenuto nella tua dashboard! 🎉

---

## 🎯 Cosa è stato creato

Dopo il setup, avrai:

- ✅ Database `school_hub` con 5 tabelle
- ✅ 5 utenti di test già configurati
- ✅ Dati di esempio (voti, presenze, orario)
- ✅ Sistema di login funzionante

---

## 🐛 Risoluzione Problemi

### "Impossibile connettersi a MySQL"
- ✅ Assicurati che MySQL sia avviato in XAMPP (deve essere verde)
- ✅ Riavvia XAMPP Control Panel

### "Database non trovato"
- ✅ Esegui nuovamente `http://localhost/school-digital-hub-v2/backend/setup.php`

### "Pagina bianca"
- ✅ Controlla `xampp/apache/logs/error.log`
- ✅ Assicurati che PHP sia abilitato in XAMPP

### "Errore 404"
- ✅ Verifica che la cartella sia in `htdocs/school-digital-hub-v2`
- ✅ Controlla l'URL: deve essere esattamente come sopra

---

## 📞 Hai bisogno di aiuto?

Se incontri problemi:

1. Controlla i log di errore di Apache
2. Verifica che tutti i prerequisiti siano soddisfatti
3. Riprova il setup da capo

---

## 🎓 Prossimi passi

Ora che il sistema è installato:

1. 📚 Esplora le diverse dashboard (studente, professore, admin)
2. 🔐 Cambia le password di default
3. 📊 Aggiungi i tuoi dati reali
4. 🎨 Personalizza l'interfaccia

**Buon lavoro!** 🚀
