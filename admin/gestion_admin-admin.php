<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';



// Page actuelle (par défaut 1)
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Nombre d'éléments par page
$nbPerPage = isset($_GET['nbPerPage']) ? (int) $_GET['nbPerPage'] : 10;

// Évite la division par zéro
if ($nbPerPage <= 0) {
    $nbPerPage = 10;
}

$offset = ($currentPage - 1) * $nbPerPage;

$stmt = $db->prepare("
    SELECT COUNT(*) as total
    FROM utilisateur u
    JOIN utilisateur_role ur ON u.id_utilisateur = ur.id_utilisateur
    WHERE ur.id_role = :id_role
");
$stmt->execute(['id_role' => 1]);
$totalAdmin = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Calcul du nombre de pages
$nbPage = ceil($totalAdmin / $nbPerPage);





// recup tous les utilisateurs avec leur rôle
$sql = "SELECT * 
        FROM utilisateur u
        JOIN utilisateur_role ur ON u.id_utilisateur = ur.id_utilisateur
        JOIN role r ON ur.id_role = r.id_role
        WHERE ur.id_role = :id_role
        ORDER BY u.id_utilisateur DESC
        LIMIT :limit OFFSET :offset";

try {
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id_role', 1, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $recordset_role = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
    $recordset_role = []; // Pour éviter d'autres erreurs en cas d'échec
}



require_once __DIR__ . '../../templates/header.php';
require_once __DIR__ . '/../templates/sidebar.php';
?>


<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé des activités du Club Canin.</h2>
</div>

<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "gestion_admin-admin.php", "page", $nbPerPage); ?>
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
                    <th>Rôle</th>
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
                            <td><?= hsc($row['nom_role']); ?></td>
                            <!-- <td><?= hsc($row['date_inscription']); ?></td> -->
                            <td> <?php $date = new DateTime($row['date_inscription']);
                                    echo hsc($date->format('d/m/Y')); ?></td>
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

<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "gestion_admin-admin.php", "page", $nbPerPage); ?>
</div>


<?php require_once __DIR__ . '../../templates/footer.php' ?>