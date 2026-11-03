<?php
require '../db.php';

$fehler = [];

$titel = trim($_POST['titel'] ?? '');
$vorname = trim($_POST['vorname'] ?? '');
$nachname = trim($_POST['nachname'] ?? '');
$fachrichtung = trim($_POST['fachrichtung'] ?? '');

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
            'INSERT INTO arzt (titel, vorname, nachname, fachrichtung)
             VALUES (:titel, :vorname, :nachname, :fachrichtung)'
        );

        $stmt->execute([
            'titel' => $titel,
            'vorname' => $vorname,
            'nachname' => $nachname,
            'fachrichtung' => $fachrichtung,
        ]);

        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Neuer Arzt</title></head>

<body>
<h1>Neuer Arzt</h1>

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
    <button type="submit">Speichern</button>
    <a href="index.php">Abbrechen</a>
</form>
</body>
</html>