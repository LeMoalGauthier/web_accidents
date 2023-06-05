<?php
// Connexion à la base de données
$db = new PDO('mysql:host=localhost;dbname=etu115', 'etu115', 'fcsvberc');

// Récupération des villes depuis la table grande_table_accidents
$query = "SELECT id_code_insee, ville FROM grande_table_accidents GROUP BY id_code_insee, ville limit 1000";
$result = $db->query($query);

// Préparation de la requête d'insertion dans la table Ville
$insertQuery = "INSERT INTO Ville (id_code_insee, ville) VALUES (:id_code_insee, :ville)";
$insertStatement = $db->prepare($insertQuery);

// Parcours des résultats et insertion des villes dans la table Ville
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    // Conversion de id_code_insee en entier (INT)
    $id_code_insee = intval($row['id_code_insee']);
    
    // Insertion des valeurs dans la table Ville
    $insertStatement->bindParam(':id_code_insee', $id_code_insee, PDO::PARAM_INT);
    $insertStatement->bindParam(':ville', $row['ville'], PDO::PARAM_STR);
    $insertStatement->execute();
}

echo "Les villes ont été ajoutées avec succès dans la table Ville.";

// Fermeture de la connexion à la base de données
$db = null;
?>
