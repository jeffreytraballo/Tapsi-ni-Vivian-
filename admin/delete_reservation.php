<?php
require_once '../includes/db.php';

// Validate input
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $reserve_id = intval($_GET['delete']); // Ensure it's an integer

    // First, get the table_id from the reservation before deleting
    $stmt_res = $db->prepare("SELECT table_id FROM reservation WHERE reserve_id = ?");
    $stmt_res->bind_param("i", $reserve_id);
    $stmt_res->execute();
    $result = $stmt_res->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $table_id = $row['table_id'];

        // Now, delete the reservation
        $stmt = $db->prepare("DELETE FROM reservation WHERE reserve_id = ?");
        $stmt->bind_param("i", $reserve_id);

        if ($stmt->execute()) {
            // After successfully deleting the reservation, update the table status to "occupied"
            $update_stmt = $db->prepare("UPDATE tables SET status = 'Available' WHERE table_id = ?");
            $update_stmt->bind_param("i", $table_id);
            $update_stmt->execute();

            // Redirect with success message
            header("Location: reservations.php?status=success");
        } else {
            // Redirect with error message
            header("Location: reservations.php?status=error");
        }

        $update_stmt->close();
        $stmt->close();
    } else {
        // Redirect with error message if no table is found
        header("Location: reservations.php?status=error_no_table");
    }

    $stmt_res->close();
} else {
    // Redirect with invalid ID message
    header("Location: reservations.php?status=invalid");
}

exit();
?>
