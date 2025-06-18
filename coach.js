/* BARRE NAV menu burger------------------------------------*/

//Sélectionne le menu burger et la navbar
const burgerMenu = document.getElementById("burgerMenu");
const navbar = document.querySelector(".navbar ul");

// Gestion du clic sur le burger menu
burgerMenu.addEventListener("click", () => {
  // Si le menu est fermé, on l'ouvre
  if (navbar.classList.contains("navbar__burger-menu--closed")) {
    navbar.classList.remove("navbar__burger-menu--closed");
    navbar.classList.add("navbar__burger-menu--open");
  } else {
    // Sinon, on le ferme
    navbar.classList.remove("navbar__burger-menu--open");
    navbar.classList.add("navbar__burger-menu--closed");
  }
});

/* sidebar menu buger------------------------------------*/

const sidebarMenu = document.getElementById("sidebarMenu");
const sidebar = document.querySelector(".sidebar");

sidebarMenu.addEventListener("click", () => {
  sidebar.classList.toggle("sidebar-active");
});

/* CONFIRMATION DE SUPPRESSION---------------------------*/
function confirmationDeleteMessage() {
  return confirm("Êtes-vous sûr de vouloir supprimer ce Message ?");
}

/* CONFIRMATION DE SUPPRESSION COMMENTAIRE---------------------------*/
function confirmationDeleteCommentaire() {
  return confirm("Êtes-vous sûr de vouloir supprimer ce Commentaire ?");
}

/*AFFICHAGE DATE-HEURE-----------------------------------------------*/

function afficherHeure() {
  const instant = new Date();
  const heure = instant.toLocaleTimeString();
  const date = instant.toLocaleDateString();
  document.getElementById("date").textContent = `${date} ${heure}`;
}
setInterval(afficherHeure, 1000);
afficherHeure();
