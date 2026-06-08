<?php
require_once 'app/classes/Database.php';
$db = Database::getInstance();
$sql = "ALTER TABLE orders ADD COLUMN cancel_reason TEXT";
if(mysqli_query($db, $sql)) {
    echo "Success: cancel_reason added.";
} else {
    echo "Error: " . mysqli_error($db);
}
?>
