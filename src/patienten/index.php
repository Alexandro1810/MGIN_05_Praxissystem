<?php
require '../db.php';

// F6: Suchbegriff aus der Adresszeile holen (GET)
$suche = trim($_GET['suche'] ?? '');
if ($suche === '')
{
    $stmt = $pdo->query('SELECT * FROM patient ORDER BY nachname');
}

else
{
    $stmt = $pdo->prepare('SELECT * FROM patient WHERE vorname LIKE :s1 OR nachname LIKE :s2 OR sv_nummer LIKE :s3 ORDER BY nachname');
    $stmt->execute(['s1' => "%$suche%", 's2' => "%$suche%", 's3' => "%$suche%"]);
}
$patienten = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Patientenliste</title></head>
<body>
    <h1>Patientenliste</h1>
    <p><a href="../">Startseite</a> | <a href="create.php">+ Neuer Patient</a></p>
        <form method="get">
            <input type="text" name="suche" placeholder="Name oder SV-Nummer" value="<?= htmlspecialchars($suche) ?>">
            <button type="submit">Suchen</button>
            <a href="index.php">Alle anzeigen</a>
        </form>

        <?php if (empty($patienten)): ?>
            <p>Keine Patient:innen gefunden.</p>
        <?php endif; ?>

    <table border="1" cellpadding="6">
    <tr>
        <th>ID</th>
        <th>SV-Nummer</th>
        <th>Vorname</th>
        <th>Nachname</th>
        <th>Geburtsdatum</th>
        <th>Telefon</th>
        <th>Aktionen</th>
    </tr>

    <?php foreach ($patienten as $p): ?>
    <tr>
        <td><?= $p['patient_id'] ?></td>
        <td><?= htmlspecialchars($p['sv_nummer']) ?></td>
        <td><?= htmlspecialchars($p['vorname']) ?></td>
        <td><?= htmlspecialchars($p['nachname']) ?></td>
        <td><?= htmlspecialchars($p['geburtsdatum']) ?></td>
        <td><?= htmlspecialchars($p['telefon'] ?? '') ?></td>

        <td>
            <a href="edit.php?id=<?= $p['patient_id'] ?>">Bearbeiten</a>
            <form method="post" action="delete.php" onsubmit="return confirm('Wirklich löschen?')">
                <input type="hidden" name="id" value="<?= $p['patient_id'] ?>">
                <button type="submit">Löschen</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>
</body>
</html>