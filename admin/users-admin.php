<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';

// recup tous les utilisateurs avec leur rôle
$sql = "SELECT * 
        FROM utilisateur u
        JOIN utilisateur_role ur ON u.id_utilisateur = ur.id_utilisateur
        JOIN role r ON ur.id_role = r.id_role";


try {
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $recordset_role = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
    $recordset_role = []; // Pour éviter d'autres erreurs en cas d'échec
}



require_once __DIR__ . '/../header.php'
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
        <li><a href="../admin/logout.php">Déconnexion <img src="../interface_graphique/img-exit.png" alt="logout" width="40px
          "></a></li>
    </ul>
</div>


<section class="admins" id="admins">
    <h2>Gestion des Admins</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <!-- <th>Rôle</th> -->
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recordset_role as $row) { ?>

                    <?php if ($row['id_role'] == 1) { ?>
                        <tr>

                            <td><?= hsc($row['id_utilisateur']); ?></td>
                            <td><?= hsc($row['nom_utilisateur']); ?></td>
                            <td><?= hsc($row['prenom_utilisateur']); ?></td>
                            <td><?= hsc($row['admin_mail']); ?></td>
                            <td><?= hsc($row['telephone_utilisateur']); ?></td>
                            <!-- <td><?= hsc($row['nom_role']); ?></td> -->
                            <!-- <td><?= hsc($row['date_inscription']); ?></td> -->
                            <td> <?php $date = new DateTime($row['date_inscription']);
                                    echo hsc($date->format('d/m/Y')); ?></td>
                            <td>
                            <td>
                                <button class="btn"><a href="../users/form.php?id=<?= hsc($row['id_utilisateur']) ?>">Modifier</a></button>
                                <button class="btn"><a href="../users/delete.php?id=<?= hsc($row['id_utilisateur']) ?>" onclick="return confirmationDeleteAdmin();">Supprimer</a></button>
                            </td>
                        </tr>
                    <?php }; ?>
                <?php }; ?>
            </tbody>
        </table>
    </div>
    <button class="btn">
        <a href="../users/ajouter_user.php">Ajouter un Utilisateur</a>
    </button>
</section>

<section class="users" id="users">
    <h2>Gestion des Utilisateurs</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <!-- <th>Rôle</th> -->
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recordset_role as $row) { ?>
                    <?php if ($row['id_role'] == 3) { ?>
                        <tr>

                            <td><?= hsc($row['id_utilisateur']); ?></td>
                            <td><?= hsc($row['nom_utilisateur']); ?></td>
                            <td><?= hsc($row['prenom_utilisateur']); ?></td>
                            <td><?= hsc($row['admin_mail']); ?></td>
                            <td><?= hsc($row['telephone_utilisateur']); ?></td>
                            <!-- <td><?= hsc($row['nom_role']); ?></td> -->
                            <!-- <td><?= hsc($row['date_inscription']); ?></td> -->
                            <td> <?php
                                    echo hsc($date->format('d/m/Y')); ?></td>
                            <td>
                                <button class="btn"><a href="../users/form.php?id=<?= hsc($row['id_utilisateur']) ?>">Modifier</a></button>
                                <button class="btn"><a href="../users/delete.php?id=<?= hsc($row['id_utilisateur']) ?>" onclick="return confirmationDeleteUser();">Supprimer</a></button>
                            </td>
                        </tr>
                    <?php }; ?>
                <?php }; ?>
            </tbody>
        </table>
    </div>
    <button class="btn">
        <a href="../users/ajouter_user.php">Ajouter un Utilisateur</a>
    </button>
</section>
<section class="coachs" id="coachs">
    <h2>Gestion des Coachs</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <!-- <th>Rôle</th> -->
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recordset_role as $row) { ?>
                    <?php if ($row['id_role'] == 2) { ?>
                        <tr>

                            <td><?= hsc($row['id_utilisateur']); ?></td>
                            <td><?= hsc($row['nom_utilisateur']); ?></td>
                            <td><?= hsc($row['prenom_utilisateur']); ?></td>
                            <td><?= hsc($row['admin_mail']); ?></td>
                            <td><?= hsc($row['telephone_utilisateur']); ?></td>
                            <!-- <td><?= hsc($row['nom_role']); ?></td> -->
                            <!-- <td><?= hsc($row['date_inscription']); ?></td> -->
                            <td> <?php $date = new DateTime($row['date_inscription']);
                                    echo hsc($date->format('d/m/Y')); ?></td>
                            <td>
                            <td>
                                <button class="btn"><a href="../users/form.php?id=<?= hsc($row['id_utilisateur']) ?>">Modifier</a></button>
                                <button class="btn"><a href="../users/delete.php?id=<?= hsc($row['id_utilisateur']) ?>" onclick="return confirmationDeleteCoach();">Supprimer</a></button>
                            </td>
                        </tr>
                    <?php }; ?>
                <?php }; ?>
            </tbody>
        </table>
    </div>
    <button class="btn">
        <a href="../users/ajouter_user.php">Ajouter un Coach</a>
    </button>
</section>


<?php require_once __DIR__ . '/../footer.php' ?>