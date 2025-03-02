<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);

    if (!empty($nom) && !empty($prenom)) {
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        header("Location: index.php");
        exit();
    } else {
        $error = "Veuillez entrer votre nom et prénom.";// "Please enter your first and last name"
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="login-container">
        <h2>Connexion</h2>
        <?php if (isset($error)) {
            echo "<p style='color: red;'>$error</p>";
        } ?>
        <form action="login.php" method="POST">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>

            <button type="submit">Se Connecter</button>
        </form>
    </div>
</body>

</html>