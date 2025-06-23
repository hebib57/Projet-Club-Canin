<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/config.php";

$stmt = $db->prepare("SELECT * FROM categorie");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

//--------------------------------------------------------------------------MODIFICATION DOG-------------------------------------------------------------------//
// Vérifier si le formulaire a été soumis
if (isset($_POST["formCU"]) && $_POST["formCU"] == "ok") {





    // Si l'ID chien est égal à 0 => insertion, sinon c'est une mise à jour.
    try {


        $id_dog = $_POST["id_dog"];

        if ($id_dog == "0") {
            $date_naissance = $_POST['date_naissance'] ?? null;
            // $age_dog = (int) $_POST["age_dog"];
            // $id_categorie = null;
            // $nom_categorie = null;

            if ($date_naissance) {
                $dateNaissance = new DateTime($date_naissance);
                $dateToday = new DateTime();

                $difference = $dateToday->diff($dateNaissance);
                $age_dog = ($difference->y * 12) + $difference->m;
            } else {
                $age_dog = null;
            }

            $id_categorie = null;
            $nom_categorie = null;

            if ($age_dog !== null) {
                foreach ($categories as $cat) {
                    $min = $cat['age_min_mois'];
                    $max = $cat['age_max_mois'] ?? 9999;

                    if ($age_dog >= $min && $age_dog <= $max) {
                        $nom_categorie = $cat['nom_categorie'];
                        $id_categorie = $cat['id_categorie'];
                        break;
                    }
                }
            }



            // Ajout d'un nouveau chien dans la base de données
            $stmt = $db->prepare("INSERT INTO chien (
                    
                    nom_dog,
                    date_naissance,
                    age_dog,
                    id_race,
                    sexe_dog,
                    id_utilisateur,
                    date_inscription,
                    id_categorie,
                    categorie
                ) VALUES (
                  
                    :nom_dog,
                    :date_naissance,
                    :age_dog,
                    :id_race,
                    :sexe_dog,
                    :id_utilisateur,
                    :date_inscription,
                    :id_categorie,
                    :categorie              
                )");


            // Liaison des valeurs avec la requête SQL

            $stmt->bindValue(":nom_dog", $_POST["nom_dog"]);
            $stmt->bindValue(":date_naissance", $date_naissance);
            $stmt->bindValue(":age_dog", $age_dog);
            $stmt->bindValue(":id_race", $_POST["id_race"]);
            $stmt->bindValue(":sexe_dog", $_POST["sexe_dog"]);
            $stmt->bindValue(":categorie", $nom_categorie);
            $stmt->bindValue(":id_categorie", $id_categorie);
            $stmt->bindValue(":id_utilisateur", $_POST["id_utilisateur"]);
            $stmt->bindValue(":date_inscription", $_POST["date_inscription"]);
            $stmt->execute();
            $id = $db->lastInsertId(); // Récupère l'ID du nouveeau chien


            // echo "Chien ajouté avec succès!";
        } else {
            //modification

            $date_naissance = $_POST['date_naissance'] ?? null;

            if ($date_naissance) {
                $dateNaissance = new DateTime($date_naissance);
                $dateToday = new DateTime();

                $difference = $dateToday->diff($dateNaissance);
                $age_dog = ($difference->y * 12) + $difference->m;
            } else {
                $age_dog = null;
            }


            $stmt = $db->prepare("UPDATE chien SET
                    
                    nom_dog = :nom_dog,
                    date_naissance = :date_naissance,
                    age_dog = :age_dog,
                    id_race = :id_race,
                    sexe_dog = :sexe_dog,
                    id_utilisateur = :id_utilisateur,
                    date_inscription = :date_inscription                    
                    WHERE id_dog = :id_dog");




            // Liaison des valeurs avec la requête SQL

            $stmt->bindValue(":nom_dog", $_POST["nom_dog"]);
            $stmt->bindValue(":date_naissance", $date_naissance);
            $stmt->bindValue(":age_dog", $age_dog);
            $stmt->bindValue(":id_race", $_POST["id_race"]);
            $stmt->bindValue(":sexe_dog", $_POST["sexe_dog"]);
            $stmt->bindValue(":id_utilisateur", $_POST["id_utilisateur"]);
            $stmt->bindValue(":date_inscription", $_POST["date_inscription"]);
            $stmt->bindValue(":id_dog", $id_dog); // Liaison de l'ID utilisateur
            $stmt->execute();


            $id = $id_dog;
        }
        // if ($stmt->execute()) {
        // $id = $_POST['id_dog'];

        // On vérifier qu'une image a été envoyé
        if (isset($_FILES["photo_dog"])) {

            // On vérifie que le transfert de fichier ne renvoie pas d'erreur (donc que le fichier soit bien parvenu)
            if ($_FILES["photo_dog"]["error"] == 0) {
                $extension = strtolower(pathinfo($_FILES["photo_dog"]["name"], PATHINFO_EXTENSION));
                $pathFile = $_SERVER["DOCUMENT_ROOT"] . "/upload/";
                // On vérifie si le type de l'image correspond bien aux types accepté par notre programme (sans prendre en compte l'extension de base qui peut être fausse)
                if ($_FILES["photo_dog"]["type"] == "image/" . str_replace("jpg", "jpeg", $extension) && in_array($extension, ["jpg", "jpeg", "png", "gif", "webp"])) {

                    if ($id > 0) {


                        $stmt = $db->prepare("SELECT photo_dog FROM chien WHERE id_dog = :id_dog AND photo_dog IS NOT NULL AND photo_dog <> ''");
                        $stmt->bindValue(":id_dog", $id);
                        $stmt->execute();
                        if ($row = $stmt->fetch()) {

                            foreach (IMG_CONFIG as $prefix => $value) { //supprime si le fichier existe
                                if (file_exists($pathFile . $prefix . "_" . $row["photo_dog"])) {
                                    unlink($pathFile . $prefix . "_" . $row["photo_dog"]);
                                }
                            }
                        }
                    }

                    $filename = "club-canin" . $_POST["id_race"] . "-" . $_POST["nom_dog"];
                    $filename = cleanFilename($filename);



                    // On boucle dans le dossier upload tant que le nom de fichier recherché est déjà pris et on veut le faire pour chaque extension de fichier (foreach)
                    $is_found = false;
                    $count = 1;
                    while ($is_found) {
                        $is_found = false;
                        foreach (IMG_CONFIG as $key => $value) {
                            if (file_exists($pathFile . $key . "_" . $filename . ($count > 1 ? "(" . $count . ")" : "") . ".webp")) {
                                $is_found = true;
                                break;
                            }
                        }
                        $is_found ? $count++ : "";
                    }
                    if ($count > 1) {
                        $filename .= "(" . $count . ")";
                    }


                    // On positionne alors le fichier avec son nouveau nom dans notre dossier
                    move_uploaded_file($_FILES["photo_dog"]["tmp_name"], $pathFile . $filename . "." . $extension);

                    // On initialise les variables qui vont changer à chque boucle pour retravailler l'image qui vient d'être créé (par souci d'optimisation)
                    $srcPrefix = "";
                    $srcExtension = $extension;

                    // On veut ensuite créer une version de l'image pour chaque type d'extension défini, donc on exécute la suite pour chaque extension du tableau
                    foreach (IMG_CONFIG as $prefix => $info) {

                        // On va ensuite chercher l'image pour pouvoir la redimensionner
                        $srcSize = getimagesize($pathFile . $srcPrefix . $filename . "." . $srcExtension);

                        // On récupère les dimensions 
                        $srcWidth = $srcSize[0];
                        $srcHeight = $srcSize[1];

                        // On définit les points de départ de l'image source à redimensionner et et l'image cible.
                        $srcX = 0;
                        $srcY = 0;

                        // On définit les points de départ de l'image de destination à redimensionner et et l'image cible.
                        $destX = 0;
                        $destY = 0;

                        // On va chercher ici dans le tableau info les largeur et hauteur relatif au préfix consulté
                        $destWidth = $info["width"];
                        $destHeight = $info["height"];

                        // On vérifie ici si l'image a besoin d'être rogné
                        if (!$info["crop"]) {

                            // On vérifier si l'image est au format protrait ou paysage pour définir la contraite
                            if ($srcWidth > $srcHeight) {
                                $destHeight = round(($srcHeight * $destWidth) / $srcWidth);

                                // On va également vérifier que l'image (EN LARGEUR) importé ne soit pas plus petite que la taille attendue, si c'est le cas on fais correspondre les dimensions de l'image de destination avec les dimensions d'origine
                                if ($srcWidth <= $destWidth) {
                                    $destWidth = $srcWidth;
                                    $destHeight = $srcHeight;
                                }
                            } else {
                                $destWidth = round(($srcWidth * $destHeight) / $srcHeight);

                                // On vérifie EN LONGUEUR que l'image d'origine ne soit pas plus petite que la taille attendue
                                if ($srcWidth <= $destWidth) {
                                    $destWidth = $srcWidth;
                                    $destHeight = $srcHeight;
                                }
                            }

                            // Si la variable "crop" est sur true, alors l'image a besoin d'être rogné et une autre procédure s'applique
                        } else {

                            // Pour définir le point de découpe sur l'image d'origine, on cherche la largeur (ou hauteur) de l'image à supprimer et on la diviser par 2.
                            // (($srcWidth - $srcHeight) / 2), 0, $srcHeight, $srcWidth

                            // On vérifie de nouveau que l'image est au format paysage
                            if ($srcWidth > $srcHeight) {
                                $srcX = round(($srcWidth - $srcHeight) / 2);
                                $srcWidth = $srcHeight;

                                // Sinon elle est au format portrait
                            } else {
                                $srcY = round(($srcHeight - $srcWidth) / 2);
                                $srcHeight = $srcWidth;
                            }
                        }


                        // On définit une toile vide qui ne contient pas de pixel
                        $dest = imagecreatetruecolor($destWidth, $destHeight);



                        // OU PLUS SIMPLEMENT
                        $src = ("imagecreatefrom" . str_replace("jpg", "jpeg", $srcExtension))($pathFile . $srcPrefix . $filename . "." . $srcExtension);

                        // On effectue une copie de l'image uploadé
                        imagecopyresampled($dest, $src, $destX, $destY, $srcX, $srcY, $destWidth, $destHeight, $srcWidth, $srcHeight);

                        // Et on l'enregistre au format webp
                        imagewebp($dest, $pathFile . $prefix . "_" . $filename . ".webp", 100);

                        // On applique l'extension webp à l'extension à rechercher pour itentifier l'image qui vient d'être créé
                        $srcExtension = "webp";

                        // On ne change le préfix pour l'image suivante que si l'image qu'on vient de traiter n'est pas une image rogné.
                        if (!$info["crop"]) {
                            $srcPrefix = $prefix . "_";
                        }
                    }
                }

                if (file_exists($pathFile . $filename . "." . $extension)) {
                    unlink($pathFile . $filename . "." . $extension);
                }
                $stmt = $db->prepare("UPDATE chien SET photo_dog=:photo_dog WHERE id_dog=:id_dog");
                $stmt->bindValue(":photo_dog", $filename . ".webp");
                $stmt->bindValue(":id_dog", $id);

                $stmt->execute();
            }
        }


        $message = ($id_dog == "0") ? "Chien ajouté avec succès" : "Chien modifié avec succès";




        switch ($_SESSION['role_name']) {
            case 'admin':
                $redirectUrl = '../admin/administratif.php#dogs';
                break;
            case 'utilisateur':
                $redirectUrl = '../user.php#dogs';
                break;
            default:
                $redirectUrl = '../index.php';
        }

        echo "<script>alert('" . hsc($message) . "'); window.location.href = '$redirectUrl';</script>";
        //     } else {
        //         $message = "Erreur lors de la " . ($id_dog == "0" ? "création" : "modification") . " du chien";
        //         echo "<script>alert('" . hsc($message) . "'); window.location.href = '../index.php#dogs';</script>";
        //     }
        // }
    } catch (PDOException $e) {
        error_log($e->getMessage()); //  Log erreur  serveur

        echo "<script>alert('Erreur lors de l\'enregistrement du chien'); window.location.href = '../index.php';</script>";
    }
}
