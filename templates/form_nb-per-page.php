 <form method="get" action="<?= hsc($_SERVER['PHP_SELF']) ?>" class="affichage_per-page">
     <label for="nbPerPage">Afficher par page :</label>
     <select name="nbPerPage" id="nbPerPage" onchange="this.form.submit()">
         <?php
            $options = [5, 10, 15, 25, 50, 100];
            foreach ($options as $opt) {
                $selected = $nbPerPage == $opt ? 'selected' : '';
                echo "<option value=\"$opt\" $selected>$opt</option>";
            }
            ?>
     </select>

     <!-- Pour garder la page actuelle si l'utilisateur change juste le nbPerPage -->
     <input type="hidden" name="page" value="<?= $currentPage ?>">
 </form>
 <div class="table-container">