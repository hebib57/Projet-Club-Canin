<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

// initialisation du formulaire 
$id_event = "";
$nom_event = "";
$date_event = "";
$heure_event = "";
$places_disponibles = "";



if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $stmt = $db->prepare("SELECT * FROM evenement WHERE id_event = :id_event");
    $stmt->bindValue(":id_event", $_GET["id"]);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        $id_event = $row['id_event'];
        $nom_event = $row['nom_event'];
        $date_event = $row['date_event'];
        $heure_event = $row['heure_event'];
        $places_disponibles = $row['places_disponibles'];
    };
};



$stmt = $db->prepare("SELECT * FROM evenement ");
$stmt->execute();
$races = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../header.php'

?>


<section>

    <div class="modification">
        <h2>Modifier Evènement</h2>
        <form class="modif" action="process.php" method="post" enctype="multipart/form-data"><!--enctype sert pour le type file-->
            <label for="nom_event">Nom de l'évènement</label>
            <input type="text" name="nom_event" id="nom_event" value="<?= hsc($nom_event) ?>">
            <label for="date_event">Date de l'évènement</label>
            <input type="date" name="date_event" id="date_event" value="<?= hsc($date_event) ?>">
            <label for="heure_event">Heure de l'évènement</label>
            <input type="time" name="heure_event" id="heure_event" value="<?= hsc($heure_event) ?>">
            <label for="places_disponibles">Places disponibles</label>
            <input type="number" name="places_disponibles" id="places_disponibles" value="<?= hsc($places_disponibles) ?>">
            <input type="hidden" name="id_event" id="id_event" value="<?= hsc($id_event) ?>">
            <input type="hidden" name="formCU" value="ok">
            <input class="btn__modif" type="submit" value="Enregistrer">
        </form>
        <?php
        switch ($_SESSION['role_name']) {
            case 'admin':
                $redirectUrl = '../admin/administratif.php#events';
                break;
            case 'coach':
                $redirectUrl = '../event_programmes-coach.php';
                break;
            // case 'utilisateur':
            //     $redirectUrl = '../user.php#cours_programmé';
            //     break;
            default:
                $redirectUrl = '../index.php';
        }
        ?>
        <button class="btn2__modif">
            <a href="<?= $redirectUrl ?>">Retour</a>
        </button>




        <!-- <button class="btn2__modif"><a href="../admin/administratif.php#cours">Retour</a></button> -->
    </div>
</section>

<?php require_once __DIR__ . '/../footer.php' ?>