<?php
// --- PARTIE 1 : Le Cerveau (Logique PHP) ---

// On vérifie si la page a été appelée par un formulaire soumis en méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    echo "<h1>Le formulaire a été soumis !</h1>";
    echo "<p>Voici ce que le serveur a reçu dans le 'courrier' POST :</p>";
    
    // var_dump() est notre meilleur outil de débogage.
    // Il nous montre le contenu brut de la variable globale $_POST.
    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";

    // On récupère les données envoyées pour les afficher proprement
    $titre = $_POST['titre'] ?? 'non défini';
    $description = $_POST['description'] ?? 'non défini';

    echo "<h2>Données traitées :</h2>";
    echo "<p>Titre que vous avez tapé : " . htmlspecialchars($titre) . "</p>";
    echo "<p>Description que vous avez tapée : " . htmlspecialchars($description) . "</p>";
    
    // On s'arrête ici pour ne pas ré-afficher le formulaire en dessous
    exit; 
}

// --- PARTIE 2 : L'Affichage (HTML) ---
// Si la méthode n'est pas POST (donc lors de la première visite de la page), 
// le code PHP en haut est ignoré et on affiche directement ce HTML.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Atelier Formulaire PHP Pur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

    <h1>Formulaire de test</h1>
    <p>Remplis ce formulaire et regarde ce que le serveur reçoit.</p>

    <form action="atelier_form.php" method="POST">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" class="form-control" id="titre" name="titre">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>

</body>
</html>