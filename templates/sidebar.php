<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
$role = $_SESSION['role_id'] ?? null;

?>

<div class="sidebar">
    <button class="sidebar__burger-menu-toggle" id="sidebarMenu">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </button>
    <div class="sidebar-header">
        <div class="user-avatar">U</div>
        <div class="user-info">
            <h3><?= hsc(ucfirst($prenom_utilisateur)) ?></h3>
        </div>
    </div>

    <ul class="menu-list">

        <?php
        if ($role === 3): ?>
            <li><a href="user.php#dashbord">Tableau de bord <img src="../interface_graphique/online-reservation.png" alt="dashboard" width="40px"></a></li>
            <li><a href="cours_programmes-user.php">Cours programmés <img src="../interface_graphique/training-program.png" alt="cours" width="40px"></a></li>
            <li><a href="event_programmes-user.php">Évènements programmés <img src="../interface_graphique/banner.png" alt="events" width="40px"></a></li>
            <li><a href="dogs-user.php">Mes chiens <img src="../interface_graphique/corgi.png" alt="dogs" width="40px"></a></li>
            <li><a href="reservations-user.php">Mes réservations <img src="../interface_graphique/reservation.png" alt="reservations" width="40px"></a></li>
            <li><a href="progression.php">Progression <img src="../interface_graphique/img-progress.png" alt="progression" width="40px"></a></li>
            <li><a href="messagerie-user.php">Messagerie <img src="../interface_graphique/mail.png" alt="messagerie" width="40px"></a></li>
            <li><a href="#">Paramètres du compte <img src="../interface_graphique/admin-panel.png" alt="parametres" width="40px"></a></li>
            <li><a href="/logout.php">Déconnexion <img src="../interface_graphique/img-exit.png" alt="logout" width="40px"></a></li>

        <?php
        elseif ($role === 2): ?>
            <li><a href="coach.php">Tableau de bord <img src="../interface_graphique/online-reservation.png" alt="dashboard" width="40px"></a></li>
            <li><a href="cours_programmes-coach.php">Gestion des Cours <img src="../interface_graphique/training-program.png" alt="cours" width="40px"></a></li>
            <li><a href="event_programmes-coach.php">Gestion des Évènements <img src="../interface_graphique/banner.png" alt="events" width="40px"></a></li>
            <li><a href="reservations-coach.php">Suivi des réservations <img src="../interface_graphique/reservation.png" alt="reservations" width="40px"></a></li>
            <li><a href="evaluations-coach.php">Evaluation <img src="../interface_graphique/img-eval.png" alt="evaluations" width="40px"></a></li>
            <li><a href="messagerie-coach.php">Messagerie <img src="../interface_graphique/mail.png" alt="messagerie" width="40px"></a></li>
            <li><a href="#">Paramètres du compte <img src="../interface_graphique/admin-panel.png" alt="parametres" width="40px"></a></li>
            <li><a href="/logout.php">Déconnexion <img src="../interface_graphique/img-exit.png" alt="logout" width="40px"></a></li>

        <?php
        elseif ($role === 1): ?>
            <li><a href="administratif.php">Tableau de bord <img src="../interface_graphique/online-reservation.png" alt="dashboard" width="40px"></a></li>
            <li><a href="reservations-admin.php">Suivi des Réservations <img src="../interface_graphique/reservation.png" alt="reservations" width="40px"></a></li>
            <li><a href="cours_programmes-admin.php">Gestion des Cours <img src="../interface_graphique/training-program.png" alt="cours" width="40px"></a></li>
            <li><a href="users-admin.php">Gestion des Utilisateurs<img src="../interface_graphique/add.png" alt="users" width="40px"></a></li>
            <li><a href="#coachs">Gestion des Coachs <img src="../interface_graphique/coach.png" alt="coachs" width="40px"></a></li>
            <li><a href="dogs-admin.php">Gestion des Chiens <img src="../interface_graphique/corgi.png" alt="dogs" width="40px"></a></li>
            <li><a href="events_programmes-admin.php">Gestion des Evènements <img src="../interface_graphique/banner.png" alt="events" width="40px"></a></li>
            <li><a href="messagerie-admin.php">Messagerie <img src="../interface_graphique/mail.png" alt="messagerie" width="40px"></a></li>
            <li><a href="parameters_count-admin.php">Paramètres du Compte <img src="../interface_graphique/admin-panel.png" alt="parametres" width="40px"></a></li>
            <li><a href="../logout.php">Déconnexion <img src="../interface_graphique/img-exit.png" alt="logout" width="40px"></a></li>
        <?php endif ?>
    </ul>
</div>