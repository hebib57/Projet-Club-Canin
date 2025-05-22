/* PAGE ACCUEIL -----------------------------------------------------------------------------------------------------------*/
/* Alerte Bienvenue ---------------------------------------*/

// window.addEventListener("load", () => {
//   alert("Bienvenue sur notre site Educa Dog!");
// });

/* BARRE NAV menu burger------------------------------------*/

// const burgerMenu = document.getElementById("burgerMenu");
// const navbar = document.querySelector(".navbar ul");

// burgerMenu.addEventListener("click", () => {
//   navbar.classList.toggle("active");
// });
/* BARRE NAV menu burger------------------------------------*/

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

/*tourner les card à 180° ------------------------------------*/
const cards = document.querySelectorAll(".activite_card");
cards.forEach((card) => {
  card.addEventListener("click", function () {
    this.classList.toggle("flipped");
  });
});
/*remonté de page------------------------------------------------------- */
function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: "smooth", // annimation fluide
  });
}

/*moteur de recherche----------------------------------------------*/

new TomSelect("#destinataires", {
  plugins: ["remove_button"],
  placeholder: "Rechercher un ou plusieurs destinataires...",
  maxOptions: 1000, // limite d'affichage dans la dropdown
  searchField: ["text"], // permet la recherche sur les noms
});

/*AFFICHAGE DATE-HEURE-----------------------------------------------*/

function afficherHeure() {
  const instant = new Date();
  const heure = instant.toLocaleTimeString();
  const date = instant.toLocaleDateString();
  document.getElementById("date").textContent = `${date} ${heure}`;
}
setInterval(afficherHeure, 1000);
afficherHeure();
