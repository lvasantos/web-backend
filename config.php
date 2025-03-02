<?php
$host = "localhost";
$dbname = "livres_db";
$user = "lucianaadriao";
$pswd = "";


try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pswd, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Error: conectar ao banco de dados: " . $e->getMessage());
}

?>