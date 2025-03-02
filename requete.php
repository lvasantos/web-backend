<?php
require_once "config.php";

session_start();

if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
    exit("<p style='color: red; text-align: center; font-weight: bold;'>Accès refusé</p>");
}

$search_author = isset($_GET['search_author']) ? trim($_GET['search_author']) : '';
$search_title = isset($_GET['search_title']) ? trim($_GET['search_title']) : '';
$search_title_code = isset($_GET['search_title_code']) ? trim($_GET['search_title_code']) : '';

$auteurs = [];
$livres = [];
$livres_par_code = [];

try {
    $pdo = new PDO("pgsql:host=localhost;dbname=livres_db", "lucianaadriao", "your_password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!empty($search_author)) {
        $sql = "SELECT code, nom, prenom FROM public.auteurs WHERE nom ILIKE :search OR prenom ILIKE :search ORDER BY nom LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':search', "%$search_author%", PDO::PARAM_STR);
        $stmt->execute();
        $auteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if (!empty($search_title)) {
        $sql = "SELECT code, nom, parution FROM public.ouvrage WHERE nom ILIKE :search ORDER BY nom LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':search', "%$search_title%", PDO::PARAM_STR);
        $stmt->execute();
        $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if (!empty($search_title_code)) {
        $sql = "SELECT code, nom, parution FROM public.ouvrage WHERE code = :search ORDER BY nom LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':search', $search_title_code, PDO::PARAM_INT);
        $stmt->execute();
        $livres_par_code = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    exit("<p style='color: red; text-align: center;'>Erreur lors de l'accès à la base de données: " . htmlspecialchars($e->getMessage()) . "</p>");
}

function formatResults($title, $data)
{
    if (!empty($data)) {
        echo "<div class='browse-results'><h2>$title</h2><ul>";
        foreach ($data as $item) {
            echo "<li><strong>" . htmlspecialchars($item['nom']) . "</strong> " . (isset($item['prenom']) ? htmlspecialchars($item['prenom']) : '') . " (" . (isset($item['parution']) ? "Publié en " . htmlspecialchars($item['parution']) : 'Date inconnue') . ")</li>";
        }
        echo "</ul></div>";
    }
}

echo "<div class='main-content'>";
formatResults("Auteurs Trouvés", $auteurs);
formatResults("Livres Trouvés", $livres);
formatResults("Livres Trouvés par Code", $livres_par_code);
echo "</div>";
