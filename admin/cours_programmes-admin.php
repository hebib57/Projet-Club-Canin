<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';


// $stmt = $db->prepare("SELECT * FROM cours JOIN seance ON cours.id_cours = seance.id_cours");
// $stmt->execute();
// $recordset_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Page actuelle (par défaut 1)
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Compter le total des enregistrements
$stmtCount = $db->prepare("SELECT COUNT(*) as total FROM cours JOIN seance ON cours.id_cours = seance.id_cours");
$stmtCount->execute();
$totalCours = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

// Nombre d'éléments par page
$nbPerPage = isset($_GET['nbPerPage']) ? (int) $_GET['nbPerPage'] : 10;

// Évite la division par zéro
if ($nbPerPage <= 0) {
    $nbPerPage = 10;
}
// Calcul du nombre de pages
$nbPage = ceil($totalCours / $nbPerPage);


$offset = ($currentPage - 1) * $nbPerPage;

$stmt = $db->prepare("SELECT * FROM cours JOIN seance ON cours.id_cours = seance.id_cours LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recordset_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

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


<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "cours_programmes-admin.php", "page", $nbPerPage); ?>
</div>


<section class="cours_programmé" id="cours_programmé">
    <h2>Gestion des Cours</h2>
    <div class="table-container">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">
                ✅ Le cours et sa séance ont bien été ajoutés !
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert error">
                ❌ Une erreur est survenue lors de l'ajout du cours. Veuillez réessayer.
            </div>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <!-- <th>Nom du Cours</th> -->
                    <th>Type de cours</th>
                    <th>Description du cours</th>
                    <!-- <th>Âge min</th>
                  <th>Âge max</th> -->
                    <th>Catégorie</th>
                    <th>Race</th>
                    <th>Sexe</th>
                    <th>Places</th>
                    <th>Date </th>
                    <th>Heure</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recordset_cours as $row) { ?>
                    <tr>
                        <td><?= hsc($row['id_cours']); ?></td>
                        <!-- <td><?= hsc($row['nom_cours']); ?></td> -->
                        <td><?= hsc($row['type_cours']); ?></td>
                        <td><?= hsc($row['description_cours']); ?></td>
                        <!-- <td><?= hsc($row['age_min']); ?></td> -->
                        <td><?= hsc($row['categorie_acceptee']); ?></td>
                        <!-- <td><?= hsc($row['age_max']); ?></td> -->
                        <td><?= hsc($row['race_dog']); ?></td>
                        <td><?= hsc($row['sexe_dog']); ?></td>
                        <td><?= hsc($row['places_disponibles']); ?></td>
                        <td><?= hsc($row['date_cours']); ?></td>
                        <td><?= hsc($row['heure_cours']); ?></td>

                        <td>
                            <button class="btn"><a href="../cours/form.php?id=<?= hsc($row['id_cours']) ?>">Modifier</a></button>
                            <button class="btn"><a href="../cours/delete.php?id=<?= hsc($row['id_cours']) ?>" onclick="return confirmationDeleteCours();">Supprimer</a></button>
                        </td>
                    </tr>
                <?php }; ?>
            </tbody>
        </table>
    </div>
    <button class="btn">
        <a href="../cours/form.php">Ajouter un Cours</a>
    </button>

</section>







<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "cours_programmes-admin.php", "page", $nbPerPage); ?>
</div>


<?php require_once __DIR__ . '../../templates/footer.php' ?>