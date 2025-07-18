</main>
<section class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3 class="footer-title">Coordonnées</h3>
            <div class="footer-info">Club Canin "Educa Dog"</div>
            <div class="footer-info">Téléphone : 03-87-30-30-30</div>
            <div class="footer-info">
                Email:
                <a href="">toto@gmail.com</a>
            </div>
            <div class="footer-info">Adresse : 86 rue aux arenes, 57000 Metz</div>
        </div>
        <div class="footer-section">
            <h3 class="footer-title">Plan du site</h3>
            <div class="footer-info"><a href="../index.php">Accueil</a></div>
            <div class="footer-info">
                <a href="#nos_activite">Nos Activités</a>
            </div>
            <div class="footer-info">
                <a href="#nos_horaires">Horaires</a>
            </div>
            <div class="footer-info">
                <a href="#nous_trouver">Nous trouver</a>
            </div>
            <div class="footer-info">
                <a href="#story">Notre histoire</a>
            </div>
            <div class="footer-info">
                <a href="#nous_contacter">Nous contacter</a>
            </div>
        </div>
        <div class="footer-section">
            <h3 class="footer-title">Mentions légales</h3>
            <div class="footer-info">
                <a href="#">Politique de confidentialité</a>
            </div>
            <div class="footer-info"><a href="#">Mentions légales</a></div>
        </div>
        <div class="footer-section">
            <h3 class="footer-title">Club Canin "Educa Dog"</h3>
            <div class="logo-container">
                <img src="./interface_graphique/logo-dog-removebg-preview.png" alt="Educa dog" />
            </div>
        </div>
    </div>
    <p>
        Copyright &copy; - 2025 Club CANIN "Educa Dog"- Tous droits réservés.
    </p>
</section>

<?php if (basename($_SERVER["SCRIPT_FILENAME"]) == 'index.php'): ?>
    <script src="../index.js"></script>
<?php else: ?>

    <?php
    if (isset($_SESSION['is_logged']) && isset($_SESSION['role_id'])) {

        switch ($_SESSION['role_id']) {
            case '1':
                $redirectUrl = '../admin/administratif.js';
                break;
            case '2':
                $redirectUrl = '../coach.js';
                break;
            case '3':
                $redirectUrl = '../user.js';
                break;
            default:
                $redirectUrl = '../index.js';
                break;
        }
    }
    ?>
    <script src="<?php echo $redirectUrl; ?>"></script>
<?php endif; ?>
</body>

</html>