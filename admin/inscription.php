<<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";

  $date = date('Y-m-d');

  require_once __DIR__ . '../../templates/header.php'
  ?>



  <section class="form-container creation">
  <h2>Créer un compte</h2>
  <form action="../users/process.php" method="POST">
    <label for="nom_utilisateur">Nom</label>
    <input type="text" id="nom_utilisateur" name="nom_utilisateur" />
    <label for="prenom_utilisateur">Prénom</label>
    <input type="text" id="prenom_utilisateur" name="prenom_utilisateur" required />
    <label for="admin_mail">Email</label>
    <input type="email" id="admin_mail" name="admin_mail" required>
    <label for="admin_password">Mot de passe</label>
    <input type="password" id="admin_password" name="admin_password" required />
    <label for="confirm_password">Confirmer votre mot de passe</label>
    <input type="password" id="confirm_password" name="confirm_password" required />
    <label for="telephone_utilisateur">Téléphone</label>
    <input type="number" id="telephone_utilisateur" name="telephone_utilisateur" required />
    <input type="hidden" name="id_role" value="3">
    <label for="date_inscription">Date d'inscription</label>
    <input type="date" name="date_inscription" id="date_inscription" value="<?= hsc($date) ?>">
    <button type="submit">Créer mon compte utilisateur</button>

  </form>
  </section>
  <?php require_once __DIR__ . '../../templates/footer.php' ?>