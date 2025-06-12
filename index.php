<?php session_start(); ?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Club CANIN - Accueil</title>
  <link rel="stylesheet" href="../custom.css" />
</head>

<body>
  <header class="header">
    <div class="overlay"></div>
    <div class="logo">
      <img src="../interface_graphique/logo-dog-removebg-preview.png" alt="logo" />
    </div>
    <nav class="navbar">
      <ul class="navbar__burger-menu--closed">
        <li><a href="index.php">Accueil</a></li>
        <li><a href="#nos_activite">Activités</a></li>
        <li><a href="#nos_horaires">Horaires</a></li>
        <li><a href="#story">Notre histoire</a></li>
        <li><a href="#nous_trouver">Nous trouver</a></li>
        <li><a href="#nous_contacter">Nous contacter</a></li>
        <li><a href="./admin/login.php" class="button">Se connecter</a></li>
      </ul>
    </nav>

    <button class="navbar__burger-menu-toggle" id="burgerMenu">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </button>
  </header>

  <section class="hero" id="accueil">
    <div class="hero-overlay"></div>
    <div class="bienvenue">
      <h1>Bienvenue au Club CANIN "Educa Dog"</h1>
      <div class="img_logo">
        <img src="./interface_graphique/logo-dog-removebg-preview.png" alt="logo" />
      </div>
      <p>
        Offrez à votre chien l'éducation qu'il mérite avec nos cours adaptés à
        son âge et ses besoins.
      </p>
      <p>
        Passionnés d'éducation canine depuis 2020, notre club vous accueille
        dans une ambiance conviviale et professionnelle. <br />Notre équipe
        d'éducateurs qualifiés vous accompagne dans l'éducation et
        l'épanouissement de votre compagnon à quatre pattes.
      </p>
    </div>

    <?php
    if (!isset($_SESSION['is_logged'])) {
      echo '<a href="./admin/inscription.php" class="button">S\'inscrire maintenant</a>';
    } else {
      switch ($_SESSION['role_name']) {
        case 'admin':
          $redirectUrl = '../admin/administratif.php';
          break;
        case 'coach':
          $redirectUrl = '../coach.php';
          break;
        case 'utilisateur':
          $redirectUrl = '../user.php';
          break;
        default:
          $redirectUrl = '../index.php';
      }
      echo '<a href="' . $redirectUrl . '" class="button">Mon Compte</a>';
    } ?>




  </section>
  <section class="nos_activite" id="nos_activite">
    <h2>Nos Activités</h2>
    <p>
      "Rejoignez Nous et offrez à votre compagnon une expérience enrichissante
      avec des activités ludiques et éducatives, <br />où il pourra développer
      ses compétences, renforcer sa sociabilité et s'amuser tout en apprenant
      !"
    </p>
    <div class="activite">
      <div class="activite_card">
        <img src="./interface_graphique/image.jpg" alt="dog" class="img_card" />
        <div class="card-body">
          <h5 class="activite_card-title">Ecole du chiot</h5>
          <p class="activite_card-text">
            Sociabilisation et apprentissages de base
          </p>
        </div>
        <div class="card-back">
          <h3>L'école du Chiot</h3>
          <p>Au programme :</p>
          <ul>
            <li>Assis</li>
            <li>Coucher</li>
            <li>Vous suivre naturellement</li>
            <li>Découvrir le parcours chiot</li>
            <li>Marcher en laisse</li>
            <li>Assis, couché</li>
            <li>La mise en situations inhabituelles</li>
          </ul>
        </div>
      </div>

      <div class="activite_card">
        <img src="./interface_graphique/th (5).jpg" alt="dog" class="img_card" />
        <div class="card-body">
          <h5 class="activite_card-title">Éducation canine</h5>
          <p class="activite_card-text">
            Obéissance, rappel et comportement en société
          </p>
        </div>
        <div class="card-back">
          <h3>L'éducation Canine</h3>
          <p>Au programme :</p>
          <ul>
            <li>Pas bouger avec absence du maître</li>
            <li>Marche au pied avec laisse</li>
            <li>Rapport d'objets</li>
            <li>Descente de voiture sur ordre</li>
            <li>Apprentissage du port de la muselière</li>
          </ul>
        </div>
      </div>
      <div class="activite_card">
        <img src="./interface_graphique/th (9).jpg" alt="dog" class="img_card" />
        <div class="card-body">
          <h5 class="activite_card-title">Agilité</h5>
          <p class="activite_card-text">
            Parcours d'obstacles pour développer agilité et complicité
          </p>
        </div>
        <div class="card-back">
          <h3>L'Agilité</h3>
          <p>Au programme :</p>
          <ul>
            <li>Maîtrise des obstacles simples</li>
            <li>Commandes de base</li>
            <li>Enchaînements d'obstacles</li>
            <li>Exercices de vitesse</li>
            <li>Exercices de précision</li>
            <li>Pratique de mini-compétitons en groupe</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="activite">
      <div class="activite_card">
        <img src="./interface_graphique/image.jpg" alt="dog" class="img_card" />
        <div class="card-body">
          <h5 class="activite_card-title">Pistage</h5>
          <p class="activite_card-text">Travail olfactif et recherche</p>
        </div>
        <div class="card-back">
          <h3>Pistage</h3>
          <p>Au programme :</p>
          <ul>
            <li>Création des premières pistes simples</li>
            <li>introduction à la récompense</li>
            <li>Exercices de concentration</li>
            <li>Pistes avec plusieurs sources d'odeurs</li>
            <li>Pistes avec différents types de terrains</li>
          </ul>
        </div>
      </div>
      <div class="activite_card">
        <img src="./interface_graphique/th (2).jpg" alt="dog" class="img_card" />
        <div class="card-body">
          <h5 class="activite_card-title">Flyball</h5>
          <p class="activite_card-text">
            Sport canin ludique et dynamique en équipess
          </p>
        </div>
        <div class="card-back">
          <h3>Flyball</h3>
          <p>Au programme :</p>
          <ul>
            <li>Présentation du Flyball</li>
            <li>Apprentissage des haies</li>
            <li>Introduction à la boîte de lancement</li>
            <li>Premiers enchaînements</li>
            <li>Enchaînements plus longs</li>
            <li>Création de parcours complexes</li>
          </ul>
        </div>
      </div>
      <div class="activite_card">
        <img src="./interface_graphique/th (3).jpg" alt="dog" class="img_card" />
        <div class="card-body">
          <h5 class="activite_card-title">Protection & Défense</h5>
          <p class="activite_card-text">
            Protection et défense de son maître ou de sa propriété.
          </p>
        </div>
        <div class="card-back">
          <h3>Protection & défense</h3>
          <p>Au programme :</p>
          <ul>
            <li>Aboyer sur commande</li>
            <li>Protection sans agression excessive</li>
            <li>Gérer la garde d'un lieu, avec contrôle total du chien</li>
            <li>Développement de l'obeïssance</li>
            <li>Marquage(attaque sur commande)</li>
          </ul>
        </div>
      </div>
    </div>
  </section>
  <section id="nos_horaires">
    <div>
      <h2>Nos Horaires</h2>
      <p>Nous sommes ouvert 7jours/7</p>
    </div>
    <table class="tableau">
      <thead>
        <tr>
          <th>JOUR</th>
          <th>Matin</th>
          <th>Après-midi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Lundi</td>
          <td>8h00 - 12h00</td>
          <td>13h30 - 20h30</td>
        </tr>
        <tr>
          <td>Mardi</td>
          <td>8h00 - 12h00</td>
          <td>13h30 - 20h30</td>
        </tr>
        <tr>
          <td>Mercredi</td>
          <td>8h00 - 12h00</td>
          <td>13h30 - 20h30</td>
        </tr>
        <tr>
          <td>Jeudi</td>
          <td>8h00 - 12h00</td>
          <td>13h30 - 20h30</td>
        </tr>
        <tr>
          <td>Vendredi</td>
          <td>8h00 - 12h00</td>
          <td>13h30 - 20h30</td>
        </tr>
        <tr>
          <td>Samedi</td>
          <td>8h00 - 12h00</td>
          <td>13h30 - 20h30</td>
        </tr>
        <tr>
          <td>Dimanche</td>
          <td>8h00 - 12h00</td>
          <td>13h30 - 20h30</td>
        </tr>
      </tbody>
    </table>
  </section>
  <section id="story">
    <div>
      <h2>Notre histoire</h2>
    </div>

    <div class="story">
      <div class="container_story">
        <h3>Nos début</h3>
        <p>
          Encourager par de nombreux amis et ayant eu l'envie de vivre une
          superbe aventure humaine accompagnés <br />de nos meilleurs amis les
          chiens, nous avons décidé de créer notre propre club canin.
        </p>
        <h3>Constante évolution</h3>
        <p>
          <br />Après plusieurs démarches, nous aurons la chance d'avoir un
          terrain dédié à nos activités,<br />
          attribué par M. le Maire de petaouchnoc, le 18 mars 2020, et en
          accord avec M. le Maire de totoville.
        </p>
        <h3>Avenir</h3>
        <p>
          Ce terrain appartient à la commune de MNS, mais est situé sur la
          commune de IFA, <br />c'est pourquoi nous nommerons notre
          association : "Club Canin MNS-IFA" . <br />Après un travail de dur
          labeur de presque trois mois pour nettoyer et agrémenter ce terrain
          pour nos activités,<br />
        </p>
        <h3>En route vers l'avenir</h3>
        <p>
          nous nous sommes installés tout en continuant les améliorations qui
          conviennent afin d'offrir nos services à la population des
          alentours.
          <br />Pendant le temps d'agrémentation du terrain concerné, nous
          nous sommes entraînés
        </p>
        <h3>Entre temps</h3>
        <p>
          râce au soutien de cette communauté et de ses membres actifs "merci
          à vous". <br />Nous avons réalisé l'ouverture du club le 1er juillet
          2012 et l'inauguration le 16 septembre 2020,<br />
          en présence de M. le Maire d'Hayange, M. le Maire de MNS, M. le
          Président de MNS
        </p>
        <h3>Finalement</h3>
        <p>
          <br />M. le Directeur sportif "merci d'être présents au quotidien".
          <br />
          Aujourd'hui, nous vivons pleinement cette aventure et nous tenons à
          remercier tous nos bénévoles et <br />membres du Club , qui
          contribuent à faire vivre cette association depuis plusieurs années.
        </p>
      </div>
    </div>
  </section>

  <section id="nous_trouver">
    <h2>Nous Trouver</h2>
    <p>
      Notre club est situé dans un endroit facile d'accès. Découvrez notre
      emplacement sur la carte ci-dessous.
    </p>

    <div class="container-fluide">
      <card class="contact-info">
        <h3>Nos Coordonnées</h3>
        <p>
          <strong>Adresse :</strong> 86 rue aux arènes, 57000 Metz, France
        </p>
        <p><strong>Téléphone :</strong> 03-87-30-30-30</p>
        <p>
          <strong>Email :</strong>
          <a href="mailto:toto@gmail.com">toto@gmail.com</a>
        </p>
      </card>

      <div class="map-container">
        <iframe
          id="google-map"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.999169081347!2d2.292292615674255!3d48.85884437928744!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e671d5bfadf0fb%3A0x59da2b64b215a9e6!2sTour%20Eiffel!5e0!3m2!1sfr!2sfr!4v1678212340221!5m2!1sfr!2sfr"
          width="600"
          height="450"
          style="border: 0"
          allowfullscreen=""
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </section>

  <section id="nous_contacter">
    <h2>Nous contacter</h2>
    <div class="accueil_form">
      <div class="contact-form">
        <form id="contactForm">
          <label for="name">Nom :</label>
          <input
            type="text"
            id="name"
            name="name"
            required />

          <label for="email">Email :</label>
          <input
            type="email"
            id="email"
            name="email"
            required />

          <label for="message">Message :</label>
          <textarea
            id="message"
            name="message"
            required></textarea>

          <button type="submit">Envoyer</button>
        </form>
      </div>
    </div>
  </section>
  <button id="scrollToTopBtn" onclick="scrollToTop()">
    <img src="./interface_graphique/logo-dog-removebg-preview.png" alt="logo" />
  </button>
  <footer>
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
          <div class="footer-info">
            Adresse : 86 rue aux arenes, 57000 Metz
          </div>
        </div>
        <div class="footer-section">
          <h3 class="footer-title">Plan du site</h3>
          <div class="footer-info"><a href="#accueil">Accueil</a></div>
          <div class="footer-info">
            <a href="inscription.html">S'inscrire</a>
          </div>
          <div class="footer-info">
            <a href="utilisateur.html">Mon compte</a>
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
            <a href="#nos_activite">Nos Activités</a>
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
            <img
              src="./interface_graphique/logo-dog-removebg-preview.png"
              alt="Educa dog" />
          </div>
        </div>
      </div>
      <p>
        Copyright &copy; - 2025 Club CANIN "Educa Dog"- Tous droits réservés.
      </p>
    </section>
  </footer>
  <script src="index.js"></script>
</body>

</html>