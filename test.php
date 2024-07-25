<?php
include_once './frontend_projet/DATABASE/Database.php';

$database = new Database();
$conn = $database->getConnection();

if ($conn) {
    echo "La connexion à la base de données a été établie et les utilisateurs ont été initialisés.";
} else {
    echo "Erreur de connexion à la base de données.";
}
