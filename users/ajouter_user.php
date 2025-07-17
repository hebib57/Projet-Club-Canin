<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

require_once __DIR__ . '../../templates/header.php'
?>




<section class="form-container creation">
  <h2>Créer un compte utilisateur</h2>
  <form action="../users/process.php" method="POST">
    <label for="nom_utilisateur">Nom</label>
    <input type="text" id="nom_utilisateur" name="nom_utilisateur" required>
    <label for="prenom_utilisateur">Prénom</label>
    <input type="text" id="prenom_utilisateur" name="prenom_utilisateur" required />
    <label for="admin_mail">Email</label>
    <input type="email" id="admin_mail" name="admin_mail" required />
    <label for="password">Mot de passe</label>
    <input type="password" id="admin_password" name="admin_password" required />
    <label for="telephone_utilisateur">Téléphone</label>
    <input type="number" id="telephone_utilisateur" name="telephone_utilisateur" required />
    <label for="text">Rôle</label>
    <select name="id_role" required>
      <option value="1">Admin</option>
      <option value="2">Coach</option>
      <option value="3">Utilisateur</option>
    </select>
    <button type="submit">Ajouter ce compte utilisateur</button>
  </form>
  <?php
  switch ($_SESSION['role_name']) {
    case 'admin':
      $redirectUrl = '../admin/administratif.php#users';
      break;
    case 'coach':
      $redirectUrl = '../coach.php#users';
      break;
    case 'utilisateur':
      $redirectUrl = '../user.php#cusers';
      break;
    default:
      $redirectUrl = '../index.php';
  }
  ?>
  <button class="btn2__modif">
    <a href="<?= $redirectUrl ?>">Retour</a>
  </button>
</section>
<?php require_once __DIR__ . '../../templates/footer.php' ?>