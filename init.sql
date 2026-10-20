SET NAMES utf8mb4;
 
CREATE TABLE patient (
    patient_id   INT AUTO_INCREMENT PRIMARY KEY,
    sv_nummer    VARCHAR(10) NOT NULL UNIQUE,
    vorname      VARCHAR(50) NOT NULL,
    nachname     VARCHAR(50) NOT NULL,
    geburtsdatum DATE        NOT NULL,
    telefon      VARCHAR(30)
);
 
CREATE TABLE arzt (
    arzt_id      INT AUTO_INCREMENT PRIMARY KEY,
    titel        VARCHAR(50),
    vorname      VARCHAR(50)  NOT NULL,
    nachname     VARCHAR(50)  NOT NULL,
    fachrichtung VARCHAR(100) NOT NULL
);
 
CREATE TABLE termin (
    termin_id  INT AUTO_INCREMENT PRIMARY KEY,
    datum      DATE NOT NULL,
    uhrzeit    TIME NOT NULL,
    grund      VARCHAR(255),
    patient_id INT  NOT NULL,
    arzt_id    INT  NOT NULL,
 
    -- ein Patient hat n Termine, ein Arzt hat n Termine
    -- Wird ein Patient oder Arzt gelöscht, werden seine Termine mitgelöscht
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (arzt_id)    REFERENCES arzt(arzt_id)       ON DELETE CASCADE,
 
    -- F4: keine Doppelbuchung - ein Arzt hat zur gleichen Zeit nur einen Termin
    UNIQUE (arzt_id, datum, uhrzeit)
);
 
INSERT INTO patient (patient_id, sv_nummer, vorname, nachname, geburtsdatum, telefon) VALUES
(1,  '1234010185', 'Anna',      'Huber',   '1985-01-01', '0664 1234567'),
(2,  '2345150790', 'Lukas',     'Gruber',  '1990-07-15', '0676 2345678'),
(3,  '3456220302', 'Sophie',    'Wagner',  '2002-03-22', '0650 3456789'),
(4,  '4567301178', 'Michael',   'Bauer',   '1978-11-30', NULL),
(5,  '5678170501', 'Julia',     'Pichler', '2001-05-17', '0664 5678901'),
(6,  '6789080955', 'Stefan',    'Moser',   '1955-09-08', '0676 6789012'),
(7,  '7890211298', 'Lisa',      'Leitner', '1998-12-21', '0650 7890123'),
(8,  '8901270205', 'Florian',   'Berger',  '2005-02-27', '0664 8901234'),
(9,  '9012020693', 'Katharina', 'Eder',    '1993-06-02', NULL),
(10, '1023300168', 'Markus',    'Wimmer',  '1968-01-30', '0676 1023456');
 
 
 
INSERT INTO arzt (arzt_id, titel, vorname, nachname, fachrichtung) VALUES
(1, 'Dr.',      'Maria',  'Steiner', 'Allgemeinmedizin'),
(2, 'Dr.',      'Thomas', 'Hofer',   'Orthopädie'),
(3, 'Dr. med.', 'Julia',  'Mayer',   'Dermatologie'),
(4, 'Dr.',      'Johann', 'Reiter',  'Innere Medizin');
 
 
 
INSERT INTO termin (termin_id, datum, uhrzeit, grund, patient_id, arzt_id) VALUES
(1,  '2026-10-20', '08:00', 'Kontrolle',            1,  1),
(2,  '2026-10-20', '08:30', 'Grippe',               2,  1),
(3,  '2026-10-20', '08:00', 'Knieschmerzen',        3,  2),
(4,  '2026-10-21', '10:00', 'Hautausschlag',        4,  3),
(5,  '2026-10-22', '09:15', 'Nachkontrolle',        1,  2),
(6,  '2026-10-20', '09:00', 'Blutabnahme',          5,  1),
(7,  '2026-10-20', '10:00', 'Muttermal-Kontrolle',  7,  3),
(8,  '2026-10-20', '09:00', 'Blutdruckkontrolle',   6,  4),
(9,  '2026-10-20', '10:30', 'Rückenschmerzen',      8,  2),
(10, '2026-10-21', '08:00', 'Impfung',              9,  1),
(11, '2026-10-21', '08:30', 'Krankmeldung',        10,  1),
(12, '2026-10-21', '09:00', 'Diabetes-Kontrolle',   6,  4),
(13, '2026-10-21', '11:00', 'Akne-Behandlung',      8,  3),
(14, '2026-10-21', '10:00', 'Schulterschmerzen',    4,  2),
(15, '2026-10-22', '08:00', 'Vorsorgeuntersuchung', 3,  1),
(16, '2026-10-22', '10:00', 'EKG-Kontrolle',       10,  4),
(17, '2026-10-22', '11:00', 'Allergietest',         5,  3),
(18, '2026-10-23', '08:30', 'Sportverletzung',      7,  2),
(19, '2026-10-23', '09:00', 'Befundbesprechung',    2,  4),
(20, '2026-10-23', '10:00', 'Laborbefund',          9,  1);
 