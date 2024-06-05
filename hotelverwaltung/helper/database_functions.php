<?php
//Das ist notwendig, damit deleteRecord ausgeführt wird.
require_once 'conf.php';
global $conn;

//Dadurch bekomme ich eine MENGE von Werten in der Datenbank
// Nützlich dropdowns und radio buttons
// Man bekommt ALLE Werte von einer Spalte
function getValues($conn, $table, $column) {
    $sql = "SELECT DISTINCT $column FROM $table order by $column";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // wird die daten in einem array speichern
    $values = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return $values;
}

//Macht das Gleiche, aber man bekommt nur einen Wert. Parameter: Ich will $folgendenWert wo $folgendeBedingung $folgendenWert hat.
function getValue($conn, $table, $column, $conditionColumn, $conditionValue) {
    $sql = "SELECT $column FROM $table WHERE $conditionColumn = :conditionValue LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':conditionValue', $conditionValue);
    $stmt->execute();
    $value = $stmt->fetch(PDO::FETCH_COLUMN);
    return $value;
}
//Gibt es schon folgenden Datenwert in der Tabelle?
function recordExists($conn, $table, $column, $value) {
    $sql = "SELECT COUNT(*) FROM $table WHERE $column = :value";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':value', $value);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0;
}

function recordExists2($conn, $table, $column1,$column2,$value1,$value2) {
    $sql = "SELECT COUNT(*) FROM $table WHERE $column1 = :value1 and 
    $column2 = :value2";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':value1', $value1);
    $stmt->bindParam(':value2', $value2);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0;
}

//SPEICHERN Hier werden Daten eingefügt (in eine Tabelle), die von dem Formular kommen. 
function addRecord($conn, $table, $data) {
    //implode ist ähnlich wie ein Split
    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), '?'));

    $stmt = $conn->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");

    $stmt->execute(array_values($data));

    $lastId = $conn->lastInsertId();

    return $lastId;
}

//UPDATE
function updateRecord($conn, $table, $data, $conditions) {
    try {
        $query = "UPDATE $table SET ";
        $setPart = [];
        foreach ($data as $key => $value) {
            $setPart[] = "$key = :$key";
        }
        $query .= implode(', ', $setPart);
        $query .= ' WHERE ' . implode(' AND ', $conditions);
        $stmt = $conn->prepare($query);

        foreach ($data as $key => &$val) {
            $stmt->bindParam(":$key", $val);
        }
        $stmt->execute();
        return $stmt->rowCount();
    } catch(PDOException $e) {    
        echo showAlertWarning('Update Error: ' . $e->getMessage()); 
    }
}

//DELETE
function deleteRecord($conn,$table, $idColumn, $idValue)
{
    try {
        $deleteQuery = "DELETE FROM $table WHERE " . $idColumn . "=" .$idValue;

        $preparedStmt = $conn->prepare($deleteQuery);
        $preparedStmt->execute();

        return $preparedStmt->rowCount();
    } catch (PDOException $exception) {
        echo 'Database Delete Error: ' . $exception->getMessage();
    }
}
?>