<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_coach.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour afficher
$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';

// ercup les messages reçus
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

require_once __DIR__ . '/templates/header.php'
?>



<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
</div>


<div class="sidebar">
    <button class="sidebar__burger-menu-toggle" id="sidebarMenu">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </button>
    <div class="sidebar-header">
        <div class="user-avatar">C</div>
        <div class="user-info">
            <h3><?= hsc(ucfirst($prenom_utilisateur)) ?></h3>
        </div>
    </div>

    <ul class="menu-list">
        <li><a href="coach.php">Tableau de bord <img src="../interface_graphique/online-reservation.png" alt="dashboard" width="40px
          "></a></li>
        <li><a href="cours_programmes-coach.php">Gestion des Cours <img src="../interface_graphique/training-program.png" alt="cours" width="40px
          "></a></li>
        <li><a href="event_programmes-coach.php">Gestion des Évènements <img src="../interface_graphique/banner.png" alt="events" width="40px
          "></a></li>
        <li><a href="reservations-coach.php">Suivi des réservations <img src="../interface_graphique/reservation.png" alt="reservations" width="40px
          "></a></li>
        <li><a href="evaluations-coach.php">Evaluation <img src="../interface_graphique/img-eval.png" alt="evaluations" width="40px
          "></a></li>
        <li><a href="messagerie-coach.php">Messagerie <img src="../interface_graphique/mail.png" alt="messagerie" width="40px
          "></a></li>
        <li><a href="#">Paramètres du compte <img src="../interface_graphique/admin-panel.png" alt="parametres" width="40px
          "></a></li>
        <li><a href="/logout.php">Déconnexion <img src="../interface_graphique/img-exit.png" alt="logout" width="40px
          "></a></li>
    </ul>
</div>
<!-- <div>
        <span id="date">
        </span>
    </div> -->

<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "messagerie-coach.php", "page", $nbPerPage); ?>
</div>

<section class="card-coach_messagerie" id="messagerie">
    <div>
        <h2>Messagerie</h2>
        <div class="card-header">
            <button class="btn"><a href="../messages/message_send.php">Nouveau message</a></button>
        </div>

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
                        <td><?= hsc($msg['prenom_utilisateur'] . ' ' . hsc($msg['nom_utilisateur'])) ?></td>
                        <td><?= substr(hsc($msg['sujet_message']), 0, 30) ?>...</td>
                        <td><?= hsc(date('d/m/Y H:i', strtotime(hsc($msg['date_envoi'])))) ?></td>


                        <td>
                            <button><a class="btn" href="../messages/message_read.php?id_message=<?= hsc($msg['id_message']) ?>">Lire</a></button>
                            <button><a class="btn" href="../messages/message_delete.php?id=<?= hsc($msg['id_message']) ?>" onclick="return confirmationDeleteMessage();">Supprimer</a></button>

                        </td>
                        <td><?= hsc($msg['lu'] ? 'Oui' : 'Non') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
</section>
<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "messagerie-coach.php", "page", $nbPerPage); ?>
</div>



<?php require_once __DIR__ . '/templates/footer.php' ?>