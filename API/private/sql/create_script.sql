
-- Alle Tabellen löschen
-- DROP TABLE IF EXISTS erinnerung2, erinnerung, benutzer;

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



-----------------------------------------------------------------------------------
-- Der 1. Eintrag in die Tabelle benutzer
INSERT INTO benutzer (Name, Email, Passwort, Salt, Rolle)
VALUES ('Test User', 'testuser@example.com', '$2y$10$8LvGuZgekrUA6pmfiV1S8ee80.Pg3FstQC4V3f87XIHKc178QLSSm', '7f3ee49b8c56c9df8340dcc62dacb766', 'admin');

-- ODER:
--
-- BenutzerId=1 erstellen (registerieren)
-- http://localhost:8080/registrieren.php
--
-- POST: http://localhost:5001/api/register.php
-- {
--     "Name": "Test User",
--     "Email": "testuser@example.com",
--     "Passwort": "meinPasswort"
-- }
--
-----------------------------------------------------------------------------------

-- Dummy Daten für die Tabelle erinnerung

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

-----------------------------------------------------------------------------------

/*

-- Beispielabfragen

SELECT ErinnerungId, BenutzerId, Termin, InTage, Bezeichnung FROM erinnerung;
SELECT ErinnerungId, BenutzerId, Von, Bis, Bezeichnung FROM erinnerung2;


-- Alle Einträge der Tabelle mit "von,bis" Angaben.
SELECT ErinnerungId, BenutzerId, Von, Bis, Bezeichnung FROM erinnerung2;

-- Bsp: Erinnerungsscript wird am 2024-11-01 ausgeführt. 
SELECT ErinnerungId, BenutzerId, Von, Bis, Bezeichnung 
FROM erinnerung2 WHERE '2024-11-01' BETWEEN Von AND Bis;

*/
