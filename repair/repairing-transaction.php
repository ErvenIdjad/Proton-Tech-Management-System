<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['logged_id'])) {
    header('location: ../login/login.php');
}

require_once '../homeIncludes/dbconfig.php';
require_once '../tools/variables.php';
$page_title = 'ProtonTech | Home';
$home = '';
$repairtransac = 'account-active';
include_once('../homeIncludes/header.php');


$transaction_code = $_SESSION['transaction_code'];
$user_id = $_SESSION['logged_id'];

$query = "SELECT * 
FROM customer 
LEFT JOIN accounts ON customer.account_id=accounts.account_id 
LEFT JOIN rprq 
ON rprq.cust_id=customer.cust_id 
AND rprq.transaction_code='{$transaction_code}' 
WHERE accounts.account_id='{$user_id}'";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);




?>

<body>
    <?php include_once('../homeIncludes/homenav.php');?>

    <div class="accountcon">
        <div class="container-fluid">
            <div class="accheader">
                <h4>My Transactions</h4>
            </div>
            <div class="row">

                <div class="col-sm-3 sidebar">
                    <div class="accon d-flex align-items-center">
                        <img src="../img/usericon.png" alt="user icon" class="user-icon">
                        <h5 class="mb-0"><?php echo $row['fname'] ." " .  $row['lname']?></h5>
                    </div>
                    <div class="rprq">
                        <a href="../repair/repair-transanction.php" class="<?php echo $repairtransac; ?>">Repair
                            request</a>
                    </div>
                    <div>
                        <a href="../service/service-transaction.php" class="<?php echo $servicetransac; ?>">Service
                            request</a>
                    </div>
                    <div>
                        <a href="../mytransactions/transaction-history.php" class="<?php echo $history; ?>">Transaction
                            History</a>
                    </div>
                    <div>
                        <a href="../mytransactions/account.php" class="<?php echo $accsetting; ?>">Account setting</a>
                    </div>
                    <div>
                        <a href="../login/logout.php">Logout</a>
                    </div>
                    <div class="ticket">
                        <div class="ticket-header">
                            <span class="text">Proton</span><span class="green">Tech</span></a>
                            <p>Confirmation Ticket</p>
                        </div>

                        <div class="ticket-body">
                            <div>
                                <p class="nopad">Transaction Code:</p>
                                <p><?php echo $row['transaction_code']?></p>
                            </div>
                            <div>
                                <p class="nopad">Customer Name:</p>
                                <p><?php echo $row['fname'] ." " .  $row['lname']?></p>
                            </div>
                            <div>
                                <p class="nopad">Date:</p>
                                <p><?php echo $row['date_req']?></p>
                            </div>
                        </div>

                        <div class="ticket-footer"></div>
                    </div>


                </div>
                <div class="col-sm-9 accform ">
                <nav class="nav nav-pills flex-column flex-sm-row">
                        <a class="flex-sm-fill text-sm-center nav-link" aria-current="page"
                            href="pending-transanction.php">Pending</a>
                        <a class="flex-sm-fill text-sm-center nav-link active" href="repairing-transaction.php">Repairing</a>
                        <a class="flex-sm-fill text-sm-center nav-link" href="pickup-transaction.php">To pickup</a>
                        <a class="flex-sm-fill text-sm-center nav-link" href="completed-transaction.php">Completed</a>
                    </nav>

                    <?php

                    $query = "SELECT *
                    FROM rprq
                    LEFT JOIN technician ON rprq.tech_id = technician.tech_id
                    WHERE rprq.status = 'In-Progress'";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) { ?>
                    <div class="d-flex flex-wrap pending-card">
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="card mb-3 transaction-details-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Transaction #:</span>
                                            <span class="text-primary"><?php echo $row['transaction_code']?></span>
                                        </div>
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Status:</span>
                                            <span class="transaction-details-pending"><?php echo $row['status']?></span>
                                        </div>
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Electronic Type:</span>
                                            <span><?php echo $row['etype']?></span>
                                        </div>
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Defects:</span>
                                            <span class="transaction-details-none"><?php echo $row['defective']?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Shipping:</span>
                                            <span
                                                class="transaction-details-standard-shipping"><?php echo $row['shipping']?></span>
                                        </div>
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Date Requested:</span>
                                            <span><?php echo $row['date_req']?></span>
                                        </div>
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Expected Completion:</span>
                                            <span><?php echo $row['date_completed']?></span>
                                        </div>
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Assigned Technician:</span>
                                            <span><?php echo $row['fname'] . " " . $row['lname']?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    </table>
                    <?php } else { ?>
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-exclamation-circle"></i> No Transaction at the moment.
                    </div>
                    <?php } ?>


                </div>
            </div>

        </div>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
        <script>
        document.getElementById("download").addEventListener("click", function() {
            const ticket = document.querySelector(".ticket");
            html2canvas(ticket).then(function(canvas) {
                const element = document.createElement("a");
                element.setAttribute("href", canvas.toDataURL("image/png").replace("image/png",
                    "image/octet-stream"));
                element.setAttribute("download", "ticket.png");
                element.style.display = "none";
                document.body.appendChild(element);
                element.click();
                document.body.removeChild(element);
            });
        });
        </script>

</body>

</html>