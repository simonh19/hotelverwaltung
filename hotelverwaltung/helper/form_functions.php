<?php

function getUrlParam($urlParam)
{
    $separator = "=";
    if (str_contains($urlParam, $separator)) {
        $paramArray = explode($separator, $urlParam);
        return $paramArray[1];

    } else return [];
}

function getUrlParamName($urlParam)
{
    $separator = "=";
    if (str_contains($urlParam, $separator)) {
        $paramArray = explode($separator, $urlParam);
        return $paramArray[0];

    } else return [];
}

function executeQuery($query,$conn){
    $stmt = $conn->prepare($query);
    $stmt->execute();

    return $stmt;
}

//Immer wenn ich etwas von einem Formular(Post-Formular) haben will, dann verwende ich diese Funktion
function getPostParameter($paramName, $defaultValue = '') {
    return isset($_POST[$paramName]) ? trim($_POST[$paramName]) : $defaultValue;
}

function createDropdown($name, $options, $selectedValue = null) {
    $html = "<select class='form-control' name='$name' id='$name'>";
    foreach ($options as $value => $label) {
        $selected = ($value == $selectedValue) ? 'selected' : '';
        $html .= "<option value='$value' $selected>$label</option>";
    }
    $html .= "</select>";
    return $html;
}

function showAlertSuccess($message) {
    echo '<div class="alert alert-success" role="alert">';
    echo $message;
    echo '</div>';
}

function showAlertWarning($message) {
    echo '<div class="alert alert-warning" role="alert">';
    echo $message;
    echo '</div>';
}
//
function generateTableFromQuery($conn, $stmt, $idColumnName, $tableName)
{

    // beginne mit dem erstellen der Tabelle
    $table = '<table class="table mt-3">';

    // Kopfzeile mit Spaltennamen aus der Query generieren
    $table .= '<thead><tr>';
    for ($i = 0; $i < $stmt->columnCount(); $i++) {
        $columnMeta = $stmt->getColumnMeta($i);
        if ($columnMeta['name'] != $idColumnName) {
            $table .= '<th>' . htmlspecialchars($columnMeta['name']) . '</th>';
        }
    }
    $table .= '</tr></thead>';

    // datenzeilen generieren
    $table .= '<tbody>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $table .= '<tr>';
        foreach ($row as $columnName => $cell) {
            if ($columnName != $idColumnName) {
                $cell = $cell ? $cell : '';
                $table .= '<td>' . htmlspecialchars($cell) . '</td>';
            }
        }

        // Bearbeiten und Löschen Buttons mit der ID des Eintrags HIER SEITE ANPASSEN
        $table .= "<td><a href='index.php?site=zimmer-bearbeiten?edit_id=" . $row[$idColumnName] . "' class='btn btn-warning'>Bearbeiten</a></td>";
        $table .= "<td><a href='index.php?site=helper/delete?" . $idColumnName . "=" . $row[$idColumnName] . "?table=" . $tableName . "' class='btn btn-danger' onclick='return confirm(\"Wollen Sie den Eintrag wirklich löschen?\");'>Löschen</a></td>";

        $table .= '</tr>';
    }
    $table .= '</tbody>';

    // schließe die Tabelle
    $table .= '</table>';

    return $table;
}
?>