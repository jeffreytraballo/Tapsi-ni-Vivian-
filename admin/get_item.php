<?php 
session_start();
require "includes/functions.php";
require "includes/db.php";

if (!isset($_SESSION['user'])) {
    header("location: logout.php");
    exit();
}

$result = "";
$info = "";
$items = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['order_id'])) {

        $order_id = htmlentities($_POST['order_id'], ENT_QUOTES, 'UTF-8');

        if (!empty($order_id)) {
            $arr_id = explode("_", $order_id);
            $id = $arr_id[0];

            // Use prepared statements to prevent SQL injection
            $stmt = $db->prepare("SELECT * FROM basket WHERE id = ? LIMIT 1");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $order = $stmt->get_result();

            if ($order->num_rows > 0) {
                $row = $order->fetch_assoc();

                // Build the customer info table
                $info .= "<table class='table table-hover'>
                    <thead>
                        <th>Order ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>ORD_$id</td>
                            <td>" . htmlspecialchars($row['customer_name']) . "</td>
                            <td>" . htmlspecialchars($row['address']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . htmlspecialchars($row['contact_number']) . "</td>
                        </tr>
                    </tbody>
                </table>";

                // Build the items table
                $items .= "<table class='table table-hover'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>";

                $stmt_items = $db->prepare("SELECT * FROM items WHERE order_id = ?");
                $stmt_items->bind_param("i", $id);
                $stmt_items->execute();
                $get_data = $stmt_items->get_result();

                while ($data = $get_data->fetch_assoc()) {
                    $items .= "<tr>
                        <td>" . htmlspecialchars($data['food']) . "</td>
                        <td>" . htmlspecialchars($data['qty']) . "</td>
                    </tr>";
                }

                $items .= "<tr>
                    <th>Total Price</th>
                    <th>" . htmlspecialchars($row['total']) . "</th>
                </tr>";

                // Add the status field based on the current order status
                $status_disabled = $row['status'] === "confirmed" ? "disabled" : "";
                $items .= "<tr>
                    <th>Status</th>
                    <td>
                        <select onChange=\"change_stat('$id')\" name='status' id='$id' class='form-control' $status_disabled>
                            <option value='pending_$id' " . ($row['status'] === "pending" ? "selected" : "") . ">Pending</option>
                            <option value='confirmed_$id' " . ($row['status'] === "confirmed" ? "selected" : "") . ">Confirmed</option>
                        </select>
                    </td>
                </tr>";

                $items .= "</tbody></table>";
                $result = $info . $items;

                echo $result;
            } else {
                echo "No order found.";
            }
        }
    } elseif (isset($_POST['status'])) {

        $status = htmlentities($_POST['status'], ENT_QUOTES, 'UTF-8');

        if (!empty($status)) {
            $stat_arr = explode("_", $status);
            $stat_name = $stat_arr[0];
            $stat_id = intval($stat_arr[1]);

            // Use prepared statement for updating status
            $stmt_update = $db->prepare("UPDATE basket SET status = ? WHERE id = ? LIMIT 1");
            $stmt_update->bind_param("si", $stat_name, $stat_id);
            $update = $stmt_update->execute();

            if ($update) {
                echo "Status updated to: $stat_name";
            } else {
                echo "Failed to update status.";
            }
        }
    }
}
?>
