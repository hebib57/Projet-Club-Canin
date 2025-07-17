<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";
var_dump($_GET);
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;


$utilisateur = "";
$nom = "";
$prenom = "";
$email = "";
$password = "";
$phone = "";
// $id_role = "";
$date = date("Y-m-d");


if ($id_utilisateur) {
    $stmt = $db->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur");
    $stmt->bindValue(":id_utilisateur", $id_utilisateur);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        $utilisateur = $row['id_utilisateur'];
        $nom = $row['nom_utilisateur'];
        $prenom = $row['prenom_utilisateur'];
        $email = $row['admin_mail'];
        $password = $row['admin_password'];
        $confirm_password = $password;
        $phone = $row['telephone_utilisateur'];
        $id_role = $row['id_role'];
        $date = $row['date_inscription'];
    };
};

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



<section class="form-container creation">
    <h2>Paramètre du compte</h2>
    <form action="../users/process.php" method="POST">

        <label for="nom_utilisateur">Nom</label>
        <input type="text" id="nom_utilisateur" name="nom_utilisateur" value="<?= hsc($nom) ?>" />

        <label for="prenom_utilisateur">Prénom</label>
        <input type="text" id="prenom_utilisateur" name="prenom_utilisateur" value="<?= hsc($prenom) ?>" required />

        <label for="admin_mail">Email</label>
        <input type="email" id="admin_mail" name="admin_mail" value="<?= hsc($email) ?>" required>

        <label for="admin_password">Mot de passe</label>
        <input type="password" id="admin_password" name="admin_password" value="<?= hsc($password) ?>" required />

        <label for="confirm_password">Confirmer votre mot de passe</label>
        <input type="password" id="confirm_password" name="confirm_password" value="<?= hsc($confirm_password) ?>" required />

        <label for="telephone_utilisateur">Téléphone</label>
        <input type="tel" id="telephone_utilisateur" name="telephone_utilisateur" value="<?= hsc($phone) ?>" required />


        <label for="date_inscription">Date d'inscription</label>
        <input type="date" name="date_inscription" id="date_inscription" value="<?= hsc($date) ?>">

        <input type="hidden" name="id_utilisateur" value="<?= hsc($utilisateur) ?>">
        <button type="submit">Modifier mon compte utilisateur</button>

    </form>
</section>


<?php require_once __DIR__ . '/../footer.php' ?>