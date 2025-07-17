<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_coach.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour afficher
$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';

$query = "
SELECT 
    r.id_reservation,
    r.date_reservation,

    u.id_utilisateur,
    u.nom_utilisateur, 

    d.id_dog,
    d.nom_dog,             

    s.id_seance,
    s.date_seance,
    s.heure_seance,
    s.places_disponibles,
    s.duree_seance,
    s.statut_seance,
    c.categorie_acceptee,
    c.type_cours,
    c.nom_cours,

    coach.nom_utilisateur AS nom_coach

FROM reservation r
INNER JOIN seance s ON r.id_seance = s.id_seance
INNER JOIN cours c ON s.id_cours = c.id_cours
INNER JOIN utilisateur u ON r.id_utilisateur = u.id_utilisateur
INNER JOIN chien d ON r.id_dog = d.id_dog

-- Coach assigné à la séance (optionnel si tu as un coach pour chaque séance)
LEFT JOIN utilisateur coach ON coach.id_utilisateur = s.id_utilisateur
LEFT JOIN utilisateur_role ur ON coach.id_utilisateur = ur.id_utilisateur
LEFT JOIN role role_coach ON ur.id_role = role_coach.id_role AND role_coach.nom_role = 'coach'

ORDER BY r.date_reservation DESC;
";

$stmt = $db->query($query);
$recordset_reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);

// recup les inscriptions aux evenements
$stmt = $db->prepare("SELECT ie.*, e.nom_event, c.nom_dog   
                      FROM inscription_evenement ie
                      JOIN evenement e ON ie.id_event = e.id_event
                      JOIN chien c ON ie.id_dog = c.id_dog");
$stmt->execute();
$recordset_inscription_event = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';
?>



<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
</div>





<section class="reservations" id="reservations">
    <h2> Cours Réservés</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID </th>
                    <th>Catégorie</th>
                    <th>Nom du coach</th>
                    <th>Utilisateur</th>
                    <th>Type de cours</th>
                    <th>Nom Cours</th>
                    <th>Nom du chien</th>
                    <th>Date Séance</th>
                    <th>Heure Séance</th>
                    <th>Date Réservation</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($recordset_reservation as $reserv): ?>

                    <td><?= hsc($reserv['id_reservation']); ?></td>
                    <td><?= hsc($reserv['categorie_acceptee']); ?></td>
                    <td><?= hsc($reserv['nom_coach']); ?></td>
                    <td><?= hsc($reserv['nom_utilisateur']); ?></td>
                    <td><?= hsc($reserv['type_cours']); ?></td>
                    <td><?= hsc($reserv['nom_cours']); ?></td>
                    <td><?= hsc($reserv['nom_dog']); ?></td>
                    <td><?= hsc($reserv['date_seance']); ?></td>
                    <td><?= hsc($reserv['heure_seance']); ?></td>
                    <td><?= hsc($reserv['date_reservation']); ?></td>
                    <td>

                        <form method="post" action="./reservations/delete_reservation.php" style="display: inline;">
                            <input type="hidden" name="id_reservation" value="<?= hsc($reserv['id_reservation']); ?>">
                            <button type="submit" class="btn" onclick=" return confirmationDeleteReservation();">Supprimer</button>
                        </form>
                    </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>



<section class="inscriptions_event" id="inscriptions_event">
    <h2>Suivi des Inscriptions Évènements</h2>
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
                        <td><?= hsc($inscription['id_utilisateur']); ?></td>
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
</section>


<?php require_once __DIR__ . '/templates/footer.php' ?>