<?php
require '../db.php';

// JOIN: zu jedem Termin die Daten von Patient:in und Arzt dazuholen
$stmt = $pdo->query(
    'SELECT termin.datum, termin.uhrzeit, termin.grund,
            patient.vorname AS patient_vorname, patient.nachname AS patient_nachname,
            arzt.titel, arzt.vorname AS arzt_vorname, arzt.nachname AS arzt_nachname
     FROM termin
     JOIN patient ON termin.patient_id = patient.patient_id
     JOIN arzt ON termin.arzt_id = arzt.arzt_id
     ORDER BY termin.datum, termin.uhrzeit'
);
$termine = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Terminliste</title></head>
<body>
    <h1>Terminliste</h1>
        <p><a href="../">Startseite</a> | <a href="create.php">+ Neuer Termin</a> | <a href="tagesansicht.php">Tagesansicht</a></p>
    <table border="1" cellpadding="6">
    <tr>
        <th>Datum</th>
        <th>Uhrzeit</th>
        <th>Patient:in</th>
        <th>Arzt</th>
        <th>Grund</th>
    </tr>

    <?php foreach ($termine as $t): ?>
    <tr>
        <td><?= htmlspecialchars($t['datum']) ?></td>
        <td><?= htmlspecialchars(substr($t['uhrzeit'], 0, 5)) ?></td>
        <td><?= htmlspecialchars($t['patient_vorname'] . ' ' . $t['patient_nachname']) ?></td>
        <td><?= htmlspecialchars($t['titel'] . ' ' . $t['arzt_vorname'] . ' ' . $t['arzt_nachname']) ?></td>
        <td><?= htmlspecialchars($t['grund'] ?? '') ?></td>
    </tr>
    <?php endforeach; ?>
    </table>
</body>
</html>