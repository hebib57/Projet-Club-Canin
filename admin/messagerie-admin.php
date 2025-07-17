<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';


// Recup les messages reçus
// $sql = "SELECT m.*, u.prenom_utilisateur, u.nom_utilisateur 
// FROM message m
// JOIN utilisateur u ON m.id_expediteur = u.id_utilisateur
// WHERE m.id_destinataire = :id_utilisateur
// AND m.contenu IS NOT NULL 
// ORDER BY m.date_envoi DESC";

// $stmt = $db->prepare($sql);
// $stmt->execute([':id_utilisateur' => $_SESSION['user_id']]);
// $recordset_messages = $stmt->fetchAll();

// Page actuelle (par défaut 1)
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Compter le total des enregistrements
$stmtCount = $db->prepare("SELECT COUNT(*) as total FROM message WHERE id_destinataire = :id_utilisateur AND contenu IS NOT NULL ");
$stmtCount->execute([':id_utilisateur' => $_SESSION['user_id']]);
$totalItems = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

// Nombre d'éléments par page
$nbPerPage = isset($_GET['nbPerPage']) ? (int) $_GET['nbPerPage'] : 10;

// Évite la division par zéro
if ($nbPerPage <= 0) {
    $nbPerPage = 10;
}
// Calcul du nombre de pages
$nbPage = ceil($totalItems / $nbPerPage);


$offset = ($currentPage - 1) * $nbPerPage;

$stmt = $db->prepare("SELECT m.*, u.prenom_utilisateur, u.nom_utilisateur  
                        FROM message m
                        JOIN utilisateur u ON m.id_expediteur = u.id_utilisateur
                        WHERE m.id_destinataire = :id_utilisateur
                        AND m.contenu IS NOT NULL
                        ORDER BY m.date_envoi DESC
                        LIMIT :limit OFFSET :offset");


// Bind avec les bons types (essentiel pour LIMIT/OFFSET)
$stmt->bindValue(':id_utilisateur', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->bindValue(':limit', (int)$nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$recordset_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);


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


<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "messagerie-admin.php", "page", $nbPerPage); ?>
</div>

<section class="card-admin_messagerie" id="messagerie">
    <h2>Messagerie</h2>
    <div class="card-header">
        <button class="btn"><a href="../messages/message_send.php">Nouveau message</a></button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>De</th>
                    <th>Sujet</th>
                    <th>Date</th>
                    <th>Actions</th>
                    <th>lu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recordset_messages as $msg): ?>
                    <tr>
                        <td><?= hsc($msg['prenom_utilisateur'] . ' ' . $msg['nom_utilisateur']) ?></td>
                        <td><?= substr(hsc($msg['sujet_message']), 0, 30) ?>...</td>
                        <td><?= hsc(date('d/m/Y H:i', strtotime($msg['date_envoi']))) ?></td>
                        <td>
                            <button><a class="btn" href="../messages/message_read.php?id_message=<?= hsc($msg['id_message']) ?>">Lire</a></button>
                            <button><a class="btn" href="../messages/message_delete.php?id=<?= hsc($msg['id_message']) ?>" onclick="return confirmationDeleteMessage();">Supprimer</a></button>
                        </td>
                        <td><?= hsc($msg['lu'] ? 'Oui' : 'Non') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</section>

<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "messagerie-admin.php", "page", $nbPerPage); ?>
</div>

<?php require_once __DIR__ . '/../footer.php' ?>