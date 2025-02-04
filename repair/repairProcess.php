<?php
session_start();
require_once '../homeIncludes/dbconfig.php';

$transaction_code = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 11);
$cust_id = $_SESSION["cust_id"];
if(isset($_POST['etype'])){
    $etype = $_POST['etype'];
}
$defective = htmlentities($_POST['defective']);
$tech = htmlentities($_POST['tech']);
$other_defective = htmlentities($_POST['other_defective']);
$other_brand = htmlentities($_POST['other_brand']);
$ebrand = htmlentities($_POST['ebrand']);
$categname = htmlentities($_POST['categname']);
$shipping = $_POST['shipping'];
$imgcontent = "";

if(!empty($_FILES['eimg']['name'])){
    $filename = $_FILES['eimg']['name'];
    $filetype = pathinfo($filename, PATHINFO_EXTENSION);
    $allowedtypes = array('png', 'jpg', 'jpeg', 'gif');

    if(in_array($filetype,$allowedtypes)){
        $image = $_FILES['eimg']['tmp_name'];
        $imgcontent = addslashes(file_get_contents($image));
    }
}

$_SESSION['transaction_code'] = $transaction_code;
$status = htmlentities("Pending");


if ($defective === "other"){

    $query = "INSERT INTO rprq (cust_id, transaction_code, elec_id, subcateg_id, eb_id, other_defects, shipping, image, status, tech_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "isssssssss", $cust_id, $transaction_code, $etype, $categname, $ebrand, $other_defective, $shipping, $imgcontent, $status, $tech);
    mysqli_stmt_execute($stmt);
    
}elseif ($ebrand === "other"){

    $query = "INSERT INTO rprq (cust_id, transaction_code, elec_id, subcateg_id, other_brand, defect_id, shipping, image, status, tech_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "isssssssss", $cust_id, $transaction_code, $etype, $categname, $other_brand, $defective, $shipping, $imgcontent, $status, $tech);
    mysqli_stmt_execute($stmt);
    
}elseif ($ebrand === "other" && $defective === "other"){

    $query = "INSERT INTO rprq (cust_id, transaction_code, elec_id, subcateg_id, other_brand, other_defects, shipping, image, status, tech_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "isssssssss", $cust_id, $transaction_code, $etype, $categname, $other_brand, $other_defective, $shipping, $imgcontent, $status, $tech);
    mysqli_stmt_execute($stmt);
    
}else{

    $query = "INSERT INTO rprq (cust_id, transaction_code, elec_id, subcateg_id, eb_id, defect_id, shipping, image, status, tech_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "isssssssss", $cust_id, $transaction_code, $etype, $categname, $ebrand, $defective, $shipping, $imgcontent, $status, $tech);
    mysqli_stmt_execute($stmt);
}

// Get the ID of the newly inserted row
$newly_inserted_id = mysqli_insert_id($conn);
$tquery = "INSERT INTO rp_timeline (rprq_id, tm_date, tm_time, tm_status) VALUES ('$newly_inserted_id', NOW(), NOW(), '$status');";
$tresult = mysqli_query($conn, $tquery);

?>

