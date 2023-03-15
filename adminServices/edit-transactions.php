<?php
session_start();
include_once('../admin_includes/header.php');
require_once '../homeIncludes/dbconfig.php';
include_once('../tools/variables.php');

$rowid = $_GET['rowid'];
$tcode = $_GET['transaction_code'];


$rpactive = "active";
$rpshow = "show";
$rptrue = "true";

    
// Perform the query to retrieve the data for the selected row
$query = "SELECT sr.*, 
       c.fname AS cust_fname, 
       c.lname AS cust_lname, 
       t.fname AS tech_fname, 
       t.lname AS tech_lname, 
       t.status AS tech_status,
       a.*,
       c.*,
       s.*,
       p.*
FROM service_request sr
LEFT JOIN customer c ON sr.cust_id = c.cust_id
LEFT JOIN accounts a ON c.account_id = a.account_id
LEFT JOIN services s ON sr.service_id = s.service_id
LEFT JOIN package p ON sr.pkg_id = p.pkg_id
LEFT JOIN technician t ON sr.tech_id = t.tech_id
WHERE sr.transaction_code = '" . $tcode . "';";
$result = mysqli_query($conn, $query);


// Check if the query was successful and output the data
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

}
$_SESSION['account_id'] = $row['account_id'];
$_SESSION['rowid'] = $_GET['rowid'];
$_SESSION['transaction_code'] = $_GET['transaction_code'];
?>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <?php include_once('../admin_includes/navbar.php'); ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <?php include_once('../admin_includes/sidebar.php'); ?>

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon text-white me-2">
                            <i class="fas fa-cogs menu-icon"></i>
                            </span> Repair Transaction <span class="bread">/ Update transaction</span>
                        </h3>
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <?php
                                $href = "";
                                if ($row['status'] == 'Pending'){
                                    $href = "pendings.php";
                                }else{
                                    $href = "transactions.php";
                                }
                                ?>
                                <a href="<?php echo $href; ?>">
                                    <li class="breadcrumb-item active" aria-current="page">
                                        <span></span><i
                                            class=" mdi mdi-arrow-left-bold icon-sm text-primary align-middle">Back
                                        </i>
                                    </li>
                                </a>
                            </ul>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <form class="form-sample" action="edit-processs.php" method="POST"
                                        enctype="multipart/form-data">
                                        <?php
                                        $query6 = "SELECT sr.*, 
                                        c.fname AS cust_fname, 
                                        c.lname AS cust_lname, 
                                        t.fname AS tech_fname, 
                                        t.lname AS tech_lname, 
                                        t.status AS tech_status,
                                        a.*,
                                        c.*,
                                        s.*,
                                        p.*
                                 FROM service_request sr
                                 LEFT JOIN customer c ON sr.cust_id = c.cust_id
                                 LEFT JOIN accounts a ON c.account_id = a.account_id
                                 LEFT JOIN services s ON sr.service_id = s.service_id
                                 LEFT JOIN package p ON sr.pkg_id = p.pkg_id
                                 LEFT JOIN technician t ON sr.tech_id = t.tech_id
                                 WHERE sr.transaction_code = '" . $tcode . "';";
                                        $result6 = mysqli_query($conn, $query6);
                                        
                                        // Check if the query was successful and output the data
                                        if (mysqli_num_rows($result6) > 0) {
                                            $row6 = mysqli_fetch_assoc($result6);
                                        }
                                        $selected_technician_id = $row6['tech_id'];
                                        ?>
                                        <p class="card-description">Update Personal info </p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-form-label" for="fname">First Name</label>
                                                    <div class="">
                                                        <input type="text" name="fname" class="form-control"
                                                            value="<?php echo $row6['fname']; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-form-label" for="lname">Last Name</label>
                                                    <div class="">
                                                        <input type="text" name="lname" class="form-control"
                                                            value="<?php echo $row6['lname']; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="email" class="col-form-label">Email</label>
                                                    <div class="">
                                                        <input name="email" class="form-control" type="email"
                                                            value="<?php echo $row6['email']; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="phone" class="col-form-label">Phone</label>
                                                    <div class="">
                                                        <input name="phone" class="form-control" type="tel"
                                                            value="<?php echo $row6['phone']; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="address" class="col-form-label">Address</label>
                                                    <div class="">
                                                        <input name="address" class="form-control" type="text"
                                                            value="<?php echo $row6['address']; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="status" class="col-form-label">Status</label>
                                                    <div class="">
                                                    <select name="status" class="form-control">
                                                            <option value="Pending"
                                                                <?php if ($row6['status'] == 'Pending') echo 'selected'; ?>>Pending
                                                            </option>
                                                            <option value="In-progress"
                                                                <?php if ($row6['status'] == 'In-progress') echo 'selected'; ?>>
                                                                In-progress
                                                            </option>
                                                            <option value="Done"
                                                                <?php if ($row6['status'] == 'Done') echo 'selected'; ?>>
                                                                Done
                                                            </option>
                                                            <option value="Completed"
                                                                <?php if ($row6['status'] == 'Completed') echo 'selected'; ?>>
                                                                Completed
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="stype" class="col-form-label">Service Type</label>
                                                    <div class="">
                                                        <select name="stype" class="form-control">
                                                            <option value="None">--- Select ---</option>
                                                            <?php
                                                                $query = "SELECT * FROM services";
                                                                $result = mysqli_query($conn, $query);
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    $selected = ($row6['service_id'] == $row['service_id']) ? "selected" : "";
                                                                    echo "<option value='{$row['service_id']}' {$selected}>{$row['service_name']}</option>";
                                                                }
                                                                ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="package" class="col-form-label">Package Type</label>
                                                    <div class="">
                                                        <select name="package" id="package" class="form-control">
                                                            <option value="None">--- Select ---</option>
                                                            <?php
                                                                $query = "SELECT * FROM package";
                                                                $result = mysqli_query($conn, $query);
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    $selected = ($row6['pkg_id'] == $row['pkg_id']) ? "selected" : "";
                                                                    echo "<option value='{$row['pkg_id']}' {$selected}>{$row['name']}</option>";
                                                                }
                                                                ?>
                                                        </select>
                                                        <span id="package_error" class="error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="other" class="col-form-label">Defective</label>
                                                    <div class="">
                                                        <input name="other" type="text" class="form-control"
                                                            value="<?php echo $row6['other']; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="technician" class="col-form-label">Assigned
                                                    Technician</label>
                                                    <div class="">
                                                    <select name="technician" class="form-control">
                                                            <option value="None">--- Select ---</option>
                                                            <?php
                                                                $sql2 = "SELECT * FROM technician";
                                                                $result3 = mysqli_query($conn, $sql2);
                                                                while ($technician = mysqli_fetch_assoc($result3)) {
                                                                    $tech_id = mysqli_real_escape_string($conn, $technician['tech_id']);
                                                                    $selected = ($tech_id == $selected_technician_id) ? "selected" : "";
                                                                    echo "<option value='{$tech_id}' {$selected}>{$technician['fname']} {$technician['lname']}</option>";
                                                                }                                                        
                                                                ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="date" class="col-form-label">Date</label>
                                                    <div class="">
                                                        <input name="date" type="date" class="form-control"
                                                            placeholder="dd/mm/yyyy"
                                                            value="<?php echo $row6['date_req']; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="completed" class="col-form-label">Date Completed</label>
                                                    <div class="">
                                                        <input name="completed" type="date" class="form-control"
                                                            placeholder="dd/mm/yyyy"
                                                            value="<?php echo $row6['date_completed']; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="payment" class="col-form-label">Payment</label>
                                                    <div class="">
                                                        <input name="payment" class="form-control" type="text"
                                                            value="$ " />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input name="submit" type="submit" class="btn btn-info"
                                                value="Update Transaction" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    <footer class="footer">
                        <div class="container-fluid d-flex justify-content-between">
                            <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright ©
                                protontech.com 2023</span>
                            <span class="float-none float-sm-end mt-1 mt-sm-0 text-end"><a
                                    href="https://www.proton-tech.online/" target="_blank">ProtonTech</a></span>
                        </div>
                    </footer>
                    <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->
        <!-- plugins:js -->
        <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <script src="../assets/vendors/chart.js/Chart.min.js"></script>
        <script src="../assets/js/jquery.cookie.js" type="text/javascript"></script>
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="../assets/js/off-canvas.js"></script>
        <script src="../assets/js/hoverable-collapse.js"></script>
        <script src="../assets/js/misc.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page -->
        <script src="../assets/js/dashboard.js"></script>
        <script src="../assets/js/todolist.js"></script>
        <!-- End custom js for this page -->
        <script>
        // Add an event listener to the eye icon to show the modal window
        const viewAccountIcons = document.querySelectorAll('.view-account');
        viewAccountIcons.forEach(icon => {
            icon.addEventListener('click', () => {
                const rowid = icon.getAttribute('data-rowid');
                const modal = new bootstrap.Modal(document.getElementById('accountModal'));
                modal.show();
                // TODO: Populate the account form with data from the rowid
            });
        });
        </script>

        
</body>

</html>