<?php
require '../db.php';

// F7: Arzt und Datum kommen über GET (aus der Adresszeile)
$arzt_id = $_GET['arzt_id'] ?? '';
$datum = $_GET['datum'] ?? date('Y-m-d');

$aerzte = $pdo->query('SELECT * FROM arzt ORDER BY nachname')->fetchAll();
$termine = [];

if ($arzt_id !== '')
{
    $stmt = $pdo->prepare(
        'SELECT termin.uhrzeit, termin.grund,
                patient.vorname AS patient_vorname, patient.nachname AS patient_nachname
         FROM termin
         JOIN patient ON termin.patient_id = patient.patient_id
         WHERE termin.arzt_id = :arzt_id AND termin.datum = :datum
         ORDER BY termin.uhrzeit'
    );
    $stmt->execute(['arzt_id' => $arzt_id, 'datum' => $datum]);
    $termine = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Tagesansicht</title></head>
<body>
    <h1>Tagesansicht</h1>
    <p><a href="../">Startseite</a> | <a href="index.php">Alle Termine</a></p>

    <form method="get">
        Arzt:
        <select name="arzt_id" required>
            <option value="">-- bitte wählen --</option>
            <?php foreach ($aerzte as $a): ?>
                <option value="<?= $a['arzt_id'] ?>" <?= $a['arzt_id'] == $arzt_id ? 'selected' : '' ?>><?= htmlspecialchars($a['titel'] . ' ' . $a['vorname'] . ' ' . $a['nachname']) ?></option>
            <?php endforeach; ?>
        </select>
        Datum: <input type="date" name="datum" value="<?= htmlspecialchars($datum) ?>" required>
        <button type="submit">Anzeigen</button>
    </form>

    <?php if ($arzt_id !== ''): ?>
        <?php if (empty($termine)): ?>
            <p>An diesem Tag gibt es keine Termine.</p>
        <?php else: ?>
            <table border="1" cellpadding="6">
            <tr>
                <th>Uhrzeit</th>
                <th>Patient:in</th>
                <th>Grund</th>
            </tr>

            <?php foreach ($termine as $t): ?>
            <tr>
                <td><?= htmlspecialchars(substr($t['uhrzeit'], 0, 5)) ?></td>
                <td><?= htmlspecialchars($t['patient_vorname'] . ' ' . $t['patient_nachname']) ?></td>
                <td><?= htmlspecialchars($t['grund'] ?? '') ?></td>
            </tr>
            <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>