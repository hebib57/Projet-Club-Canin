/* BARRE NAV menu burger------------------------------------*/

//Sélectionne le menu burger et la navbar
const burgerMenu = document.getElementById("burgerMenu");
const navbar = document.querySelector(".navbar ul");

// Ajoute un événement de clic pour activer/désactiver le menu
// burgerMenu.addEventListener("click", () => {
//   navbar.classList.toggle("active");
// });

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
