<?php
session_start();
if (!isset($_SESSION['logged_id'])) {
    header('location: ../login/login.php');
}

require_once '../homeIncludes/dbconfig.php';
require_once '../tools/variables.php';
$page_title = 'ProtonTech | Home';
$home = '';
$servicetransac = 'account-active';
include_once('../homeIncludes/header.php');


$transaction_code = $_SESSION['transaction_code'];
$user_id = $_SESSION['logged_id'];

$query = "SELECT * 
FROM customer 
LEFT JOIN accounts ON customer.account_id=accounts.account_id 
LEFT JOIN service_request 
ON service_request.cust_id=customer.cust_id 
AND service_request.transaction_code='{$transaction_code}' 
WHERE accounts.account_id='{$user_id}'";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

?>

<body class="view-body">
    <?php include_once('../homeIncludes/homenav.php');?>

    <div class="accountcon">
        <div class="container-fluid">
            <div class="accheader">
                <h4>My Transactions <?php echo $user_id ." " .  $transaction_code?></h4>
            </div>
            <div class="row">

                <div class="col-sm-3 sidebar">
                    <div class="accon d-flex align-items-center">
                        <img src="../img/usericon.png" alt="user icon" class="user-icon">
                        <h5 class="mb-0"><?php echo $row['fname'] ." " .  $row['lname']?></h5>
                    </div>
                    <div class="rprq">
                        <a href="../repair/pending-transaction.php" class="<?php echo $repairtransac; ?>">Repair
                            request</a>
                    </div>
                    <div>
                        <a href="../service/pending-transaction.php" class="<?php echo $servicetransac; ?>">Service
                            request</a>
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
                    <?php
                    // Get the counts for each status
                    $query_pending = "SELECT * FROM service_request 
                    LEFT JOIN customer ON service_request.cust_id = customer.cust_id
                    LEFT JOIN accounts ON customer.account_id = accounts.account_id
                    WHERE status='Pending' AND accounts.account_id = '{$user_id}';";
                    $result_pending = mysqli_query($conn, $query_pending);
                    $num_pending = mysqli_num_rows($result_pending);

                    $query_in_progress = "SELECT * FROM service_request 
                    LEFT JOIN customer ON service_request.cust_id = customer.cust_id
                    LEFT JOIN accounts ON customer.account_id = accounts.account_id
                    WHERE status='In-progress' AND accounts.account_id = '{$user_id}';";
                    $result_in_progress = mysqli_query($conn, $query_in_progress);
                    $num_in_progress = mysqli_num_rows($result_in_progress);

                    $query_done = "SELECT * FROM service_request 
                    LEFT JOIN customer ON service_request.cust_id = customer.cust_id
                    LEFT JOIN accounts ON customer.account_id = accounts.account_id
                    WHERE status='Underway' AND accounts.account_id = '{$user_id}';";
                    $result_done = mysqli_query($conn, $query_done);
                    $num_done = mysqli_num_rows($result_done);

                    $query_completed = "SELECT * FROM service_request 
                    LEFT JOIN customer ON service_request.cust_id = customer.cust_id
                    LEFT JOIN accounts ON customer.account_id = accounts.account_id
                    WHERE status='Completed' AND accounts.account_id = '{$user_id}';";
                    $result_completed = mysqli_query($conn, $query_completed);
                    $num_completed = mysqli_num_rows($result_completed);

                    // Set the notification count and style for each status
                    $notification_count_pending = $num_pending > 0 ? $num_pending : "";
                    $notification_style_pending = $num_pending > 0 ? "style='display: inline-block;'" : "";

                    $notification_count_in_progress = $num_in_progress > 0 ? $num_in_progress : "";
                    $notification_style_in_progress = $num_in_progress > 0 ? "style='display: inline-block;'" : "";

                    $notification_count_done = $num_done > 0 ? $num_done : "";
                    $notification_style_done = $num_done > 0 ? "style='display: inline-block;'" : "";

                    $notification_count_completed = $num_completed > 0 ? $num_completed : "";
                    $notification_style_completed = $num_completed > 0 ? "style='display: inline-block;'" : "";
                    ?>

                </div>
                <div class="col-sm-9 accform ">
                    <nav class="nav nav-pills flex-column flex-sm-row">
                        <a class="flex-sm-fill text-sm-center nav-link" aria-current="page"
                            href="pending-transaction.php">Pending
                            <?php
                                if($notification_count_pending){
                                    echo'<span class="count-symbol bg-danger"></span>';
                                }
                                ?>
                        </a>
                        <a class="flex-sm-fill text-sm-center nav-link" href="repairing-transaction.php">In-progress
                            <?php
                                if($notification_style_in_progress){
                                    echo'<span class="count-symbol bg-danger"></span>';
                                }
                                ?>
                        </a>
                        <a class="flex-sm-fill text-sm-center nav-link" href="pickup-transaction.php">Underway
                            <?php
                                if($notification_style_done){
                                    echo'<span class="count-symbol bg-danger"></span>';
                                }
                                ?>
                        </a>
                        <a class="flex-sm-fill text-sm-center nav-link active" href="completed-transaction.php">Completed
                            <?php
                                if($notification_count_completed){
                                    echo'<span class="count-symbol bg-danger"></span>';
                                }
                                ?>
                        </a>
                    </nav>
                    <?php
                    $query = "SELECT service_request.*, 
                    technician.fname AS tech_fname, 
                    technician.lname AS tech_lname, 
                    technician.phone AS tech_phone,
                    technician.status AS tech_status, 
                    customer.fname AS cust_fname, 
                    customer.lname AS cust_lname, 
                    customer.phone AS cust_phone,
                    service_request.status AS sr_status, 
                    GROUP_CONCAT(CONCAT(technician.fname, ' ', technician.lname)) AS tech_names,
                    GROUP_CONCAT(DISTINCT CONCAT(technician.phone)) AS tech_phones,
                    accounts.*,
                    technician.*,
                    services.*,
                    package.*,
                    customer.*
                    FROM service_request
                    LEFT JOIN service_request_technicians ON service_request.sreq_id = service_request_technicians.sreq_id
                    LEFT JOIN technician ON service_request_technicians.tech_id = technician.tech_id
                    LEFT JOIN services ON service_request.service_id = services.service_id
                    LEFT JOIN package ON service_request.pkg_id = package.pkg_id
                    LEFT JOIN customer ON service_request.cust_id = customer.cust_id
                    LEFT JOIN accounts ON customer.account_id = accounts.account_id
                    WHERE service_request.status = 'Completed' OR service_request.status = 'Cancelled' AND accounts.account_id = '{$user_id}';";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) { ?>
                    <div class="d-flex flex-wrap pending-card">
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <?php
                            $_SESSION['transaction_id'] = $row['transaction_code'];
                            $_SESSION['sreq_id'] = $row['sreq_id'];
                            ?>
                        <a href="../service/view-trans.php" class="viewtrans">
                            <div class="card mb-3 transaction-details-card">
                                <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="trans_image center">
                                            <?php
        $imageData = base64_encode($row['image']);
        $src = 'data:image/jpeg;base64,'.$imageData;
        echo '<img src="'.$src.'" id="main_product_image" width="350">';
    ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Transaction
                                                #:</span>
                                            <span class="text-primary"><?php echo $row['transaction_code']?></span>
                                        </div>
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Status:</span>
                                            <span
                                                class="transaction-details-pending"><?php echo $row['sr_status']?></span>
                                        </div>
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Service
                                                Type:</span>
                                            <span><?php echo $row['service_name']?></span>
                                        </div>
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Package
                                                Type:</span>
                                            <span
                                                class="transaction-details-none text-secondary"><?php echo $row['name']?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Date
                                                Requested:</span>
                                            <span><?php echo $row['date_req']?></span>
                                        </div>
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Estimated
                                                Completion:</span>
                                            <?php
                                                    if($row['dat_date'] == ''){
                                                        echo '<span class="tbh">';
                                                        echo '<i class="fas fa-exclamation-circle"></i>' . 'TBA';
                                                        echo '</span>';
                                                    }else{
                                                       
                                                        echo '<span class="">';
                                                        echo $row['dat_date'] . " day(s)";
                                                        echo '</span>';
                                                    }
                                                    ?>
                                        </div>
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Assigned
                                                Technician(s):</span>

                                            <?php
                                                    if($row['tech_names'] == ''){
                                                        echo '<span class="tbh">';
                                                        echo '<i class="fas fa-exclamation-circle"></i>' . 'TBA';
                                                        echo '</span>';
                                                    }else{
                                                       
                                                        echo '<span class="">';
                                                        echo  $row['tech_names'];
                                                        echo '</span>';
                                                    }
                                                    ?>

                                        </div>
                                        <div class="transaction-details-row">
                                            <span class="fw-bold me-2 transaction-details-label">Technician's
                                                Contact:</span>
                                            <?php
                                                    if($row['tech_phones'] == ''){
                                                        echo '<span class="tbh">';
                                                        echo '<i class="fas fa-exclamation-circle"></i>' . 'TBA';
                                                        echo '</span>';
                                                    }else{
                                                       
                                                        echo '<span class="">';
                                                        echo  $row['tech_phones'];
                                                        echo '</span>';
                                                    }
                                                    ?>
                                        </div>
                                    </div>
                                    <div class="text-start">
                                        <form method="post" action="../repair-invoice/booking-repair-pdf.php"
                                            target="_blank">
                                            <?php
    if($row['sr_status'] == 'Pending') {
        echo '<button type="submit" name="download" value="' . $row['sreq_id'] . '" class="btn btn-secondary">Download Ticket <i class="fas fa-download"></i></button>';
    }
    ?>
                                        </form>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </a>
                        <?php } ?>
                    </div>

                    </table>
                    <?php } else { ?>
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-exclamation-circle"></i> No Pending Transaction at the moment.
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