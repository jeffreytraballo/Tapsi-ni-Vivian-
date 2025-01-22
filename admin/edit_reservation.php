<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservation</title>
    <!-- Link to your CSS file -->
    <style type="text/css">
      form {
    width: 100%;
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
}

/* Labels */
form label {
    display: block;
    font-weight: bold;
    margin-bottom: 8px;
    color: #333;
}

/* Input fields and textarea */
form input[type="number"],
form input[type="email"],
form input[type="text"],
form input[type="date"],
form input[type="time"],
form textarea,
form select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 14px;
    font-family: Arial, sans-serif;
}

/* Placeholder and focus */
form input::placeholder,
form textarea::placeholder {
    color: #aaa;
}

form input:focus,
form textarea:focus,
form select:focus {
    border-color: #f05a28;
    outline: none;
    box-shadow: 0 0 5px rgba(240, 90, 40, 0.5);
}

/* Button styling */
form button {
    width: 100%;
    background-color: #f05a28;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    text-align: center;
}

form button:hover {
    background-color: #d94b1f;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Responsive design */
@media (max-width: 768px) {
    form {
        padding: 15px;
    }

    form button {
        font-size: 14px;
    }
}
      </style>
</head>
<body>

<?php
// Include the database connection
require_once 'includes/db.php';

// Fetch available tables from the database
$tableQuery = "SELECT table_id, table_name FROM tables WHERE status = 'Available'"; // Modify based on your table structure
$tableResult = mysqli_query($db, $tableQuery);

// Fetch existing reservation details
if (isset($_GET['edit'])) {
    $reserve_id = $_GET['edit']; 

    $stmt = $db->prepare("SELECT * FROM reservation WHERE reserve_id = ?");
    $stmt->bind_param("i", $reserve_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $no_of_guest = $row['no_of_guest'];
        $email = $row['email'];
        $phone = $row['phone'];
        $date_res = $row['date_res'];
        $time = $row['time'];
        $status = $row['status'];
        $suggestions = $row['suggestions'];
        $table_id = $row['table_id']; // Assuming table_id is stored in the reservation table
    } else {
        echo "Reservation not found.";
        exit();
    }
} else {
    die("No reserve_id provided.");
}

// Handle form submission
if (isset($_POST['reservations'])) {
    $reserve_id = $_POST['reserve_id'];
    $no_of_guest = $_POST['no_of_guest'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date_res = $_POST['date_res'];
    $time = $_POST['time'];
    $status = $_POST['status'];
    $suggestions = $_POST['suggestions'];
    $table_id = $_POST['table_id']; // New table selection

    // Update the reservation
    $query = "UPDATE reservation SET no_of_guest = ?, email = ?, phone = ?, date_res = ?, time = ?, status = ?, suggestions = ?, table_id = ? WHERE reserve_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("issssssii", $no_of_guest, $email, $phone, $date_res, $time, $status, $suggestions, $table_id, $reserve_id);

    if ($stmt->execute()) {
        // After updating the reservation, update the table status to 'Occupied'
        $updateTableStatus = "UPDATE tables SET status = 'Reserved' WHERE table_id = ?";
        $updateStmt = $db->prepare($updateTableStatus);
        $updateStmt->bind_param("i", $table_id);
        $updateStmt->execute();

        // Redirect to the reservations page after the update
        header("Location: reservations.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
?>

<form method="post" action="edit_reservation.php?edit=<?php echo $reserve_id; ?>">
    <label for="no_of_guest">Reserve ID:</label>
    <input type="number" name="reserve_id" value="<?php echo $reserve_id; ?>" readonly>
    
    <label for="no_of_guest">Number of Guests:</label>
    <input type="number" name="no_of_guest" value="<?php echo $no_of_guest; ?>" required>
    
    <label for="email">Email:</label>
    <input type="email" name="email" value="<?php echo $email; ?>" required>
    
    <label for="phone">Phone:</label>
    <input type="text" name="phone" value="<?php echo $phone; ?>" required>
    
    <label for="date_res">Date of Reservation:</label>
    <input type="date" name="date_res" value="<?php echo $date_res; ?>" required>
    
    <label for="time">Time:</label>
    <input type="time" name="time" value="<?php echo $time; ?>" required>
    
    <label for="status">Status:</label>
    <select name="status">
        <option value="Confirmed" <?php echo ($status == 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
        <option value="Pending" <?php echo ($status == 'Pending') ? 'selected' : ''; ?>>Pending</option>
    </select>

    <label for="table_id">Select Table:</label>
    <select name="table_id" required>
        <?php while ($table = mysqli_fetch_assoc($tableResult)) { ?>
            <option value="<?php echo $table['table_id']; ?>" <?php echo ($table['table_id'] == $table_id) ? 'selected' : ''; ?>>
                <?php echo $table['table_name']; ?>
            </option>
        <?php } ?>
    </select>
    
    <label for="suggestions">Suggestions:</label>
    <textarea name="suggestions"><?php echo $suggestions; ?></textarea>
    
    <button type="submit" name="reservations">Update Reservation</button>
</form>

<?php mysqli_close($db); ?>

</body>
</html>