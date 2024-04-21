<?php 

require_once 'core.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $startDate = filter_input(INPUT_POST, 'startDate', FILTER_SANITIZE_STRING);
    $endDate = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_STRING);

    // Validate date format
    if (!validateDate($startDate) || !validateDate($endDate)) {
        echo "Invalid date format";
        exit();
    }

    // Prepare SQL statement using prepared statement
    $sql = "SELECT * FROM orders WHERE order_date BETWEEN ? AND ? AND order_status = 1";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Create the HTML output
        echo '
        <style>
            /* Styling for the report */
            .report-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            .report-table th, .report-table td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
                border-radius: 5px;
            }
            .report-table th {
                background-color: #f0f0f0;
                color: black;
                font-weight: bold;
            }
            .print-button {
                margin-bottom: 10px;
                padding: 10px 15px;
                background-color: #4CAF50;
                color: white;
                border: none;
                cursor: pointer;
                border-radius: 5px;
                font-size: 16px;
            }
            .print-button:hover {
                background-color: #45a049;
            }
            .report-header {
                text-align: center;
                font-weight: bold;
                font-size: 24px;
            }
        </style>
        
        <div class="report-header">Order Report ('.$startDate.' to '.$endDate.')</div>
        <button class="print-button" onclick="window.print()">Print Report</button>
        
        <table class="report-table">
            <tr>
                <th>Order Date</th>
                <th>Client Name</th>
                <th>Contact</th>
                <th>Paid Amount</th>
                <th>Due Amount</th>
            </tr>';

        // Initialize total amounts
        $totalPaidAmount = 0;
        $totalDueAmount = 0;

        // Loop through results and populate table
        while ($row = $result->fetch_assoc()) {
            // Format the date and monetary values for better readability
            $orderDate = date('d-m-Y', strtotime($row['order_date']));
            $paidAmount = number_format($row['paid'], 2);
            $dueAmount = number_format($row['due'], 2);

            echo "
            <tr>
                <td>$orderDate</td>
                <td>{$row['client_name']}</td>
                <td>{$row['client_contact']}</td>
                <td>$paidAmount</td>
                <td>$dueAmount</td>
            </tr>";

            // Accumulate total paid and due amounts
            $totalPaidAmount += $row['paid'];
            $totalDueAmount += $row['due'];
        }

        // Format total amounts for display
        $formattedTotalPaid = number_format($totalPaidAmount, 2);
        $formattedTotalDue = number_format($totalDueAmount, 2);

        // Add footer for total paid and due amounts
        echo "
            <tr>
                <td colspan='3'><strong>Total</strong></td>
                <td><strong>$formattedTotalPaid</strong></td>
                <td><strong>$formattedTotalDue</strong></td>
            </tr>
        </table>";
    } else {
        echo "No records found";
    }
} else {
    echo "Invalid request method";
}

// Function to validate date format (YYYY-MM-DD)
function validateDate($date) {
    return preg_match("/^\d{4}-\d{2}-\d{2}$/", $date);
}

?>
