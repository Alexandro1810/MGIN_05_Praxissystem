<?php
require '../db.php';
 
$id = $_GET['id'] ?? null;
 
$stmt = $pdo->prepare('SELECT * FROM patient WHERE patient_id = :id');
 
$stmt->execute(['id' => $id]);
 
$p = $stmt->fetch();
 
if (!$p)
{
    die('Eintrag nicht gefunden');
}
 
$fehler = [];
 
// Beim Öffnen kommen die Werte aus der Datenbank, nach dem Absenden aus dem Formular
$sv_nummer = trim($_POST['sv_nummer'] ?? $p['sv_nummer']);
$vorname = trim($_POST['vorname'] ?? $p['vorname']);
$nachname = trim($_POST['nachname'] ?? $p['nachname']);
$geburtsdatum = trim($_POST['geburtsdatum'] ?? $p['geburtsdatum']);
$telefon = trim($_POST['telefon'] ?? $p['telefon'] ?? '');
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    if (strlen($sv_nummer) !== 10 || !ctype_digit($sv_nummer)) {
        $fehler[] = 'SV-Nummer muss genau 10 Ziffern haben.';
    }
 
    // SV-Nummer darf kein ANDERER Patient haben (der eigene Datensatz zählt nicht)
    $stmt = $pdo->prepare('SELECT * FROM patient WHERE sv_nummer = :sv_nummer AND patient_id != :id');
    $stmt->execute(['sv_nummer' => $sv_nummer, 'id' => $id]);
 
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
 
    if (empty($fehler)) {
 
        $stmt = $pdo->prepare(
            'UPDATE patient
             SET sv_nummer = :sv_nummer, vorname = :vorname, nachname = :nachname,
                 geburtsdatum = :geburtsdatum, telefon = :telefon
             WHERE patient_id = :id'
        );
 
        $stmt->execute([
            'sv_nummer' => $sv_nummer,
            'vorname' => $vorname,
            'nachname' => $nachname,
            'geburtsdatum' => $geburtsdatum,
            'telefon' => $telefon,
            'id' => $id,
        ]);
 
        header('Location: index.php');
        exit;
    }
}
?>
 
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Patient bearbeiten</title></head>
 
<body>
<h1>Patient bearbeiten</h1>
 
<?php if (!empty($fehler)): ?>
    <ul>
        <?php foreach ($fehler as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
 
<form method="post">
    <p><input type="text" name="sv_nummer" value="<?= htmlspecialchars($sv_nummer) ?>" required></p>
    <p><input type="text" name="vorname" value="<?= htmlspecialchars($vorname) ?>" required></p>
    <p><input type="text" name="nachname" value="<?= htmlspecialchars($nachname) ?>" required></p>
    <p>Geburtsdatum: <input type="date" name="geburtsdatum" value="<?= htmlspecialchars($geburtsdatum) ?>" required></p>
    <p><input type="text" name="telefon" placeholder="Telefon (optional)" value="<?= htmlspecialchars($telefon) ?>"></p>
 
    <button type="submit">Aktualisieren</button>
 
    <a href="index.php">Abbrechen</a>
</form>
</body>
</html>
 