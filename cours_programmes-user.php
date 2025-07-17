<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_user.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

// Vérifier si l'utilisateur est connecté
$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

$utilisateur = [];

//recup des seances
// $stmt = $db->prepare("SELECT s.id_seance, s.id_cours, c.nom_cours, u.nom_utilisateur, u.prenom_utilisateur, s.date_seance, s.heure_seance, s.places_disponibles, c.id_cours, c.categorie_acceptee
//                       FROM seance s 
//                       LEFT JOIN cours c ON s.id_cours = c.id_cours
//                       LEFT JOIN utilisateur u ON u.id_utilisateur = s.id_utilisateur
//                     ");
// $stmt->execute();
// $recordset_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

//recup chiens utilisateur
$stmt = $db->prepare("SELECT c.id_dog, c.nom_dog, c.date_naissance, r.nom_race, c.age_dog, c.photo_dog, c.sexe_dog, c.date_inscription, c.categorie
                       FROM chien AS c 
                       INNER JOIN race AS r                       
                       ON c.id_race = r.id_race  
                      
                       WHERE c.id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$dogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

$stmt = $db->prepare("SELECT s.id_seance, s.id_cours, c.nom_cours, u.nom_utilisateur, u.prenom_utilisateur, s.date_seance, s.heure_seance, s.places_disponibles, c.id_cours, c.categorie_acceptee
                      FROM seance s 
                      LEFT JOIN cours c ON s.id_cours = c.id_cours
                      LEFT JOIN utilisateur u ON u.id_utilisateur = s.id_utilisateur
                      LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recordset_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';

?>



<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
</div>







<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "cours_programmes-user.php", "page", $nbPerPage); ?>
</div>


<section class="cours_programmé" id="cours_programmé">
    <h2>Cours programmés</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Catégorie</th>
                <th>Nom du Cours</th>
                <th>Nom Coach</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Places disponibles</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recordset_cours as $row) { ?>
                <tr>

                    <td><?= hsc($row['id_seance']); ?></td>
                    <td><?= hsc($row['categorie_acceptee']); ?></td>
                    <td><?= hsc($row['nom_cours']); ?></td>
                    <td><?= hsc($row['prenom_utilisateur'] . ' ' . $row['nom_utilisateur']); ?></td>
                    <td><?= hsc($row['date_seance']); ?></td>
                    <td><?= hsc($row['heure_seance']); ?></td>
                    <td><?= hsc($row['places_disponibles']); ?></td>

                    <td>
                        <form method="post" action="reservations/process_reservation-u.php" style="display: inline;">
                            <input type="hidden" name="id_dog" value="<?= hsc($row["id_seance"]); ?>">
                            <input type="hidden" name="id_cours" value="<?= hsc($row["id_cours"]); ?>">
                            <?php if (!in_array($row["id_cours"], $utilisateur)): ?>
                                <button type="button" class="btn" onclick="openCoursModal(<?= hsc($row['id_cours']) ?>)">S'inscrire</button>
                            <?php else: ?>
                                <button type="submit" name="action" value="desinscrire" class="btn">Se désinscrire</button>
                            <?php endif;
                            ?>

                        </form>
                    </td>

                </tr>
            <?php }; ?>
        </tbody>
    </table>

</section>
<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "cours_programmes-user.php", "page", $nbPerPage); ?>
</div>
<!-- Modal pour choisir un chien pour la réservation d'un cours-->
<div id="reservationModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeCoursModal()">&times;</span>
        <h3>Choisissez un chien pour ce cours</h3>

        <form method="post" action="../reservations/process_reservation-u.php">
            <input type="hidden" name="id_cours" id="modal_id_cours">

            <input type="hidden" name="action" value="inscrire">
            <label for="id_dog">Votre chien :</label>
            <select name="id_dog" id="id_dog_reservation" required>
                <option value="">-- Sélectionner un chien --</option>

                <?php
                foreach ($dogs as $dog): ?>
                    <option value="<?= hsc($dog['id_dog']) ?>"><?= hsc($dog['nom_dog']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="action" value="inscrire" class="btn">Confirmer l'inscription</button>
            <button type="button" class="btn" onclick="closeCoursModal()">Annuler</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php' ?>