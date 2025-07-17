<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';


// $query = "
// SELECT 
//         r.id_reservation,
//         r.date_reservation,
//         u.nom_utilisateur,
//         r.id_dog,
//         d.nom_dog,
//         s.id_seance,
//         s.date_seance,
//         s.heure_seance,
//         s.places_disponibles,
//         s.duree_seance,
//         s.statut_seance,
//         co.nom_cours,
//         co.categorie_acceptee
//     FROM 
//         reservation r
//         JOIN seance s ON r.id_seance = s.id_seance
//         JOIN cours co ON s.id_cours = co.id_cours
//         JOIN utilisateur u ON r.id_utilisateur = u.id_utilisateur
//         JOIN chien d ON r.id_dog = d.id_dog
//     ORDER BY r.date_reservation DESC
//     ";
// $stmt = $db->query($query);
// $recordset_reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);


// recup les inscriptions aux evenements
// $stmt = $db->prepare("
//   SELECT 
//   i.id_inscription,
//   i.date_inscription,
//   u.nom_utilisateur,
//   d.nom_dog,
//   e.nom_event
//    FROM inscription_evenement i 
//    JOIN utilisateur u ON i.id_utilisateur = u.id_utilisateur
//    JOIN chien d ON i.id_dog = d.id_dog
//    JOIN evenement e ON i.id_event = e.id_event
//    ORDER BY i.date_inscription DESC;
//    ");
// $stmt->execute();
// $recordset_inscription_event = $stmt->fetchAll(PDO::FETCH_ASSOC);
// --------------------------------------------------------------------------------------------------------------------
// Page actuelle (par défaut 1)
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Compter le total des enregistrements
$stmtCount = $db->prepare("SELECT COUNT(*) as total FROM reservation ");
$stmtCount->execute();
$totalReservation = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

// Nombre d'éléments par page
$nbPerPage = isset($_GET['nbPerPage']) ? (int) $_GET['nbPerPage'] : 10;

// Évite la division par zéro
if ($nbPerPage <= 0) {
    $nbPerPage = 10;
}
// Calcul du nombre de pages
$nbPage = ceil($totalReservation / $nbPerPage);


$offset = ($currentPage - 1) * $nbPerPage;

$stmt = $db->prepare("SELECT 
                        r.id_reservation,
        r.date_reservation,
        u.nom_utilisateur,
        r.id_dog,
        d.nom_dog,
        s.id_seance,
        s.date_seance,
        s.heure_seance,
        s.places_disponibles,
        s.duree_seance,
        s.statut_seance,
        co.nom_cours,
        co.categorie_acceptee
        FROM 
         reservation r
        JOIN seance s ON r.id_seance = s.id_seance
        JOIN cours co ON s.id_cours = co.id_cours
        JOIN utilisateur u ON r.id_utilisateur = u.id_utilisateur
        JOIN chien d ON r.id_dog = d.id_dog
        ORDER BY r.date_reservation DESC 
        LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recordset_reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);

// -------------------------------------------------------------------------------------------------------------------------------------------------

// Page actuelle (par défaut 1)
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Compter le total des enregistrements
$stmtCount = $db->prepare("SELECT COUNT(*) as total FROM evenement ");
$stmtCount->execute();
$totalInscription = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

// Nombre d'éléments par page
$nbPerPage = isset($_GET['nbPerPage']) ? (int) $_GET['nbPerPage'] : 10;

// Évite la division par zéro
if ($nbPerPage <= 0) {
    $nbPerPage = 10;
}
// Calcul du nombre de pages
$nbPage = ceil($totalInscription / $nbPerPage);


$offset = ($currentPage - 1) * $nbPerPage;

