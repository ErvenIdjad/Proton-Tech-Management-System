<?php
session_start();
require_once '../homeIncludes/dbconfig.php';



if(isset($_POST["submit"])){
    $invoice_no = mysqli_real_escape_string($conn, $_POST["invoice_no"]);
    $invoice_date = date("Y-m-d", strtotime($_POST["invoice_date"]));
    $grand_total = mysqli_real_escape_string($conn, $_POST["grand_total"]);

    $accountid = $_SESSION['account_id'];
    $rowid = $_SESSION['rowid'];
    $transaction_code = $_SESSION['transaction_code'];

    // Insert data into invoice table
    $sql = "INSERT INTO invoice (invoice_no, invoice_date, grand_total) VALUES ('{$invoice_no}', '{$invoice_date}', '{$grand_total}') ";
    if($conn->query($sql)){
        // Get the invoice ID generated by the database
        $invoice_id = $conn->insert_id;
        
        // Insert data into invoice_desc table
        $sql2 = "INSERT INTO invoice_desc (invoice_id, descname, descPrice, descQty, total) VALUES ";
        $rows = array();
        for($i=0;$i<count($_POST["pname"]);$i++) {
            $pname = mysqli_real_escape_string($conn, $_POST["pname"][$i]);
            $price = mysqli_real_escape_string($conn, $_POST["price"][$i]);
            $qty = mysqli_real_escape_string($conn, $_POST["qty"][$i]);
            $total = mysqli_real_escape_string($conn, $_POST["total"][$i]);
            $rows[] = "('{$invoice_id}', '{$pname}', '{$price}', '{$qty}', '{$total}')";
        }
        $sql2 .= implode(",", $rows);

        if($conn->query($sql2)){
            $_SESSION['invoice_id'] = $invoice_id;
            // Update service_request table with the new invoice ID
            $sql3 = "UPDATE service_request SET invoice_id = '{$invoice_id}' WHERE id = '{$_SESSION['rowid']}'";
            if($conn->query($sql3)){
                // Redirect to view-transaction.php with success message

                header("location: ../adminServices/view-transactions.php?msg=Record updated Successfully.&transaction_code=" . $transaction_code . "&rowid=" . $rowid);
                exit();
            } else {
                echo "<div class='alert alert-danger'>Failed to update service_request table.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Failed to insert data into invoice_desc table.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Failed to insert data into invoice table.</div>";
    }
}            


?>