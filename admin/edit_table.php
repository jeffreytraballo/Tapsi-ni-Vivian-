<?php require_once '../includes/db.php'; ?>

<?php
$table_id = $_GET['edit'];
$result = $db->query("SELECT * FROM tables WHERE table_id = $table_id");
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $table_name = $_POST["table_name"];
    $capacity = $_POST["capacity"];
    $status = $_POST["status"];

    $query = "UPDATE tables SET table_name=?, capacity=?, status=? WHERE table_id=?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sisi", $table_name, $capacity, $status, $table_id);

    if ($stmt->execute()) {
        echo "<script>alert('Table updated successfully!'); window.location='table_management.php';</script>";
        exit();
    } else {
        echo "Error: " . $db->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Table | Admin</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #FF5722;
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-submit {
            background: #FF5722;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background: #E64A19;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
        }
    </style>

 <!-- Bootstrap core CSS     -->
 <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

<!-- Animation library for notifications   -->
<link href="assets/css/animate.min.css" rel="stylesheet"/>

<!--  Light Bootstrap Table core CSS    -->
<link href="assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/>


<!--  CSS for Demo Purpose, don't include it in your project     -->
<link href="assets/css/demo.css" rel="stylesheet" />


<!--     Fonts and icons     -->
<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />


<link href="assets/css/style.css" rel="stylesheet" />


</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-color="#000" data-image="assets/img/sidebar-5.jpg">

    <!--   you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple" -->


    	<?php require "includes/side_wrapper.php"; ?>

    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed" style="background: #FF5722;">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar" style="background: #fff;"></span>
                        <span class="icon-bar" style="background: #fff;"></span>
                        <span class="icon-bar" style="background: #fff;"></span>
                    </button>
                    <a class="navbar-brand" href="#" style="color: #fff;">TABLE MANAGEMENT</a>
                </div>
                <div class="collapse navbar-collapse">

                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="logout.php" style="color: #fff;">
                                Log out
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


<div class="container">
    <h2>Edit Table</h2>
    <form method="post">
        <input type="hidden" name="table_id" value="<?php echo $table_id; ?>">

        <label>Table Name:</label>
        <input type="text" name="table_name" value="<?php echo $row['table_name']; ?>" required>

        <label>Capacity:</label>
        <input type="number" name="capacity" value="<?php echo $row['capacity']; ?>" required>

        <label>Status:</label>
        <select name="status">
            <option value="Available" <?php echo ($row['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
            <option value="Reserved" <?php echo ($row['status'] == 'Reserved') ? 'selected' : ''; ?>>Reserved</option>
            <option value="Occupied" <?php echo ($row['status'] == 'Occupied') ? 'selected' : ''; ?>>Occupied</option>
        </select>

        <button type="submit" class="btn-submit">Update Table</button>
        
    </form>
</div>
    </div>
    </div>
    
<script src="../assets/js/jquery-1.10.2.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>

</body>
</html>
