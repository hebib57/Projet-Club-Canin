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
