<?php
if (session_id() == '') {
    session_start();   
}
include 'helper/form_functions.php';
require_once 'conf.php';
include_once 'helper/delete.php';
global $conn;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Zimmerverwaltung</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-item nav-link" href="index.php">Willkommensseite</a>
            <a class="nav-item nav-link" href="index.php?site=zimmer_anzeigen">Zimmer je Etage</a>
        </div>
    </div>
</nav>
<div class='container d-flex align-items-center flex-column mt-4 gap-4'>
    <h3>Willkommen</h3>
    <?php

        if (isset($_GET["site"])) {
            $fullUrl = $_GET["site"];
            if (str_contains($fullUrl, "?")) {
                $separator = "?";
                $parts = explode($separator, $fullUrl);
                $_GET['urlParam'] = $parts;
                $site = $parts[0];
                include_once($site . ".php");
            } else {
                include_once($fullUrl . ".php");
            }
        } else {
            $tableName="zimmer";
            $tableId = "zim_id";
            $query = "select zim.zim_etage as Etage,rt.raty_name as Raumtyp,zim.zim_nr as Zimmernummer,zim.zim_id
            from zimmer zim
            join raumtyp rt on zim.raty_id = rt.raty_id";
            $zimmer = executeQuery($query,$conn);
            echo generateTableFromQuery($conn, $zimmer,"zim_id","zimmer");
        }
    ?>
</div>
</body>
</html>