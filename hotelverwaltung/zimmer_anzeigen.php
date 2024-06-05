<?php
include_once 'helper/database_functions.php';
require_once 'conf.php';
include_once 'suche.php';

global $conn;
$tableName="zimmer";
$zimmerListe = getValues($conn,"zimmer","zim_etage");
$tableId = "zim_id";

$stateChanged = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $selectedEtageValue = getPostParameter("etage","");
    $etage = $zimmerListe[$selectedEtageValue];
    $_POST['selectedEtage'] = $etage;
    $gefundeneZimmer = processForm($_POST);
    // Sammle und verarbeite hier die Formulardaten
    //Hier bekomme ich den Wert von dem Input-Attribut
    $stateChanged = true;
}

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Ort Hinzufügen</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Zimmer je Etage</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="etage">Etage wählen</label>
            <?php echo createDropdown('etage', $zimmerListe); ?>
        </div>
        <button type="submit" class="btn btn-primary">Zimmer anzeigen</button>
    </form>
    <div>
        <?php if ($stateChanged) { echo generateTableFromQuery($conn,$gefundeneZimmer,$tableId,$tableName); } ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>