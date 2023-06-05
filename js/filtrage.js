// Récupérer le formulaire et ajouter un gestionnaire d'événement pour la soumission
var filterForm = document.getElementById('filtreForm');
filterForm.addEventListener('submit', function(event) {
    event.preventDefault(); // Empêcher la soumission du formulaire par défaut

    // Récupérer les valeurs des filtres
    var mois = document.querySelector('#mois').value;
    var descr_grav = document.querySelector('#descr_grav').value;
    var age = document.getElementById('age').value;

    // Construire les données à envoyer au serveur
    var data = "mois=" + mois + "&descr_grav=" + descr_grav + "&age=" + age;

    // Envoyer la requête AJAX
    ajaxRequest('POST', 'php/request.php/filtre', function(response) {
        // Mettre à jour la carte avec les nouveaux marqueurs
	    updateMap(response);

        console.log(response);
    }, data);
});


// Fonction pour mettre à jour la carte avec les marqueurs filtrés
function updateMap(data) {
 // Créer la carte
 mapboxgl.accessToken = 'pk.eyJ1IjoiZW1pZTE4IiwiYSI6ImNsaDdxdXB2dDAxZmYzZW1tM3hhbWR3b24ifQ.zjp20nsMooS-xVfxn982pA'; // Remplacez YOUR_ACCESS_TOKEN par votre propre jeton d'accès Mapbox
 var map = new mapboxgl.Map({
   container: 'map',
   style: 'mapbox://styles/mapbox/streets-v11', // Style de la carte (vous pouvez choisir un autre style)
   center: [2.554071, 46.603354], // Centre initial de la carte
   zoom: 4 // Niveau de zoom initial de la carte
 });

 // Parcourir les données et ajouter des marqueurs à la carte
 data.forEach(function(item) {
   // Créer un élément HTML personnalisé pour le marqueur
   var el = document.createElement('div');
   el.className = 'marker';

   // Ajouter le marqueur à la carte
   var marker = new mapboxgl.Marker(el)
     .setLngLat([item.longitude, item.latitude])
     .addTo(map);

   // Ajouter un événement de survol pour afficher les informations
   marker.getElement().addEventListener('mouseover', function() {
       const info = document.getElementById('info');
       
       info.innerHTML =`<strong>Ville:</strong> ${item.ville}
       <br><strong>Date:</strong> ${item.date}
       <br><strong>Âge Conducteur:</strong> ${item.age}
       <br><strong>Gravité:</strong> ${item.descr_grav}
       <br><strong>latitude </strong> ${item.latitude} <strong> longitude :</strong> ${item.longitude}
       <br><strong>conditions atmosphériques:</strong> ${item.descr_athmo}
       <br><strong>luminosité:</strong> ${item.descr_lum}
       <br><strong>Etat de la surface:</strong> ${item.descr_etat_surf}
       <br><strong>disposition sécurité:</strong> ${item.descr_dispo_secu}`;
   });
 });
}
