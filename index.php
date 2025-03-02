<?php
require_once "config.php";

session_start();

if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
    header("Location: login.php");
    exit();
}

$nom = htmlspecialchars($_SESSION['nom']);
$prenom = htmlspecialchars($_SESSION['prenom']);

function incrementCounter()
{
    $counterFile = "compteur.txt";
    if (!file_exists($counterFile)) {
        file_put_contents($counterFile, 0);
    }

    if (!isset($_COOKIE['visited'])) {
        $counter = (int) file_get_contents($counterFile);
        $counter++;
        file_put_contents($counterFile, $counter);
        setcookie("visited", "true", time() + 3600); // 1h 
    } else {
        $counter = (int) file_get_contents($counterFile);
    }
    return $counter;
}

$counter = incrementCounter();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteque</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body>
    <nav class="navbar">
        <ul class="navbar-items">
            <li class="user-info">Bienvenue, <span><?php echo $nom . " " . $prenom; ?></span></li>
            <li>Book selling</li>
            <li class="visitors"><b><?php echo $counter; ?></b> Visiteurs</li>
        </ul>

        <a href="logout.php" class="logout-btn">
            <i class="ph ph-sign-out"></i> Se déconnecter
        </a>
    </nav>


    <div class="content-wrapper">
        <section class="search-section">
            <div class="search-item">
                <input type="text" id="search_author" placeholder="Nom de l'auteur">
                <button onclick="search('search_author', $('#search_author').val())">
                    <i class="ph ph-magnifying-glass"></i>
                </button>
            </div>

            <div class="search-item">
                <input type="text" id="search_title" placeholder="Titre du livre">
                <button onclick="search('search_title', $('#search_title').val())">
                    <i class="ph ph-magnifying-glass"></i>
                </button>
            </div>

            <div class="search-item">
                <input type="text" id="search_title_code" placeholder="Code de l'ouvrage">
                <button onclick="search('search_title_code', $('#search_title_code').val())">
                    <i class="ph ph-magnifying-glass"></i>
                </button>
            </div>
        </section>

        <section class="main-content" id="results">
        </section>
    </div>

    <script>
        function search(param, value) {
            if (value.trim() === "") return;

            $.ajax({
                url: 'requete.php',
                type: 'GET',
                data: {
                    [param]: value
                },
                success: function(response) {
                    $('#results').html(response);
                    clearInputs();
                }
            });
        }

        function clearInputs() {
            $('input[type="text"]').val("");
        }

        $(document).ready(function() {
            $('input[type="text"]').keypress(function(event) {
                if (event.which === 13) { // Tecla Enter
                    event.preventDefault();
                    let inputId = $(this).attr('id');
                    search(inputId, $(this).val());
                }
            });
        });
    </script>
</body>

</html>