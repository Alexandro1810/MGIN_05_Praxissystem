<?php
require '../db.php';                                        
 
$stmt = $pdo->query('SELECT * FROM patient ORDER BY nachname');
$patienten = $stmt->fetchAll();                        
?>
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Patientenliste</title></head>
<body>
    <h1>Patientenliste</h1>
    <p><a href="../">Startseite</a> | <a href="create.php">+ Neuer Patient</a></p>
 
    <table border="1" cellpadding="6">
        <tr>
            <th>SV-Nummer</th><th>Vorname</th><th>Nachname</th><th>Geburtsdatum</th><th>Telefon</th><th>Aktionen</th>
        </tr>
        <?php foreach ($patienten as $p): ?>
        <tr>
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
 
 