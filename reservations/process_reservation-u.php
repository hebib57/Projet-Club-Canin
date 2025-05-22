<?php


require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_dog = $_POST['id_dog'] ?? null;
    $id_cours = $_POST['id_cours'] ?? null;
    $id_utilisateur = $_SESSION['user_id'] ?? null;

    if (!$id_dog || !$id_cours || !$id_utilisateur) {
        die("Champs manquants");
    }

    // Recup séance liée au cours
    $stmt = $db->prepare("SELECT id_seance FROM seance WHERE id_cours = ? LIMIT 1");
    $stmt->execute([$id_cours]);
    $id_seance = $stmt->fetchColumn();

    if (!$id_seance) {
        die("Séance introuvable");
    }

    // Vérifier si la réservation existe déjà
    $check = $db->prepare("
        SELECT 1 FROM reservation 
        WHERE id_utilisateur = ? AND id_seance = ? AND id_dog = ?
    ");
    $check->execute([$id_utilisateur, $id_seance, $id_dog]);

    if ($check->fetch()) {
        echo "<script>alert('Vous avez déjà réservé ce cours avec ce chien'); window.location.href = '../user.php';</script>";
        exit;
    }

    // Enregistrement de la réservation
    $stmt = $db->prepare("
        INSERT INTO reservation (id_utilisateur, id_seance, id_dog, date_reservation)
        VALUES (?, ?, ?, NOW())
    ");
    $success = $stmt->execute([$id_utilisateur, $id_seance, $id_dog]);

    if ($success) {
        echo "<script>alert('Cours réservé avec succès'); window.location.href = '../user.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de la réservation du cours'); window.location.href = '../user.php';</script>";
    }
}
exit;
