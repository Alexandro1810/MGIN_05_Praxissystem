Table patient 
{
  patient_id integer [primary key, increment]
  sv_nummer varchar(10) [unique, not null]
  vorname varchar [not null]
  nachname varchar [not null]
  geburtsdatum date [not null]
  telefon varchar
}
 
Table arzt 
{
  arzt_id integer [primary key, increment]
  titel varchar
  vorname varchar [not null]
  nachname varchar [not null]
  fachrichtung varchar [not null]
}
 
Table termin 
{
  termin_id integer [primary key, increment]
  datum date [not null]
  uhrzeit time [not null]
  grund varchar
  patient_id integer [not null]
  arzt_id integer [not null]
 
  indexes {
    (arzt_id, datum, uhrzeit) [unique, note: 'keine Doppelbuchung (F4)']
  }
}
 
Ref: termin.patient_id > patient.patient_id // ein Patient hat n Termine
Ref: termin.arzt_id > arzt.arzt_id // ein Arzt hat n Termine
 
Records patient(patient_id, sv_nummer, vorname, nachname, geburtsdatum, telefon) {
  1, '1234010185', 'Anna', 'Huber', '1985-01-01', '0664 1234567'
  2, '2345150790', 'Lukas', 'Gruber', '1990-07-15', '0676 2345678'
  3, '3456220302', 'Sophie', 'Wagner', '2002-03-22', '0650 3456789'
  4, '4567301178', 'Michael', 'Bauer', '1978-11-30', null
}
 
Records arzt(arzt_id, titel, vorname, nachname, fachrichtung) {
  1, 'Dr.', 'Maria', 'Steiner', 'Allgemeinmedizin'
  2, 'Dr.', 'Thomas', 'Hofer', 'Orthopädie'
  3, 'Dr. med.', 'Julia', 'Mayer', 'Dermatologie'
}
 
Records termin(termin_id, datum, uhrzeit, grund, patient_id, arzt_id) {
  1, '2026-10-20', '08:00', 'Kontrolle', 1, 1
  2, '2026-10-20', '08:30', 'Grippe', 2, 1
  3, '2026-10-20', '08:00', 'Knieschmerzen', 3, 2
  4, '2026-10-21', '10:00', 'Hautausschlag', 4, 3
  5, '2026-10-22', '09:15', 'Nachkontrolle', 1, 2
}