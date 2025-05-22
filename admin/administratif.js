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

/*Confirmation de Suppression-------------------------------*/

function confirmationDeleteReservation() {
  return confirm("Êtes-vous sûr de vouloir supprimer cette réservation ?");
}

function confirmationDeleteCours() {
  return confirm("Êtes-vous sûr de vouloir supprimer ce cours ?");
}

function confirmationDeleteAdmin() {
  return confirm("Êtes-vous sûr de vouloir supprimer cet Admin ?");
}

function confirmationDeleteUser() {
  return confirm("Êtes-vous sûr de vouloir supprimer cet Utilisateur ?");
}

function confirmationDeleteCoach() {
  return confirm("Êtes-vous sûr de vouloir supprimer ce Coach ?");
}

function confirmationDeleteDog() {
  return confirm("Êtes-vous sûr de vouloir supprimer ce Chien ?");
}

function confirmationDeleteMessage() {
  return confirm("Êtes-vous sûr de vouloir supprimer ce Message ?");
}

function confirmationDeleteEvent() {
  return confirm("Êtes-vous sûr de vouloir supprimer cet évènement ?");
}

/* Temporisation affichage "cours ajouté" page Admin---------------*/


window.addEventListener("DOMContentLoaded", () => {
  const alertBox = document.querySelector(".alert");

  if (alertBox) {
    
    setTimeout(() => {
      alertBox.style.transition = "opacity 0.5s ease-out";
      alertBox.style.opacity = "0";

     
      setTimeout(() => alertBox.remove(), 500);
    }, 5000);
  }
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
