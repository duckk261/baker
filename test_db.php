<?php
require_once 'app/classes/Database.php';
$db = Database::getInstance();
$res = mysqli_query($db, "DESCRIBE orders");
while ($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
?>
