<?php
require_once '../includes/db.php';
$table_id = $_GET['id'];
$db->query("DELETE FROM tables WHERE table_id = $table_id");
header("Location: table_management.php");
exit();
?>
