<?php
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $stmt = $pdo->prepare('DELETE FROM patient WHERE patient_id = :id');
    $stmt->execute(['id' => $_POST['id']]); 
}
header('Location: index.php');
exit;
