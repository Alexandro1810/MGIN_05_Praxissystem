<?php
require '../db.php';

$id = $_GET['id'] ?? null;

$stmt = $pdo->prepare('SELECT * FROM termin WHERE termin_id = :id');
$stmt->execute(['id' => $id]);
$termin = $stmt->fetch();

if (!$termin)
{
    die('Eintrag nicht gefunden');
}

$fehler = [];

// Beim Öffnen kommen die Werte aus der Datenbank, nach dem Absenden aus dem Formular
$patient_id = $_POST['patient_id'] ?? $termin['patient_id'];
$arzt_id = $_POST['arzt_id'] ?? $termin['arzt_id'];
$datum = trim($_POST['datum'] ?? $termin['datum']);
$uhrzeit = trim($_POST['uhrzeit'] ?? substr($termin['uhrzeit'], 0, 5));
$grund = trim($_POST['grund'] ?? $termin['grund'] ?? '');

// Daten für die Auswahllisten
$patienten = $pdo->query('SELECT * FROM patient ORDER BY nachname')->fetchAll();
$aerzte = $pdo->query('SELECT * FROM arzt ORDER BY nachname')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($patient_id === '')
    {
        $fehler[] = 'Bitte eine Patientin oder einen Patienten auswählen.';
    }

    if ($arzt_id === '')
    {
        $fehler[] = 'Bitte einen Arzt auswählen.';
    }

    if ($datum === '')
    {
        $fehler[] = 'Datum darf nicht leer sein.';
    }

    if ($uhrzeit === '')
    {
        $fehler[] = 'Uhrzeit darf nicht leer sein.';
    }

    // F4: keine Doppelbuchung - der eigene Termin zählt dabei nicht
    if (empty($fehler))
    {
        $stmt = $pdo->prepare('SELECT * FROM termin WHERE arzt_id = :arzt_id AND datum = :datum AND uhrzeit = :uhrzeit AND termin_id != :id');
        $stmt->execute(['arzt_id' => $arzt_id, 'datum' => $datum, 'uhrzeit' => $uhrzeit, 'id' => $id]);

        if ($stmt->fetch())
        {
            $fehler[] = 'Dieser Arzt hat zu dieser Zeit schon einen Termin.';
        }
    }

    if (empty($fehler))
    {
        try
        {
            $stmt = $pdo->prepare('UPDATE termin SET datum = :datum, uhrzeit = :uhrzeit, grund = :grund, patient_id = :patient_id, arzt_id = :arzt_id WHERE termin_id = :id');

            $stmt->execute([
                'datum' => $datum,
                'uhrzeit' => $uhrzeit,
                'grund' => $grund,
                'patient_id' => $patient_id,
                'arzt_id' => $arzt_id,
                'id' => $id,
            ]);

            header('Location: index.php');
            exit;
        }
        catch (PDOException $e)
        {
            // F4: 1062 = doppelter Eintrag (UNIQUE arzt_id, datum, uhrzeit)
            if ($e->errorInfo[1] == 1062)
            {
                $fehler[] = 'Dieser Arzt hat zu dieser Zeit schon einen Termin.';
            }
            else
            {
                throw $e;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Termin bearbeiten</title></head>

<body>
<h1>Termin bearbeiten</h1>

<?php if (!empty($fehler)): ?>
    <ul>
        <?php foreach ($fehler as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">
    <p>Patient:in:
        <select name="patient_id" required>
            <option value="">-- bitte wählen --</option>
            <?php foreach ($patienten as $p): ?>
                <option value="<?= $p['patient_id'] ?>" <?= $p['patient_id'] == $patient_id ? 'selected' : '' ?>><?= htmlspecialchars($p['nachname'] . ' ' . $p['vorname'] . ' (' . $p['sv_nummer'] . ')') ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>Arzt:
        <select name="arzt_id" required>
            <option value="">-- bitte wählen --</option>
            <?php foreach ($aerzte as $a): ?>
                <option value="<?= $a['arzt_id'] ?>" <?= $a['arzt_id'] == $arzt_id ? 'selected' : '' ?>><?= htmlspecialchars($a['titel'] . ' ' . $a['vorname'] . ' ' . $a['nachname'] . ' (' . $a['fachrichtung'] . ')') ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>Datum: <input type="date" name="datum" value="<?= htmlspecialchars($datum) ?>" required></p>
    <p>Uhrzeit: <input type="time" name="uhrzeit" value="<?= htmlspecialchars($uhrzeit) ?>" required></p>
    <p><input type="text" name="grund" placeholder="Grund (optional)" value="<?= htmlspecialchars($grund) ?>"></p>
    <button type="submit">Aktualisieren</button>
    <a href="index.php">Abbrechen</a>
</form>
</body>
</html>