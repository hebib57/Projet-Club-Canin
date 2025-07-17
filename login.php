<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

if (isset($_POST['admin_mail']) && isset($_POST['admin_password'])) {

  $stmt = $db->prepare("
  SELECT u.*, ur.id_role, r.nom_role
  FROM utilisateur u
  LEFT JOIN utilisateur_role ur ON u.id_utilisateur = ur.id_utilisateur
  LEFT JOIN role r ON ur.id_role = r.id_role
  WHERE u.admin_mail = :admin_mail
");

  $stmt->bindValue(':admin_mail', $_POST['admin_mail']);
  $stmt->execute();



  if ($utilisateur = $stmt->fetch(PDO::FETCH_ASSOC)) {

    if (password_verify($_POST['admin_password'], $utilisateur['admin_password'])) {

      session_start();

      $_SESSION["is_logged"] = "oui";
      $_SESSION["user_id"] = $utilisateur["id_utilisateur"];
      $_SESSION["role_id"] = $utilisateur["id_role"];
      $_SESSION["role_name"] = $utilisateur["nom_role"];
      $_SESSION["user_email"] = $utilisateur["admin_mail"];
      $_SESSION['prenom_utilisateur'] = $utilisateur['prenom_utilisateur'];


      switch ($utilisateur["id_role"]) {
        case 1:
          header("Location: /admin/administratif.php");
          break;
        case 2:
          header("Location: /coach.php");
          break;
        case 3:
          header("Location: /user.php");
          break;
        default:
          echo "Rôle inconnu. Contactez un administrateur.";
          exit;
      }


      exit();
    } else {
      echo "id ou mot de passe incorrect;";
    }
  } else {
    echo "id ou mot de passe incorrect;";
  }
}

require_once __DIR__ . '/templates/header.php'
?>





<section class="form-container creation">
  <h2>Se connecter</h2>
  <form action="login.php" method="POST">
    <label for="admin_mail">Email</label>
    <input type="email" id="admin_mail" name="admin_mail" />
    <label for="admin_password">Mot de passe</label>
    <input type="password" id="admin_password" name="admin_password" />
    <button type="submit" class="button" value="ok">Se connecter
    </button>
  </form>
</section>
<?php require_once __DIR__ . '/templates/footer.php' ?>