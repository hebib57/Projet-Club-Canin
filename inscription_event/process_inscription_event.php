<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_dog = $_POST['id_dog'] ?? null;
    $id_event = $_POST['id_event'] ?? null;
    $id_utilisateur = $_SESSION['user_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if (!$id_dog || !$id_event || !$id_utilisateur || !$action) {
        die("Champs manquants");
    }

    // Vérifie chien appartenant à l'utilisateur
    $stmt = $db->prepare("SELECT 1 FROM chien WHERE id_dog = ? AND id_utilisateur = ?");
    $stmt->execute([$id_dog, $id_utilisateur]);
    if (!$stmt->fetch()) {
        echo "<script>alert('Erreur : Ce chien ne vous appartient pas.'); window.location.href = '../user.php';</script>";
        exit;
    }
    if ($action === 'inscrire') {
        // verifie si l'évènement existe
        $stmt = $db->prepare("SELECT COUNT(*) FROM evenement WHERE id_event = ?");
        $stmt->execute([$id_event]);

        if (!$stmt->fetchColumn()) {
            die("Évènement introuvable");
        }

        // Vérifie si l'inscription existe déjà
        $check = $db->prepare("
            SELECT 1 FROM inscription_evenement 
            WHERE id_utilisateur = ? AND id_event = ? AND id_dog = ?
        ");
        $check->execute([$id_utilisateur, $id_event, $id_dog]);

        if ($check->fetch()) {
            echo "<script>alert('Vous avez déjà réservé cet évènement avec ce chien'); window.location.href = '../user.php';</script>";
            exit;
        }

        // Enregistrement de l'inscription
        $stmt = $db->prepare("
            INSERT INTO inscription_evenement (id_utilisateur, id_event, id_dog, date_inscription)
            VALUES (?, ?, ?, NOW())
        ");
        $success = $stmt->execute([$id_utilisateur, $id_event, $id_dog]);

        if ($success) {
            echo "<script>alert('Évènement réservé avec succès'); window.location.href = '../user.php';</script>";
        } else {
            echo "<script>alert('Erreur lors de l\'inscription à l\'évènement'); window.location.href = '../user.php';</script>";
        }
    } elseif ($action === 'desinscrire') {
        if (!$id_dog) {
            die("Champs manquants");
        }
        //désinscription 
        $stmt = $db->prepare("DELETE FROM inscription_evenement WHERE id_utilisateur = ? AND id_event = ? AND id_dog = ?");
        if ($stmt->execute([$id_utilisateur, $id_event, $id_dog])) {
            echo "<script>alert('Désinscription réussie'); window.location.href = '../user.php';</script>";
        } else {
            echo "<script>alert('Erreur lors de la désinscription'); window.location.href = '../user.php';</script>";
        }
    }
    exit;
}
