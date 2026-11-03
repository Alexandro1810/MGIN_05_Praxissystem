<?php
require '../db.php';
 
$stmt = $pdo->query('SELECT * FROM arzt ORDER BY nachname');
$aerzte = $stmt->fetchAll();
?>
 
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Ärzteliste</title></head>
<body>
    <h1>Ärzteliste</h1>
    <p><a href="../">Startseite</a> | <a href="create.php">+ Neuer Arzt</a></p>
 
    <table border="1" cellpadding="6">
    <tr>
        <th>ID</th>
        <th>Titel</th>
        <th>Vorname</th>
        <th>Nachname</th>
        <th>Fachrichtung</th>
        <th>Aktionen</th>
    </tr>
 
    <?php foreach ($aerzte as $a): ?>
    <tr>
        <td><?= $a['arzt_id'] ?></td>
        <td><?= htmlspecialchars($a['titel'] ?? '') ?></td>
        <td><?= htmlspecialchars($a['vorname']) ?></td>
        <td><?= htmlspecialchars($a['nachname']) ?></td>
        <td><?= htmlspecialchars($a['fachrichtung']) ?></td>
 
        <td>
            <a href="edit.php?id=<?= $a['arzt_id'] ?>">Bearbeiten</a>
            <form method="post" action="delete.php" onsubmit="return confirm('Wirklich löschen?')">
                <input type="hidden" name="id" value="<?= $a['arzt_id'] ?>">
                <button type="submit">Löschen</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>
</body>
</html>
 