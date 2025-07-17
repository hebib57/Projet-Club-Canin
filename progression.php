<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_user.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

$utilisateur = [];

//recup chiens utilisateur
$stmt = $db->prepare("SELECT c.id_dog, c.nom_dog, c.date_naissance, r.nom_race, c.age_dog, c.photo_dog, c.sexe_dog, c.date_inscription, c.categorie
                       FROM chien AS c 
                       INNER JOIN race AS r                       
                       ON c.id_race = r.id_race  

                       WHERE c.id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$dogs = $stmt->fetchAll(PDO::FETCH_ASSOC);


//recup commentaires
$stmt = $db->prepare("
        SELECT c.*, u.prenom_utilisateur, u.nom_utilisateur, d.nom_dog 
        FROM commentaire c
        JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
        JOIN chien d ON c.id_dog = d.id_dog
        
        ORDER BY c.date_commentaire DESC
      ");
$stmt->execute();
$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);


$commentaire_dog = [];


require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';
?>




<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
</div>






<section class="suivi" id="suivi">
    <h2>Suivi et Progression</h2>
    <div class="selection">
        <label for="dog-select">Sélectionner un chien :</label>
        <select name="id_dog" id="id_dog_user" required>
            <option value="">-- Sélectionner un chien --</option>
            <?php foreach ($dogs as $dog): ?>
                <option
                    value="<?= hsc($dog['id_dog']) ?>"
                    data-nom="<?= hsc($dog['nom_dog']) ?>"
                    data-race="<?= hsc($dog['nom_race']) ?>"
                    data-categorie="<?= hsc($dog['categorie']) ?>"
                    data-age="<?= hsc($dog['age_dog']) ?>"
                    data-date-naissance="<?= hsc($dog['date_naissance']) ?>"
                    data-sexe="<?= hsc($dog['sexe_dog']) ?>"
                    data-date-inscription="<?= hsc($dog['date_inscription']) ?>">
                    <?= hsc($dog['nom_dog']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="dog-info" class="dog-info" style="display: none; margin-top: 20px;">
        <h3>Informations sur le chien</h3>
        <p><strong>Nom :&nbsp;</strong><span id="info-nom"></span></p>
        <p><strong>Catégorie :&nbsp; </strong><span id="info-categorie"></span></p>
        <p><strong>Râce :&nbsp; </strong><span id="info-race"></span></p>
        <p><strong>Age : &nbsp;</strong><span id="info-age"></span>&nbsp; mois</p>
        <p><strong>Date de naissance :&nbsp;</strong><span id="info-date-naissance"></span></p>
        <p><strong>Sexe :&nbsp;</strong><span id="info-sexe"></span></p>
        <p><strong>Date d'inscription :&nbsp;</strong><span id="info-date-inscription"></span></p>
    </div>

    <div class="progress">
        <h3>Suivi des progrès</h3>
        <table class="progress-table ">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Coach</th>
                    <th>Nom du cours</th>
                    <th>Note</th>
                    <th>Commentaires</th>
                    <th>Progrès</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($commentaires as $commentaire) {

                    $id_dog = $commentaire['id_dog'];
                    if (!isset($commentaire_dog[$id_dog])) {
                        $commentaire_dog[$id_dog] = [];
                    }

                    $commentaire_dog[$id_dog][] = [
                        'date' => date('d/m/Y', strtotime($commentaire['date_commentaire'])),
                        'coach' => $commentaire['prenom_utilisateur'] . ' ' . $commentaire['nom_utilisateur'],
                        'nom_cours' => $commentaire['nom_cours'],
                        'note' => $commentaire['note'],
                        'progres' => $commentaire['progres'],
                        'commentaire' => $commentaire['commentaire']
                    ];
                } ?>
            </tbody>
        </table>
    </div>
</section>
<!-- CONSTANTE COMMENTAIRE -->
<script>
    const commentaireDog = <?= json_encode($commentaire_dog) ?>
</script>

<?php require_once __DIR__ . '/templates/footer.php' ?>