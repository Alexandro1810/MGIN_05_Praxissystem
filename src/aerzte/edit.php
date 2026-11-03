<?php
require '../db.php';
 
$id = $_GET['id'] ?? null;
 
$stmt = $pdo->prepare('SELECT * FROM arzt WHERE arzt_id = :id');
$stmt->execute(['id' => $id]);
$a = $stmt->fetch();
 
if (!$a)
{
    die('Eintrag nicht gefunden');
}
 
$fehler = [];
 
// Beim Öffnen kommen die Werte aus der Datenbank, nach dem Absenden aus dem Formular
$titel = trim($_POST['titel'] ?? $a['titel'] ?? '');
$vorname = trim($_POST['vorname'] ?? $a['vorname']);
$nachname = trim($_POST['nachname'] ?? $a['nachname']);
$fachrichtung = trim($_POST['fachrichtung'] ?? $a['fachrichtung']);
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    // Titel ist optional, darum wird er nicht geprüft
    if ($vorname === '')
    {
        $fehler[] = 'Vorname darf nicht leer sein.';
    }
 
    if ($nachname === '')
    {
        $fehler[] = 'Nachname darf nicht leer sein.';
    }
 
    if ($fachrichtung === '')
    {
        $fehler[] = 'Fachrichtung darf nicht leer sein.';
    }
 
    if (empty($fehler))
    {
        $stmt = $pdo->prepare(
            'UPDATE arzt
             SET titel = :titel, vorname = :vorname, nachname = :nachname, fachrichtung = :fachrichtung
             WHERE arzt_id = :id'
        );
 
        $stmt->execute([
            'titel' => $titel,
            'vorname' => $vorname,
            'nachname' => $nachname,
            'fachrichtung' => $fachrichtung,
            'id' => $id,
        ]);
 
        header('Location: index.php');
        exit;
    }
}
?>
 
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Arzt bearbeiten</title></head>
 
<body>
<h1>Arzt bearbeiten</h1>
 
<?php if (!empty($fehler)): ?>
    <ul>
        <?php foreach ($fehler as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
 
<form method="post">
    <p><input type="text" name="titel" placeholder="Titel (optional, z. B. Dr.)" value="<?= htmlspecialchars($titel) ?>"></p>
    <p><input type="text" name="vorname" placeholder="Vorname" value="<?= htmlspecialchars($vorname) ?>" required></p>
    <p><input type="text" name="nachname" placeholder="Nachname" value="<?= htmlspecialchars($nachname) ?>" required></p>
    <p><input type="text" name="fachrichtung" placeholder="Fachrichtung" value="<?= htmlspecialchars($fachrichtung) ?>" required></p>
    <button type="submit">Aktualisieren</button>
    <a href="index.php">Abbrechen</a>
</form>
</body>
</html>
 