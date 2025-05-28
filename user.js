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

/* sidebar menu buger------------------------------------*/

const sidebarMenu = document.getElementById("sidebarMenu");
const sidebar = document.querySelector(".sidebar");

sidebarMenu.addEventListener("click", () => {
  sidebar.classList.toggle("sidebar-active");
});

/*POPUP MODAL Reservation Cours----------------------------------------------------*/

function openCoursModal(idCours) {
  document.getElementById("modal_id_cours").value = idCours;
  document.getElementById("reservationModal").style.display = "flex";
}

function closeCoursModal() {
  document.getElementById("reservationModal").style.display = "none";
}

/*POPUP MODAL USER Dog------------------------------------------------*/

const modal = document.getElementById("dogModal");
const modalPhoto = document.getElementById("modal_photo-dog");
const modalNom = document.getElementById("modal-nom");
const modalRace = document.getElementById("modal-race");
const modalAge = document.getElementById("modal-age");
const modalSexe = document.getElementById("modal-sexe");
const closeBtn = modal.querySelector(".close");

document.querySelectorAll(".btn-details").forEach((button) => {
  button.addEventListener("click", () => {
    modalPhoto.src = "../upload/md_" + button.dataset.photo;
    modalNom.textContent = button.dataset.nom;
    modalRace.textContent = button.dataset.race;
    modalAge.textContent = button.dataset.age;
    modalSexe.textContent = button.dataset.sexe;
    modal.style.display = "block";
  });
});

closeBtn.onclick = () => {
  modal.style.display = "none";
};

window.onclick = function (event) {
  if (event.target === modal) {
    modal.style.display = "none";
  }
};

/*POPUP MODAL Inscription Evenement----------------------------------*/

function openEventModal(idEvent) {
  document.getElementById("modal_id_event").value = idEvent;
  document.getElementById("inscriptionModal").style.display = "flex";
}

function closeEventModal() {
  document.getElementById("inscriptionModal").style.display = "none";
}

/*CONFIRMATION DE SUPPRESSION---------------------------------------*/
function confirmationDeleteMessage() {
  return confirm("Êtes-vous sûr de vouloir supprimer ce Message ?");
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
