<?php
header('Content-Type: application/json');
require_once('bd.php');

if (!isset($_GET['ville']) || !isset($_GET['date'])) {
    echo json_encode(["message" => "Ville ou date manquante"]);
    exit;
}

$ville = $_GET['ville'];
$date = $_GET['date'];

$db = new Database();
$conn = $db->getConnection();

// Recherche dans la table principale
$stmt = $conn->prepare("SELECT polluant, valeur_journaliere, unite_de_mesure FROM all_years_cleaned_daily WHERE ville = ? AND jour = ?");
$stmt->bind_param("ss", $ville, $date);
$stmt->execute();
$result = $stmt->get_result();

$pollution = [];

while ($row = $result->fetch_assoc()) {
    $pollution[] = $row;
}
$stmt->close();

// Si aucune donnée réelle, on cherche dans la table de prédictions
if (empty($pollution)) {
    $stmt = $conn->prepare("SELECT polluant, valeur_predite AS valeur_journaliere, 'µg/m³' AS unite_de_mesure FROM prediction_cities WHERE ville = ? AND jour = ?");
    $stmt->bind_param("ss", $ville, $date);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pollution[] = $row;
    }
    $stmt->close();
}

$conn->close();

echo json_encode($pollution ?: ["message" => "Aucune donnée trouvée."]);
