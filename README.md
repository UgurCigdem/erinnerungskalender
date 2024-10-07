
# Erinnerungskalender | API, PHP

### Inhaltsverzeichnis


- [Erinnerungskalender](#erinnerungskalender)
  - [Ziel und Anforderungen](#ziel-und-anforderungen)
  - [Aufbau des Projekts](#aufbau-des-projekts)
  - [Eingesetzte Technologien](#eingesetzte-technologien)
  - [Installation / Deployment](#installation--deployment)
- [API](#api)
  - [Konfiguration](#konfiguration)
  - [API-Übersichtstabelle](#api-übersichtstabelle)
  - [API-Dokumentation für Endpunkt](#api-dokumentation-für-endpunkt)
  - [API Dokumentation 10-12 – Erweiterter Erinnerungskalender (Admin-Zugriff)](#api-dokumentation-10-12--erweiterter-erinnerungskalender-admin-zugriff)

- [WEB](#web)
  - [Konfiguration](#konfiguration-1)
  - [Template](#template)
  - [Weiterentwicklung](#weiterentwicklung)
- [SENDER](#sender)
  - [Konfiguration](#konfiguration-2)
- [Entwicklerwerkzeuge](#entwicklerwerkzeuge)
  - [DB Design und SQL](#db-design-und-sql)
  - [Code-Beispiele](#code-beispiele)
  - [API | Aufbau und Funktionalität](#api--aufbau-und-funktionalität)
  - [WEB | Aufbau und Funktionalätet](#web--aufbau-und-funktionalätet)
  - [SENDER | Aufbau und Funktionalität](#sender--aufbau-und-funktionalität)
  - [SENDER | Aufbau und Funktionalität](#sender--aufbau-und-funktionalität-1)

- [Über die Aufgabe](#über-die-aufgabe)
- [Entwickler](#entwickler)


-----

## Ziel und Anforderungen

**Ziel:** Eine Webseite erstellen, die einen persönlichen Erinnerungskalender integriert. Termine eintragen und Erinnerungsmails zum richtigen Zeitpunkt verschicken. Die Lösung soll AJAX-basierte Bearbeitung und Löschung der Termine ermöglichen. Zusätzlich ein Skript erstellen, das prüft, ob Erinnerungen verschickt werden müssen, und diese automatisch versenden.

### Anforderungen:
- Das Design entsprechend den Vorlagen aus den Dateien 'Screen1.jpg' und 'Screen2.jpg' erstellen.
- Mindestens zwei Menüpunkte integrieren: Einer führt zu einer Seite mit Text und Bild, der andere zum Erinnerungskalender.
- In der Übersicht alle erfassten Termine anzeigen, die über AJAX bearbeitet oder gelöscht werden können.
- Benutzerverwaltung ist optional. Bei der Registrierung die Felder Name, E-Mail und Passwort ausfüllen. 
- Der Login erfolgt über E-Mail und Passwort. Nach dem erfolgreichen Login können die Nutzer ihre Termine bearbeiten.
- Im Erinnerungskalender Termine mit Datum, Bezeichnung und Erinnerungszeitpunkt eintragen. Der Erinnerungszeitpunkt kann 1 Tag, 2 Tage, 4 Tage, 1 Woche oder 2 Wochen vor dem Termin festgelegt werden. Anschließend automatisch eine Erinnerungsmail an die hinterlegte E-Mail-Adresse schicken.
- Ein Hintergrundprogramm, wie ein Cron-Job oder Daemon, regelmäßig ausführen, um zu prüfen, ob bald Termine anstehen, und rechtzeitig Erinnerungen versenden.
- Alle gängigen Bibliotheken und Klassen der verwendeten Sprachen sind erlaubt.

  [Zurück zum Inhaltsverzeichnis](#top)
  
---


## Eingesetzte Technologien

In diesem Projekt werden PHP, MySQL, PHPMailer und JWT eingesetzt, um die wichtigsten Funktionen umzusetzen. PHP steuert die serverseitige Logik, während MySQL die Daten speichert und verwaltet. PHPMailer wird für den zuverlässigen E-Mail-Versand verwendet. JWT sorgt für eine sichere Authentifizierung der Benutzer. Diese Technologien gewährleisten die Effizienz und Sicherheit der Webanwendung.

 [Zurück zum Inhaltsverzeichnis](#top)

<!-- 
## Sicherheitsmaßnahmen 

Um die Anwendung vor häufigen Sicherheitsrisiken zu schützen, sind Maßnahmen gegen SQL-Injection, Cross-Site-Scripting (XSS) und Cross-Site Request Forgery (CSRF) wichtig. Bei SQL-Injection sollte man vorbereitete Statements oder sichere Datenbankabfragen verwenden, um zu verhindern, dass Angreifer schädlichen Code in Datenbankabfragen einschleusen. XSS kann vermieden werden, indem man Benutzereingaben filtert und sicherstellt, dass nur erlaubte Inhalte in Webseiten eingefügt werden. Für den Schutz gegen CSRF sollten spezielle Sicherheits-Tokens (CSRF-Tokens) verwendet werden, um sicherzustellen, dass nur berechtigte Anfragen ausgeführt werden. Diese Maßnahmen helfen, die Anwendung sicher und vor Angriffen geschützt zu halten.
-->


---

## Aufbau des Projekts

Das Projekt besteht aus 3 Teilen:

- `/API/`           – Schnittstelle zur Kommunikation zwischen den Diensten.
- `/WEB/`           – Webbasierte Benutzerschnittstelle mit Registrierung, Anmeldung und Verwaltung der Erinnerungen.
- `/SENDER/`        – Dienst, der Erinnerungen an Benutzer versendet.

Sicherstellen, dass der Webserver so konfiguriert ist, dass nur der Ordner `public/` als Root-Dokument verfügbar ist. Dadurch wird sichergestellt, dass die sensiblen Daten und Konfigurationsdateien, die sich in den Ordnern `private/` und `config/` befinden, nicht öffentlich zugänglich sind.

- `/API/public/`   – Öffentliche Endpunkte und Ressourcen der API.
- `/API/private/`  – Nicht-öffentliche Dateien wie Logik und vertrauliche Daten der API.
- `/API/config/`   – Konfigurationsdateien der API.

- `/WEB/public/`   – Öffentliche Dateien und Ressourcen für die Webanwendung.
- `/WEB/private/`  – Nicht-öffentliche Dateien wie Logik und vertrauliche Daten.
- `/WEB/config/`   – Konfigurationsdateien der Webanwendung.

- `/SENDER/public/`  – Öffentliche Dateien und Ressourcen für den Sendeprozess.
- `/SENDER/private/` – Nicht-öffentliche Dateien wie Logik und vertrauliche Daten.
- `/SENDER/config/`  – Konfigurationsdateien des Sendeprozesses.

Das Projekt ist mit PHP und MySQL umgesetzt.

 [Zurück zum Inhaltsverzeichnis](#top)


---

## Installation / Deployment

Das ist eine detaillierte Anleitung für die Installation und umfasst die wichtigsten Schritte, einschließlich der Datenbankeinrichtung, Veröffentlichung, Konfiguration, API-Tests, Mail-Sender und Cron-Job-Einrichtung.

### 1. Datenbank einrichten
- SQL Create Script ausführen. Das SQL-Skript befindet sich unter:  
  `/API/private/sql/create_script.sql`
- Dummy-Daten hinzufügen.  
- 
  (Das SQL-Skript befindet sich außerdem im Abschnitt 'Entwicklerwerkzeuge' dieser Dokumentation.)

![DB Design](/_img/db.png)

  
### 2. API und WEB-Seite veröffentlichen
- Die API und Web-Seite auf den Webserver hochladen und den "public"-Ordner als öffentliches Verzeichnis nutzen. Subdomains einrichten für `/API/`, `/WEB/` und optional `/SENDER/`.

### 3. Konfiguration
Das Projekt besteht aus 3 Teilen. Jeder Teil hat seine eigene `config.php`-Datei.
- `config.sample.php` in jedem Bereich in `config.php` umbenennen oder kopieren und die Datenbankzugangsdaten eintragen.
- DB-Zugangsdaten in den einzelnen Config-Dateien eintragen und speichern. Dateipfade:
  - `/API/private/config/config.php`
  - `/WEB/private/config/config.php`
  - `/SENDER/private/config/config.php`

- Auf Linux-Servern sollten die Berechtigungen für die `config.php`-Dateien mit `chmod 600` gesetzt werden, damit nur der Webserver darauf zugreifen kann.

### 4. Mail-Sender
- Den Mailsender lokal oder über Subdomain testen:
  - Lokal:  
    `php ..../SENDER/public/testMail.php`
  - Subdomain:  
    `http://localhost:9090/testMail.php`
   
![Testmail](/_img/testmail.png)

- Die E-Mail sollte an die in der `config.php`-Datei angegebene Adresse gesendet werden:  
  `/SENDER/private/config/config.php`

### 5. SENDER-Script / Cron-Job Einrichten
- SENDER-Skript zum Server hochladen und einen Cron-Job oder Daemon einrichten.  
  Pfad zum Skript: `/SENDER/`

- PHP-Skript testen (vollständigen Pfad angeben):  
  `php ..../SENDER/public/erinnerungenSenden.php`

- Cron-Job einrichten:
  ```bash
  crontab -e
  ```
  Skript täglich z.B. um 9:00 Uhr ausführen und die Ausgabe in eine Log-Datei schreiben:
  ```bash
  0 9 * * * /usr/bin/php ..../SENDER/public/erinnerungenSenden.php >> ..../SENDER/private/log/erinnerungen.log 2>&1
  ```

[Zurück zum Inhaltsverzeichnis](#top)


# API

## Konfiguration

Um die API richtig zu konfigurieren, musst die **config.sample.php** nach **config.php** kopiert und die entsprechenden Einstellungen in der Datei angepasst werden. Der Pfad zur Konfigurationsdatei lautet:


Die config.php enthält wichtige Parameter für die Datenbankverbindung, Token-Einstellungen und den Pfad für Log-Dateien.

```php
$configArray = [
    // ------- DB Zugangsdaten ------- 
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'dbErinnerung', 
    'DB_USER' => 'root',
    'DB_PASSWORD' => '',
    //-------- Token -------
    'TOKEN_BASE_URL' => 'http://localhost', // URL für JWT
    'TOKEN_KEY' => 'geheimesSchluessel',
    //-------- Logg-Pfad -------
    'LOG_PATH' => 'logs', // Relativer Pfad für Log-Dateien | /private/logs/
];
```

[Zurück zum Inhaltsverzeichnis](#top)

---

## API-Übersichtstabelle

### Registrierung
| Methode | URL                  | Beschreibung                            | Authentifizierung |
|---------|----------------------|------------------------------------------|-------------------|
| POST    | /api/register.php     | Einen neuen Benutzer registrieren        | Keine für Login   |

### Login
| Methode | URL                  | Beschreibung                            | Authentifizierung |
|---------|----------------------|------------------------------------------|-------------------|
| POST    | /api/login.php        | Benutzer-Login und Token erhalten        | Keine (offener Endpunkt) |

### Benutzer
| Methode | URL                              | Beschreibung                          | Authentifizierung |
|---------|----------------------------------|----------------------------------------|-------------------|
| GET     | /api/benutzer.php?id={id}        | Einen Benutzer per ID abrufen          | JWT               |
| GET     | /api/benutzer.php?email={email}  | Einen Benutzer per E-Mail abrufen      | JWT               |
| GET     | /api/benutzer.php                | Alle Benutzer abrufen                  | JWT               |
| POST    | /api/benutzer.php                | Einen neuen Benutzer erstellen         | JWT               |
| PUT     | /api/benutzer.php?id={id}        | Einen Benutzer per ID aktualisieren    | JWT               |
| DELETE  | /api/benutzer.php?id={id}        | Einen Benutzer per ID löschen          | JWT               |

### Erinnerung
| Methode | URL                                         | Beschreibung                         | Authentifizierung |
|---------|---------------------------------------------|---------------------------------------|-------------------|
| GET     | /api/erinnerung.php?id={id}                 | Eine Erinnerung per ID abrufen        | JWT               |
| GET     | /api/erinnerung.php?BenutzerId={BenutzerId} | Alle Erinnerungen abrufen             | JWT               |
| GET     | /api/erinnerung.php                         | Alle Erinnerungen abrufen             | JWT               |
| POST    | /api/erinnerung.php                         | Eine neue Erinnerung erstellen        | JWT               |
| PUT     | /api/erinnerung.php?id={id}                 | Eine Erinnerung per ID aktualisieren  | JWT               |
| DELETE  | /api/erinnerung.php?id={id}                 | Eine Erinnerung per ID löschen        | JWT               |

### Erinnerung2 (Admin)
| Methode | URL                                   | Beschreibung                           | Authentifizierung  |
|---------|---------------------------------------|-----------------------------------------|--------------------|
| GET     | /api/erinnerung2.php?date={date}      | Erinnerungen für ein bestimmtes Datum   | JWT (Admin)        |
| GET     | /api/erinnerung2.php                  | Alle Erinnerungen abrufen               | JWT (Admin)        |
| PUT     | /api/erinnerung2.php                  | Eine Erinnerung aktualisieren           | JWT (Admin)        |


[Zurück zum Inhaltsverzeichnis](#top)

---

## API-Dokumentation für Endpunkt

Diese Dokumentation beschreibt die API-Schnittstellen für Benutzerverwaltung und Erinnerungskalender. Benutzer können registriert, authentifiziert und ihre Erinnerungen verwaltet werden. Die Authentifizierung erfolgt über JWT (JSON Web Token).

`/API/public/index.html`

### 1. Benutzer Registrierung
- URL: `/api/register.php`
- Methode: POST
- Beschreibung: Einen neuen Benutzer registrieren.
- Request-Body:

```json
{
  "Name": "Max Mustermann",
  "Email": "max@example.com",
  "Passwort": "geheimespasswort"
}
```

- Antwort (bei Erfolg):

```json
{
  "message": "Registrierung erfolgreich!",
  "id": 1
}
```

### 2. Benutzer Login
- URL: `/api/login.php`
- Methode: POST
- Beschreibung: Erlaubt es, sich als Benutzer anzumelden und ein JWT-Token zu erhalten.
- Request-Body:

```json
{
  "Email": "max@muster.at",
  "Passwort": "geheimespasswort"
}
```

- Antwort (bei Erfolg):

```json
{
  "message": "Login erfolgreich!",
  "id": 1,
  "name": "Max Mustermann",
  "email": "max@muster.at",
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

### 3. Benutzer abrufen
- URL: `/api/benutzer.php?id={id}`
- Methode: GET
- Beschreibung: Ruft einen Benutzer basierend auf der ID ab.
- Anfrage-Header: Authorization: Bearer <JWT-Token>
- Antwort (bei Erfolg):

```json
{
  "id": 1,
  "Name": "Max Mustermann",
  "Email": "max@muster.at"
}
```



### 4. **Benutzer aktualisieren**  
- URL: `/api/benutzer.php?id={id}`  
- Methode: PUT  
- Beschreibung: Aktualisiert die Informationen eines Benutzers.  
- Anfrage-Header: Authorization: Bearer <JWT-Token>
- Request-Body:

```json
{
  "Name": "Max Neuer",
  "Email": "max.mustermann@muster.at",
  "Passwort": "neuespasswort"
}
```

- Antwort (bei Erfolg):

```json
{
  "message": "Benutzer aktualisiert"
}
```


### 5. **Benutzer löschen**  
- URL: `/api/benutzer.php?id={id}`  
- Methode: DELETE  
- Beschreibung: Löscht einen Benutzer.  
- Anfrage-Header: Authorization: Bearer <JWT-Token>


- Antwort (bei Erfolg):

```json
{
  "message": "Benutzer gelöscht"
}
```

### 6. **Erinnerungen anzeigen**  
- URL: `/api/erinnerung.php?BenutzerId={BenutzerId}`  
- Methode: GET  
- Beschreibung: Liefert alle Erinnerungen eines Benutzers.  
- Anfrage-Header: Authorization: Bearer <JWT-Token>


- Antwort (bei Erfolg):

```json
[
  {
    "id": 1,
    "Termin": "2024-09-27",
    "Bezeichnung": "Arzttermin"
  },
  {
    "id": 2,
    "Termin": "2024-09-28",
    "Bezeichnung": "Meeting"
  }
]

```

### 7. **Erinnerung erstellen**  
- URL: `/api/erinnerung.php`  
- Methode: POST  
- Beschreibung: Erstellt eine neue Erinnerung für den Benutzer.  
- Anfrage-Header: Authorization: Bearer <JWT-Token>
- Request-Body:

```json
{
  "BenutzerId": 1,
  "Termin": "2024-10-10",
  "Bezeichnung": "Geburtstagserinnerung",
  "InTage": 7
}
```

- Antwort (bei Erfolg):

```json
{
  "message": "Erinnerung erstellt",
  "id": 1
}
```

### 8. **Erinnerung bearbeiten**  
- URL: `/api/erinnerung.php?id={id}`  
- Methode: PUT  
- Beschreibung: Aktualisiert eine bestehende Erinnerung.  
- Anfrage-Header: Authorization: Bearer <JWT-Token>
- Request-Body:

```json
{
  "Termin": "2024-10-12",
  "Bezeichnung": "Geänderter Termin",
  "InTage": 5
}
```

- Antwort (bei Erfolg):

```json
{
  "message": "Erinnerung aktualisiert"
}
```

### 9. **Erinnerung löschen**  
- URL: `/api/erinnerung.php?id={id}`  
- Methode: DELETE  
- Beschreibung: Löscht eine Erinnerung.  
- Anfrage-Header: Authorization: Bearer <JWT-Token>

- Antwort (bei Erfolg):

```json
{
  "message": "Erinnerung gelöscht"
}
```


[Zurück zum Inhaltsverzeichnis](#top)

---


## API Dokumentation 10-12 – Erweiterter Erinnerungskalender (Admin-Zugriff)

Diese API-Dokumentation beschreibt die Schnittstellen für das Verwaltungssystem des Erinnerungskalenders, das ausschließlich von Administratoren genutzt werden darf. Die Authentifizierung erfolgt über JWT (JSON Web Token). Nur Benutzer mit der Admin-Rolle haben Zugriff.

### 10. Erinnerungen für ein bestimmtes Datum anzeigen (Admin)
- URL: `/api/erinnerung2.php?date={date}`
- Methode: GET
- Beschreibung: Liefert alle Erinnerungen für ein bestimmtes Datum. Nur Administratoren können diesen Endpunkt verwenden.
- Anfrage-Header: Authorization: Bearer <JWT-Token>
- Antwort (bei Erfolg):

```json
[
    {
        "ErinnerungId": 2,
        "BenutzerId": 1,
        "Von": "2024-11-01",
        "Bis": "2024-11-15",
        "Bezeichnung": "Arzttermin",
        "Name": "Test User",
        "Email": "testuser@example.com"
    },
    {
        "ErinnerungId": 9,
        "BenutzerId": 1,
        "Von": "2024-11-05",
        "Bis": "2024-11-07",
        "Bezeichnung": "Elternabend in der Schule",
        "Name": "Test User",
        "Email": "testuser@example.com"
    }
]
```

### 11. Alle Erinnerungen anzeigen (Admin)
- URL: `/api/erinnerung2.php`
- Methode: GET
- Beschreibung: Liefert alle gespeicherten Erinnerungen. Nur Administratoren können diesen Endpunkt verwenden.
- Anfrage-Header: Authorization: Bearer <JWT-Token>
- Antwort (bei Erfolg):

```json
[
    {
        "ErinnerungId": 2,
        "BenutzerId": 1,
        "Von": "2024-11-01",
        "Bis": "2024-11-15",
        "Bezeichnung": "Arzttermin",
        "Name": "Test User",
        "Email": "testuser@example.com"
    },
    {
        "ErinnerungId": 9,
        "BenutzerId": 1,
        "Von": "2024-11-05",
        "Bis": "2024-11-07",
        "Bezeichnung": "Elternabend in der Schule",
        "Name": "Test User",
        "Email": "testuser@example.com"
    }
]
```

### 12. Erinnerung aktualisieren (Admin)
- URL: `/api/erinnerung2.php`
- Methode: PUT
- Beschreibung: Aktualisiert eine bestehende Erinnerung basierend auf der ID. Nur Administratoren können diesen Endpunkt verwenden.
- Anfrage-Header: Authorization: Bearer <JWT-Token>
- Request-Body:

```json
{
  "ErinnerungId": 1,
  "Termin": "2024-10-12",
  "Bezeichnung": "Geänderter Termin",
  "InTage": 5
}
```

- Antwort (bei Erfolg):

```json
{
  "message": "Erinnerung aktualisiert"
}
```



# WEB

## Konfiguration

Um die WEB-Anwendung richtig zu konfigurieren, muss die config.sample.php nach config.php kopiert und die entsprechenden Einstellungen in der Datei angepasst werden. Der Pfad zur Konfigurationsdatei lautet:

`/WEB/private/config/config.php`

Die config.php enthält wichtige Parameter für die API-Verbindung, SMTP-Zugangsdaten, den Mail-Versand sowie den Pfad für Log-Dateien. Diese Einstellungen umfassen unter anderem den Zugriff auf die API, die Konfiguration des E-Mail-Versands über einen SMTP-Server und die Verwaltung der JWT-Token.

```php
$configArray = [
    // ------- API Zugangsdaten ------- 
    'API_BASE_URL' => 'http://localhost:5001/api/',
    'API_EMAIL' => 'testuser@example.com', // Benutzer mit admin Rechte
    'API_PASSWORD' => 'meinPasswort',
    // ------- SMTP Zugangsdaten ------- 
    'SMTP_HOST' => 'smtp.services.com',
    'SMTP_USERNAME' => 'office@services.com',
    'SMTP_PASSWORD' => '***',
    'SMTP_PORT' => 587,
    //------- Mail-Sender ------- 
    'ABSENDER_NAME' => 'Ihr Erinnerung-Service', // Name für die E-Mails
    'ABSENDER_EMAIL' => 'newapiservices@services.com',
    'EMPFAENGER_EMAIL' => 'newapiservices@services.com', // nur für Testmail
    'ERINNERUNG_BETREFF' => 'Erinnerung für ein Ereignis', 
    //-------- Logg-Pfad -------
    'LOG_PATH' => 'logs', // Relativer Pfad für Log-Dateien | /private/logs/
    //-------- Token -------
    'TOKEN_BASE_URL' => 'http://localhost', // URL für JWT
    'TOKEN_KEY' => 'geheimesSchluessel',
];
```

[Zurück zum Inhaltsverzeichnis](#top)

---

## Template

### Template Struktur

Das Projekt verwendet ein Template, das sich im folgenden Pfad befindet:

`\WEB\private\templates\temp3\template.php`

Diese PHP-Datei dient als Haupt-Template für die Web-Oberfläche. Sie wird verwendet, um die allgemeine Struktur der Seite zu definieren, einschließlich der Header-, Footer- und Inhaltsbereiche.

### Unterordner für zusätzliche Dateien

Das Template wird durch zusätzliche Ressourcen wie CSS, JavaScript und Bilder unterstützt. Diese Dateien sind in den folgenden Ordnern organisiert:

- **CSS-Ordner:** `\WEB\public\temp3\css\`
  - CSS-Dateien werden aus dem \css\-Ordner in die template.php eingebunden, um das Aussehen der Webseite zu steuern.
  
- **JavaScript-Ordner:** `\WEB\public\temp3\js\`
  - JavaScript-Dateien werden aus dem \js\-Ordner eingebunden, um dynamische Funktionen wie Formulare, interaktive Elemente oder API-Aufrufe zu unterstützen.
  
- **Bilder-Ordner:** `\WEB\public\temp3\img\`
  - Die im Template verwendeten Bilder werden aus dem \img\-Ordner geladen, um visuelle Elemente wie Logos bereitzustellen.

[Zurück zum Inhaltsverzeichnis](#top)

---

## Weiterentwicklung

Eine PHP-Seite mit folgendem Inhalt erstellen:

```php
<?php require __DIR__ . '/_start.php'; ?>
   
    <script src="…"></script>
    <link href="…" rel="stylesheet">
   
<?php require __DIR__ . '/_head.php'; ?>

<?php $title = "Home";  ?>
<p>…</p>
<script>…</script>

<?php require __DIR__ . '/_end.php'; ?>
```

Auf jeder Seite eine einheitliche Struktur verwenden. 
Die Dateien `_start.php`, `_head.php` und `_end.php` sorgen für eine klare Trennung der Grundfunktionen. `_start.php` lädt notwendige Initialisierungen, `_head.php` enthält Meta-Informationen und den Seitentitel, während `_end.php` die Seite abschließt und abschließende Skripte einbindet. Diese Struktur gewährleistet Konsistenz und Wiederverwendbarkeit im gesamten Projekt.



# SENDER

## Konfiguration

Um den SENDER richtig zu konfigurieren, muss die config.sample.php nach config.php kopiert und die entsprechenden Einstellungen in der Datei angepasst werden. Der Pfad zur Konfigurationsdatei lautet:

`/SENDER/private/config/config.php`

Die config.php enthält wichtige Parameter für die API-Verbindung, SMTP-Zugangsdaten und den Pfad für Log-Dateien. Die Einstellungen betreffen unter anderem den Zugriff auf die API, den E-Mail-Versand über einen SMTP-Server und den Absender sowie Empfänger für Testmails.

```php
$configArray = [
  // ------- API Zugangsdaten ------- 
  'API_BASE_URL' => 'http://localhost:5001/api/',
  'API_EMAIL' => 'testuser@example.com',
  'API_PASSWORD' => 'meinPasswort',
  // ------- SMTP Zugangsdaten -------
  'SMTP_HOST' => 'smtp.muster.at',
  'SMTP_USERNAME' => 'max@muster.at',
  'SMTP_PASSWORD' => 'maxMustermann',
  'SMTP_PORT' => 587,
  //------- Mail-Sender ------- 
  'ABSENDER_NAME' => 'Ihr Erinnerung-Service', // Name für die E-Mails
  'ABSENDER_EMAIL' => 'newapiservices@ugur.at',
  'EMPFAENGER_EMAIL' => 'newapiservices@ugur.at', // nur für Testmail
  'ERINNERUNG_BETREFF' => 'Erinnerung für ein Ereignis', 
  //'ERINNERUNG_DATUM' => '2024-11-05', // YYYY-MM-DD nur zum Testen
  //-------- Logg-Pfad -------
  'LOG_PATH' => 'logs', // Relativer Pfad für Log-Dateien | /private/logs/
];
```

[Zurück zum Inhaltsverzeichnis](#top)

# Entwicklerwerkzeuge

## DB Design und SQL 

### Script zur Erstellung der Datenbank, Tabellen und Dummy-Daten
`/API/private/sql/create_script.sql`

```sql
-- DB Erstellen
CREATE DATABASE dbErinnerung;

USE dbErinnerung;

-- Tabelle Benutzer erstellen
CREATE TABLE benutzer (
    BenutzerId INT AUTO_INCREMENT,
    Name TEXT NOT NULL,
    Email TEXT NOT NULL UNIQUE,
    Passwort TEXT NOT NULL,
    Salt TEXT NOT NULL,  
    Rolle ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    CONSTRAINT pk_benutzer_id PRIMARY KEY (BenutzerId)
);

-- Tabelle Erinnerung erstellen
CREATE TABLE erinnerung (
    ErinnerungId INT AUTO_INCREMENT,
    BenutzerId INT NOT NULL,
    Termin Date NOT NULL,
    InTage INT,
    Bezeichnung TEXT NOT NULL, 
    CONSTRAINT pk_erinnerung_id PRIMARY KEY (ErinnerungId),
    CONSTRAINT fk_benutzer_id FOREIGN KEY (BenutzerId) REFERENCES benutzer(BenutzerId) ON DELETE CASCADE
);

CREATE OR REPLACE VIEW erinnerung2 AS
SELECT 
    e.ErinnerungId,      
    e.BenutzerId,
    DATE_SUB(e.Termin, INTERVAL e.InTage DAY) AS Von,
    e.Termin AS Bis,
    e.Bezeichnung,
    b.Name,
    b.Email
FROM erinnerung e
JOIN benutzer b ON e.BenutzerId = b.BenutzerId;
```

[Zurück zum Inhaltsverzeichnis](#top)

---

### Dummy Daten für die Tabelle Erinnerung

```sql
INSERT INTO erinnerung (BenutzerId, Termin, InTage, Bezeichnung) VALUES
(1, '2024-11-01', 2, 'Geburtstag eines Freundes'),
(1, '2024-11-15', 14, 'Arzttermin'),
(1, '2024-12-01', 4, 'Weihnachtseinkäufe'),
(1, '2024-12-24', 1, 'Heiligabend'),
(1, '2024-12-31', 7, 'Silvesterparty'),
(1, '2025-01-05', 14, 'Neujahrsbesuch bei Verwandten'),
(1, '2025-01-20', 4, 'Jahrestag Hochzeit'),
(1, '2025-02-01', 7, 'Geburtstag eines Familienmitglieds'),
(1, '2024-11-07', 2, 'Elternabend in der Schule'),
(1, '2024-11-20', 4, 'Projektdeadline auf der Arbeit'),
(1, '2024-11-27', 1, 'Sportwettkampf'),
(1, '2024-12-06', 7, 'Nikolausfeier'),
(1, '2024-12-18', 2, 'Weihnachtsfeier im Büro'),
(1, '2024-12-30', 14, 'Vorbereitung für Silvesterparty'),
(1, '2025-01-10', 4, 'Zahnarzttermin'),
(1, '2025-01-25', 2, 'Treffen mit alten Schulfreunden'),
(1, '2025-01-30', 2, 'Konzertbesuch'),
(1, '2025-02-01', 1, 'Urlaubsplanung für Sommer'),
(1, '2024-11-10', 4, 'Besuch beim Tierarzt'),
(1, '2024-11-22', 4, 'Business-Meeting mit Kunden'),
(1, '2024-12-02', 1, 'Weihnachtsgeschenke kaufen'),
(1, '2024-12-09', 7, 'Jahresabschluss in der Firma'),
(1, '2024-12-20', 2, 'Kinobesuch mit Familie'),
(1, '2024-12-27', 4, 'Winterurlaub planen'),
(1, '2025-01-12', 4, 'Neujahrskonzert besuchen'),
(1, '2025-01-15', 2, 'Erste Hilfe Auffrischungskurs'),
(1, '2025-01-22', 7, 'Grillabend mit Freunden'),
(1, '2025-01-28', 1, 'Fahrsicherheitstraining');
```

Die Dummy-Daten, vor allem die Termine um den Jahreswechsel, wurden gezielt gewählt, um die Systemfunktionen in einer realistischen Umgebung zu testen. Sie stellen sicher, dass das System auch Termine über das Jahresende hinaus korrekt verarbeitet und Erinnerungen zuverlässig sendet. Diese Daten dienen nur zu Testzwecken und sollten nicht in die Produktionsumgebung übernommen werden. Sie helfen Entwicklern und Testern, die Logik für Erinnerungsberechnungen und den E-Mail-Versand für verschiedene Zeiträume zu überprüfen.

[Zurück zum Inhaltsverzeichnis](#top)

---


## Code-Beispiele
**WICHTIG!** Ein JWT-Token wird aus dem **localStorage** verwendet, um die API zu authentifizieren.

```javascript
let erinnerungCounter = 0; // Zählt die Erinnerungen
let aktuelleErinnerungId = null; // Speichert die ID für Bearbeitung

let token = localStorage.getItem('jwtToken');  // Token aus localStorage
let BenutzerId = localStorage.getItem('id');   // Benutzer-ID aus localStorage

let apiBaseURL = localStorage.getItem('apiBaseURL');
let apiErinnerungUrl = apiBaseURL + '/erinnerung.php';  // URL für die Erinnerungs-API

``` 


### Login-Funktionalität (POST-Anfrage) JavaScript
Hier wird die API mit einer POST-Anfrage angesprochen, um die Anmeldedaten (E-Mail und Passwort) zu übermitteln.

```javascript
function einloggen(){
    let apiBaseURL = localStorage.getItem('apiBaseURL');
    let apiLoginUrl = apiBaseURL + '/login.php';

    let email = $('#email').val();
    let passwort = $('#passwort').val();

    $.ajax({
        type: 'POST',
        url: apiLoginUrl,
        contentType: 'application/json',
        data: JSON.stringify({ 
            Email: email, 
            Passwort: passwort 
        }),
        success: function(response) {
            localStorage.setItem('token', response.token);
            alert('Login erfolgreich');
        },
        error: function() {
            alert('Login fehlgeschlagen');
        }
    });
}
```

### Registrierung (POST-Anfrage) JavaScript
```javascript
$('#registerForm').submit(function(event) {
    event.preventDefault();  // Verhindert, dass das Formular standardmäßig abgeschickt wird

    let apiRegisterUrl = localStorage.getItem('apiBaseURL') + '/register.php';

    let name = $('#name').val();
    let email = $('#email').val();
    let passwort = $('#passwort').val();

    $.ajax({
        type: 'POST',
        url: apiRegisterUrl,
        contentType: 'application/json',
        data: JSON.stringify({
            Name: name,
            Email: email,
            Passwort: passwort
        }),
        success: function(response) {
            $('#registerResponse').html(response.message);
            localStorage.removeItem('token');
            alert('Registrierung erfolgreich');
        },
        error: function() {
            alert('Registrierung fehlgeschlagen');
        }
    });
});
```
---

### Erinnerungen laden (GET-Anfrage) JavaScript

```javascript
function ladeErinnerungen() {
    $.ajax({
        type: 'GET',
        url: apiErinnerungUrl + '?benutzerId=' + BenutzerId,  // URL für Erinnerungs-API mit Benutzer-ID
        headers: { 'Authorization': 'Bearer ' + token },  // JWT-Token zur Autorisierung
        success: function(response) {
            // Annahme: Die API gibt eine Liste von Erinnerungen zurück
            let erinnerungen = response.erinnerungen;
            erinnerungen.forEach(function(erinnerung) {
                // Logik zum Anzeigen der Erinnerungen
                $('#erinnerungenListe').append(
                    '<li>' + erinnerung.text + ' am ' + erinnerung.datum + '</li>'
                );
            });
        },
        error: function() {
            alert('Fehler beim Laden der Erinnerungen');
        }
    });
}
```

---

### Erstellen einer neuen Erinnerung (POST-Anfrage) JavaScript
Die Datei erinnerungen.js enthält die Logik zum Verwalten von Erinnerungen, wie z. B. das Speichern, Bearbeiten und Abrufen von Erinnerungen. 

```javascript
function erstelleErinnerung() {
    let erinnerungText = $('#erinnerung-text').val();
    let erinnerungDatum = $('#erinnerung-datum').val();

    $.ajax({
        type: 'POST',
        url: apiErinnerungUrl,
        headers: { 'Authorization': 'Bearer ' + token },  // JWT-Token zur Autorisierung
        contentType: 'application/json',
        data: JSON.stringify({
            text: erinnerungText,
            datum: erinnerungDatum,
            benutzerId: BenutzerId
        }),
        success: function(response) {
            alert('Erinnerung erfolgreich erstellt');
            erinnerungCounter++;
            // Logik zum Anzeigen der neuen Erinnerung
        },
        error: function() {
            alert('Erinnerung konnte nicht erstellt werden');
        }
    });
}
```

[Zurück zum Inhaltsverzeichnis](#top)

---

## API | Aufbau und Funktionalität

### `API/private/sql/create_script.sql`
Diese Datei enthält ein SQL-Skript zum Erstellen der notwendigen Tabellen in der Datenbank. Es wird bei der Einrichtung des Systems verwendet.

### `API/private/classes/authMiddleware.php`
Diese Datei prüft, ob der Benutzer authentifiziert ist, bevor er auf bestimmte API-Endpunkte zugreifen darf. Sie schützt die API vor unbefugtem Zugriff.

### `API/private/classes/corsMiddleware.php`
Diese Datei kümmert sich um die Cross-Origin Resource Sharing (CORS) Einstellungen. Sie erlaubt der API, Anfragen von anderen Domains zu akzeptieren.

### `API/private/models/Erinnerung.php`
Diese Datei stellt das Datenmodell für Erinnerungen dar. Sie beschreibt, wie die Daten einer Erinnerung in der Datenbank gespeichert und verarbeitet werden.

### `API/private/models/Erinnerung2.php`
Diese Datei ist eine erweiterte Version des Erinnerung-Modells. Sie enthält zusätzliche Felder für die Erinnerungen.

### `API/private/services/ErinnerungService.php`
Diese Datei verwaltet die Geschäftslogik für Erinnerungen. Sie enthält Funktionen zum Erstellen, Bearbeiten und Löschen von Erinnerungen.

### `API/private/services/Erinnerung2Service.php`
Diese Datei ist eine erweiterte Version des ErinnerungService und bietet zusätzliche Funktionen für die Verwaltung spezieller Erinnerungen.

### `API/private/services/LoggerService.php`
Diese Datei protokolliert wichtige Ereignisse und Fehler in Log-Dateien. Sie hilft dabei, Probleme im System zu überwachen und zu diagnostizieren.

### `API/public/api/erinnerung.php`
Diese API-Datei bietet Endpunkte für das Verwalten von Erinnerungen. Sie verarbeitet Anfragen zum Hinzufügen, Bearbeiten und Löschen von Erinnerungen.

### `API/public/api/erinnerung2.php`
Diese API-Datei ist eine erweiterte Version der Erinnerung-API und bietet zusätzliche Funktionen für die Erinnerungen.

### `API/public/api/login.php`
Diese Datei ermöglicht es Benutzern, sich über die API anzumelden. Sie verarbeitet die Login-Daten und gibt ein Authentifizierungstoken zurück.

### `API/public/api/register.php`
Diese Datei erlaubt es, neue Benutzer zu registrieren. Sie nimmt die Benutzerdaten entgegen und erstellt einen neuen Benutzer in der Datenbank.

[Zurück zum Inhaltsverzeichnis](#top)

---


## WEB | Aufbau und Funktionalätet

### `WEB/private/classes/authMiddleware.php`
Diese Datei sorgt für die Authentifizierung von Benutzern. Sie überprüft, ob ein Benutzer eingeloggt ist und ob er die notwendigen Rechte für den Zugriff auf bestimmte Bereiche hat.

### `WEB/private/classes/corsMiddleware.php`
Diese Datei verwaltet die Cross-Origin Resource Sharing (CORS) Einstellungen. Sie sorgt dafür, dass die Web-Anwendung auch von anderen Domains auf die API zugreifen kann.

### `WEB/private/services/LoggerService.php`
Diese Datei bietet eine Logging-Funktion an. Sie speichert wichtige Informationen und Fehler in einer Log-Datei, damit Probleme einfacher analysiert und behoben werden können.

### `WEB/private/sql/create_script.sql`
Diese Datei enthält das SQL-Skript, das die Datenbanktabellen für die Web-Anwendung erstellt. Sie wird bei der Einrichtung der Datenbank ausgeführt.

### `WEB/private/templates/temp3/template.php`
Diese Datei ist ein Template, das das Grundlayout der Webseite enthält. Hier werden der Kopfbereich, der Inhalt und der Footer der Seite definiert, die von anderen Seiten verwendet werden.

### `WEB/public/erinnerungen.php`
Diese Seite zeigt eine Übersicht über die Erinnerungen eines Benutzers. Sie nutzt die API, um die Erinnerungen zu laden, und zeigt sie im Browser an.

### `WEB/public/index.php`
Dies ist die Startseite der Anwendung. Sie bietet dem Benutzer eine Übersicht und Zugang zu verschiedenen Funktionen, wie dem Anmelden oder dem Anzeigen von Erinnerungen.

### `WEB/public/login.php`
Diese Datei verwaltet den Login-Prozess. Sie zeigt das Login-Formular an und sendet die Benutzerdaten zur Authentifizierung an die API.

### `WEB/public/logout.php`
Diese Datei ermöglicht es dem Benutzer, sich abzumelden. Sie löscht die Benutzersitzung.

### `WEB/public/registrieren.php`
Diese Datei bietet ein Formular für die Benutzerregistrierung an. Nach dem Ausfüllen werden die Daten an die API gesendet, um einen neuen Benutzer zu erstellen.

### `WEB/public/_start.php`, `WEB/public/_head.php`, `WEB/public/_end.php`
Diese Dateien enthalten wiederverwendbare Teile der Webseite. _start.php lädt wichtige Skripte, _head.php enthält den HTML-Kopfbereich, und _end.php schließt die HTML-Dateien ab.

### `WEB/public/css/erinnerung.css`
Diese Datei enthält die CSS-Stile für die Erinnerungsseite. Sie sorgt für das Layout und Design der Erinnerungsansicht.

### `WEB/public/js/erinnerungen.js`
Diese Datei enthält das JavaScript für die Erinnerungsseite. Sie verwaltet das Laden und Bearbeiten der Erinnerungen über die API.

[Zurück zum Inhaltsverzeichnis](#top)

---

## SENDER | Aufbau und Funktionalität

### `SENDER/public/erinnerungenSenden.php`
Dieses Skript verschickt Erinnerungs-E-Mails automatisch. Es wird oft über einen Cron-Job ausgeführt und sorgt dafür, dass Nutzer rechtzeitig an ihre Termine erinnert werden.

## SENDER | Aufbau und Funktionalität

### `SENDER/private/config/config.php`
Diese Datei speichert die wichtigsten Einstellungen für das SENDER-System. Hier werden die Verbindungsdaten für die API, die SMTP-Daten für das Versenden von E-Mails und der Pfad für Log-Dateien definiert. Sie sorgt dafür, dass das System richtig funktioniert und E-Mails verschicken kann.

### `SENDER/private/services/ApiService.php`
Diese Datei verwaltet die Verbindungen zu externen APIs. Sie enthält Funktionen, um Daten zu senden und zu empfangen. Damit ist sie eine wichtige Schnittstelle zwischen dem SENDER-Skript und den externen Diensten.

### `SENDER/private/services/EmailService.php`
Diese Datei wird benutzt, um E-Mails zu versenden. Sie nutzt die SMTP-Daten aus der Konfigurationsdatei und sorgt dafür, dass Erinnerungs-E-Mails korrekt versendet werden.

### `SENDER/private/services/LoggerService.php`
Diese Datei speichert wichtige Informationen über das System. Sie schreibt Fehler, Warnungen oder Statusmeldungen in Log-Dateien. Dies hilft bei der Fehlersuche und der Überwachung des Systems.

### `SENDER/public/erinnerungenSenden.php`
Dieses Skript verschickt Erinnerungs-E-Mails automatisch. Es wird oft über einen Cron-Job ausgeführt und sorgt dafür, dass Nutzer rechtzeitig an ihre Termine erinnert werden.

### `SENDER/public/testMail.php`
Diese Datei wird verwendet, um die E-Mail-Funktion zu testen. Sie stellt sicher, dass die SMTP-Einstellungen richtig konfiguriert sind und E-Mails versendet werden können.

### `SENDER/public/testVorlage.php`
Mit dieser Datei kannst du E-Mail-Vorlagen testen. Sie zeigt, wie eine E-Mail beim Empfänger aussehen wird.

[Zurück zum Inhaltsverzeichnis](#top)


## Über die Aufgabe

Diese Aufgabe wurde von der Firma **MICROLAB GmbH** gestellt.

**MICROLAB GmbH**  
Aubachberg 79  
4941 Mehrnbach/Ried, Austria  
Tel.: +43 7752 70696  
[www.microlab.at/kontakt](http://www.microlab.at/kontakt)

## Entwickler

Ugur CIGDEM  
E: ugur.cigdem@gmail.com <br>

[Zurück zum Inhaltsverzeichnis](#top)
