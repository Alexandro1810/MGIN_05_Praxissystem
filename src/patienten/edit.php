<?php
require '../db.php';
$fehler = [];                                
$sv_nummer    = trim($_POST['sv_nummer'] ?? '');
$vorname      = trim($_POST['vorname'] ?? '');
$nachname     = trim($_POST['nachname'] ?? '');
$geburtsdatum = trim($_POST['geburtsdatum'] ?? '');
$telefon      = trim($_POST['telefon'] ?? '');
 
if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
 
    // SV-Nummer: genau 10 Zeichen und nur Ziffern
    if (strlen($sv_nummer) !== 10 || !ctype_digit($sv_nummer)) {
        $fehler[] = 'SV-Nummer muss genau 10 Ziffern haben.';
    }
 
    // SV-Nummer darf es noch nicht geben (UNIQUE in der init.sql)
    $stmt = $pdo->prepare('SELECT * FROM patient WHERE sv_nummer = :sv_nummer');
    $stmt->execute(['sv_nummer' => $sv_nummer]);
    if ($stmt->fetch()) {
        $fehler[] = 'Diese SV-Nummer gibt es schon.';
    }
 
    if ($vorname === '') {
        $fehler[] = 'Vorname darf nicht leer sein.';
    }
 
    if ($nachname === '') {
        $fehler[] = 'Nachname darf nicht leer sein.';
    }
 
    if ($geburtsdatum === '') {
        $fehler[] = 'Geburtsdatum darf nicht leer sein.';
    }
 
    if (empty($fehler)) {                            // nur ohne Fehler speichern
        $stmt = $pdo->prepare(
            'INSERT INTO patient (sv_nummer, vorname, nachname, geburtsdatum, telefon)
             VALUES (:sv_nummer, :vorname, :nachname, :geburtsdatum, :telefon)'
        );
 
        $stmt->execute([
            'sv_nummer'    => $sv_nummer,
            'vorname'      => $vorname,
            'nachname'     => $nachname,
            'geburtsdatum' => $geburtsdatum,
            'telefon'      => $telefon,
        ]);
 
        header('Location: index.php');               // Post/Redirect/Get
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Neuer Patient</title>
</head>
<body>
    <h1>Neuer Patient</h1>
 
    <?php if (!empty($fehler)): ?>
        <ul>
            <?php foreach ($fehler as $f): ?>
                <li><?= htmlspecialchars($f) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
 
    <form method="post">
        <p><input type="text" name="sv_nummer" placeholder="SV-Nummer (10 Ziffern)" value="<?= htmlspecialchars($sv_nummer) ?>" required></p>
        <p><input type="text" name="vorname" placeholder="Vorname" value="<?= htmlspecialchars($vorname) ?>" required></p>
        <p><input type="text" name="nachname" placeholder="Nachname" value="<?= htmlspecialchars($nachname) ?>" required></p>
        <p>Geburtsdatum: <input type="date" name="geburtsdatum" value="<?= htmlspecialchars($geburtsdatum) ?>" required></p>
        <p><input type="text" name="telefon" placeholder="Telefon (optional)" value="<?= htmlspecialchars($telefon) ?>"></p>
        <button type="submit">Speichern</button>
        <a href="index.php">Abbrechen</a>
    </form>
</body>
</html>
 
 