$stmt = $db->prepare("SELECT 
     i.id_inscription,
     i.date_inscription,
     u.nom_utilisateur,
     d.nom_dog,
     e.nom_event
     FROM inscription_evenement i 
     JOIN utilisateur u ON i.id_utilisateur = u.id_utilisateur
     JOIN chien d ON i.id_dog = d.id_dog
     JOIN evenement e ON i.id_event = e.id_event
     ORDER BY i.date_inscription DESC
        LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recordset_inscription_event = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '../../templates/header.php'
?>







<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé des activités du Club Canin.</h2>
</div>


<div class="sidebar">
    <button class="sidebar__burger-menu-toggle" id="sidebarMenu">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </button>
    <div class="sidebar-header">
        <div class="user-avatar">AD</div>
        <div class="user-info">
            <h3><?= hsc(ucfirst($prenom_utilisateur)) ?></h3>

        </div>
    </div>

    <ul class="menu-list">
        <li><a href="administratif.php">Tableau de bord <img src="../interface_graphique/online-reservation.png" alt="dashboard" width="40px
          "></a></li>
        <li><a href="reservations-admin.php">Suivi des Réservations <img src="../interface_graphique/reservation.png" alt="reservations" width="40px
          "></a></li>
        <li><a href="cours_programmes-admin.php">Gestion des Cours <img src="../interface_graphique/training-program.png" alt="cours" width="40px
          "></a></li>
        <li><a href="users-admin.php">Gestion des Utilisateurs<img src="../interface_graphique/add.png" alt="users" width="40px
          "></a></li>
        <li><a href="#coachs">Gestion des Coachs <img src="../interface_graphique/coach.png" alt="coachs" width="40px
          "></a></li>
        <li><a href="dogs-admin.php">Gestion des Chiens <img src="../interface_graphique/corgi.png" alt="dogs" width="40px
          "></a></li>
        <li><a href="events_programmes-admin.php">Gestion des Evènements <img src="../interface_graphique/banner.png" alt="events" width="40px
          "></a></li>
        <li><a href="messagerie-admin.php">Messagerie <img src="../interface_graphique/mail.png" alt="messagerie" width="40px
          "></a></li>
        <li><a href="parameters_count-admin.php">Paramètres du Compte <img src="../interface_graphique/admin-panel.png" alt="parametres" width="40px
          "></a></li>
        <li><a href="../logout.php">Déconnexion <img src="../interface_graphique/img-exit.png" alt="logout" width="40px
          "></a></li>
    </ul>
</div>



<section class="reservations" id="reservations">
    <div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
        <?php displayPagination($nbPage, $currentPage, "reservations-admin.php#reservations", "page", $nbPerPage); ?>
    </div>
    <h2>Cours réservés</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Catégorie</th>
                    <th>Utilisateur</th>
                    <th>Nom du chien</th>
                    <th>Nom Cours</th>
                    <th>Date Séance</th>
                    <th>Heure Séance</th>
                    <th>Date Réservation</th>
                    <th>Action</th>
                </tr>
            </thead><?php foreach ($recordset_reservation as $reserv): ?>

                <tbody>
                    <tr>
                        <td><?= hsc($reserv['id_reservation']); ?></td>
                        <td><?= hsc($reserv['categorie_acceptee']); ?></td>
                        <td><?= hsc($reserv['nom_utilisateur']); ?></td>
                        <td><?= hsc($reserv['nom_dog']); ?></td>
                        <td><?= hsc($reserv['nom_cours']); ?></td>
                        <td><?= hsc($reserv['date_seance']); ?></td>
                        <td><?= hsc($reserv['heure_seance']); ?></td>
                        <td><?= hsc($reserv['date_reservation']); ?></td>
                        <td>
                            <!-- Option de suppression ou gestion -->
                            <form method="post" action="../reservations/delete_reservation.php" style="display: inline;">
                                <input type="hidden" name="id_reservation" value="<?= hsc($reserv['id_reservation']); ?>">
                                <button type="submit" class="btn" onclick=" return confirmationDeleteReservation();">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
        </table>
    </div>
    <div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
        <?php displayPagination($nbPage, $currentPage, "reservations-admin.php#reservations", "page", $nbPerPage); ?>
    </div>
</section>





<section class="inscriptions_event" id="inscriptions_event">
    <div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
        <?php displayPagination($nbPage, $currentPage, "reservations-admin.php#inscriptions_event", "page", $nbPerPage); ?>
    </div>
    <h2>Évènements réservés</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Nom du chien</th>
                    <th>Nom Évènement</th>
                    <!-- <th>Date Séance</th>
                  <th>Heure Séance</th> -->
                    <th>Date Inscription</th>
                    <th>Action</th>
                </tr>
            </thead><?php foreach ($recordset_inscription_event as $inscription): ?>

                <tbody>
                    <tr>
                        <td><?= hsc($inscription['id_inscription']); ?></td>
                        <td><?= hsc($inscription['nom_utilisateur']); ?></td>
                        <td><?= hsc($inscription['nom_dog']); ?></td>
                        <td><?= hsc($inscription['nom_event']); ?></td>
                        <td><?= hsc($inscription['date_inscription']); ?></td>
                        <td>
                            <!-- Option de suppression ou gestion -->
                            <form method="post" action="../inscription_event/delete_inscription_event.php" style="display: inline;">
                                <input type="hidden" name="id_inscription" value="<?= hsc($inscription['id_inscription']); ?>">
                                <button type="submit" class="btn" onclick=" return confirmationDeleteInscription();">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
        </table>
    </div>
    <div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
        <?php displayPagination($nbPage, $currentPage, "reservations-admin.php#inscription_event", "page", $nbPerPage); ?>
    </div>
</section>

<?php require_once __DIR__ . '../../templates/footer.php' ?>