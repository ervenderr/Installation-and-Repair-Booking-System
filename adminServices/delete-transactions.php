<?php
include_once('../admin_includes/header.php');
require_once '../homeIncludes/dbconfig.php';
include_once('../tools/variables.php');

$rowid = $_GET['rowid'];
$tcode = $_GET['transaction_code'];
    
// Perform the query to retrieve the data for the selected row
$query = "SELECT service_request.service_id, service_request.transaction_code, service_request.status, customer.fname, customer.lname, customer.address, customer.phone, accounts.email, service_request.service_id, service_request.pkg_id, service_request.date_req, service_request.date_completed, service_request.other
          FROM service_request
          JOIN customer ON service_request.cust_id = customer.cust_id
          JOIN accounts ON customer.account_id = accounts.account_id
          WHERE service_request.transaction_code = '" . $tcode . "';";
$result = mysqli_query($conn, $query);


// Check if the query was successful and output the data
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

}

?>

<?php
$query5 = "DELETE FROM `sr_timeline` WHERE sr_timeline.sreq_id = '" . $rowid . "';";
$result5 = mysqli_query($conn, $query5);

$query4 = "DELETE FROM `service_request_technicians` WHERE service_request_technicians.sreq_id = '" . $rowid . "';";
$result4 = mysqli_query($conn, $query4);

if ($result5) {
    $query6 = "DELETE FROM `service_request` WHERE service_request.transaction_code = '" . $tcode . "';";
    $result6 = mysqli_query($conn, $query6);

    if($result6){

        $_SESSION['msg2'] = "Record Deleted Successfully";
        header("location: transactions.php");
    }else {
        echo "FAILED: " . mysqli_error($conn);
     }
}else {
    echo "FAILED: " . mysqli_error($conn);
 }
?>