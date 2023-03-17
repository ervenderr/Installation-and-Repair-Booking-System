<?php
session_start();
include_once('../admin_includes/header.php');
require_once '../homeIncludes/dbconfig.php';
include_once('../tools/variables.php');



$rpactive = "active";
$rpshow = "show";
$rptrue = "true";

$rowid = $_GET['rowid'];
$transaction_code = $_GET['transaction_code'];

$query = "SELECT rprq.id, rprq.transaction_code, rprq.status, customer.fname, customer.lname, customer.address, customer.phone, accounts.account_id, accounts.email, rprq.etype, rprq.defective, rprq.date_req, rprq.date_completed, rprq.shipping
          FROM rprq
          JOIN customer ON rprq.cust_id = customer.cust_id
          JOIN accounts ON customer.account_id = accounts.account_id
          WHERE rprq.transaction_code = '" . $transaction_code . "';";
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
                                <i class="far fa-file-invoice menu-icon"></i>
                            </span> Invoice form <span class="bread">/ Transaction code: <?php echo $_SESSION['transaction_code']; ?></span>
                        </h3>
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <?php echo '<a class="icns" href="../adminRepair/view-transaction.php?transaction_code=' . $row['transaction_code'] . '&rowid=' .  $row['id'] . '">';?>
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
                                    <?php                        
                                    function getNextInvoiceNumber() {
                                        $host = 'localhost';
                                        $user = 'u721805960_protontech';
                                        $password = 'KUWI&0Sz';
                                        $dbname = 'u721805960_protontech';
                                        $conn = mysqli_connect($host, $user, $password, $dbname);
                                        if (!$conn) {
                                            die("Connection failed: " . mysqli_connect_error());
                                        }
                                    
                                       
                                        $sql = "SELECT MAX(invoice_id) AS last_invoice_number FROM invoice";
                                        $result = mysqli_query($conn, $sql);
                                    
                                        $row = mysqli_fetch_assoc($result);
                                        $last_invoice_number = 1000 . $row['last_invoice_number'] ?? 0;
                                    
                                        $next_invoice_number = $last_invoice_number + 1;
                                    
                                        mysqli_close($conn);
                                    
                                        return $next_invoice_number;
                                    }
                                    
                                    
                                    $invoice_number = getNextInvoiceNumber();
                                ?>
                                    <form method='post' action='rp-invoice-process.php' autocomplete='off'>
                                        <div class='row'>
                                            <div class='col-md-4'>
                                                <h5 class='text-success'>Invoice Details</h5>
                                                <div class='form-group'>
                                                    <label>Invoice No</label>
                                                    <input type='text' name='invoice_no' value='<?php echo $invoice_number; ?>' required class='form-control'>
                                                </div>
                                                <div class='form-group'>
                                                    <label>Invoice Date</label>
                                                    <input type='date' name='invoice_date' id='date' required
                                                        class='form-control'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='row'>
                                            <div class='col-md-12'>
                                                <h5 class='text-success'>Product Details</h5>
                                                <table class='table table-bordered'>
                                                    <thead>
                                                        <tr>
                                                            <th>Description</th>
                                                            <th>Price</th>
                                                            <th>Qty</th>
                                                            <th>Total</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id='product_tbody'>
                                                        <tr>
                                                            <td><input type='text' required name='pname[]'
                                                                    class='form-control'></td>
                                                            <td><input type='text' required name='price[]'
                                                                    class='form-control price'></td>
                                                            <td><input type='text' required name='qty[]'
                                                                    class='form-control qty'></td>
                                                            <td><input type='text' required name='total[]'
                                                                    class='form-control total'></td>
                                                            <td><input type='button' value='x'
                                                                    class='btn btn-danger btn-sm btn-row-remove'> </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td><input type='button' value='+ Add Row'
                                                                    class='btn btn-primary btn-sm' id='btn-add-row'>
                                                            </td>
                                                            <td colspan='2' class='text-right'>Total</td>
                                                            <td><input type='text' name='grand_total' id='grand_total'
                                                                    class='form-control' required></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                <input type='submit' name='submit' value='Save Invoice'
                                                    class='btn btn-success '>
                                            </div>
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
        
        
        <script src="https://code.jquery.com/ui/1.13.0-rc.3/jquery-ui.min.js" integrity="sha256-R6eRO29lbCyPGfninb/kjIXeRjMOqY3VWPVk6gMhREk=" crossorigin="anonymous"></script>
        
        <script>
      $(document).ready(function(){
        
        $("#btn-add-row").click(function(){
          var row="<tr> <td><input type='text' required name='pname[]' class='form-control'></td> <td><input type='text' required name='price[]' class='form-control price'></td> <td><input type='text' required name='qty[]' class='form-control qty'></td> <td><input type='text' required name='total[]' class='form-control total'></td> <td><input type='button' value='x' class='btn btn-danger btn-sm btn-row-remove'> </td> </tr>";
          $("#product_tbody").append(row);
        });
        
        $("body").on("click",".btn-row-remove",function(){
          if(confirm("Are You Sure?")){
            $(this).closest("tr").remove();
            grand_total();
          }
        });

        $("body").on("keyup",".price",function(){
          var price=Number($(this).val());
          var qty=Number($(this).closest("tr").find(".qty").val());
          $(this).closest("tr").find(".total").val(price*qty);
          grand_total();
        });
        
        $("body").on("keyup",".qty",function(){
          var qty=Number($(this).val());
          var price=Number($(this).closest("tr").find(".price").val());
          $(this).closest("tr").find(".total").val(price*qty);
          grand_total();
        });      
        
        function grand_total(){
          var tot=0;
          $(".total").each(function(){
            tot+=Number($(this).val());
          });
          $("#grand_total").val(tot);
        }
      });
    </script>

        <script>
        const form = document.querySelector('.form-sample');
        const fname = form.querySelector('input[name="fname"]');
        const lname = form.querySelector('input[name="lname"]');
        // const email = form.querySelector('input[name="email"]');
        const phone = form.querySelector('input[name="phone"]');
        const address = form.querySelector('input[name="address"]');


        form.addEventListener('submit', (event) => {
            let error = false;

            if (fname.value === '') {
                fname.nextElementSibling.innerText = 'Please enter first name';
                error = true;
            } else if (!/^[A-Z][a-z]*$/.test(fname.value)) {
                fname.nextElementSibling.innerText = 'First name should be capitalized';
                error = true;
            } else {
                fname.nextElementSibling.innerText = '';
            }

            if (lname.value === '') {
                lname.nextElementSibling.innerText = 'Please enter last name';
                error = true;
            } else if (!/^[A-Z][a-z]*$/.test(lname.value)) {
                lname.nextElementSibling.innerText = 'Last name should be capitalized';
                error = true;
            } else {
                lname.nextElementSibling.innerText = '';
            }

            // if (email.value === '') {
            //     email.nextElementSibling.innerText = 'Please enter your email';
            //     error = true;
            // } else {
            //     email.nextElementSibling.innerText = '';
            // }

            if (phone.value === '') {
                phone.nextElementSibling.innerText = 'Please enter phone number';
                error = true;
            } else if (!/^\d{11}$/.test(phone.value)) {
                phone.nextElementSibling.innerText = 'Please enter a valid 11-digit phone number';
                error = true;
            } else {
                phone.nextElementSibling.innerText = '';
            }

            if (address.value === '') {
                address.nextElementSibling.innerText = 'Please enter address';
                error = true;
            } else if (!/^[a-zA-Z0-9\s,'-]*$/.test(address.value)) {
                address.nextElementSibling.innerText = 'Please enter a valid address';
                error = true;
            } else {
                address.nextElementSibling.innerText = '';
            }

            if (error) {
                event.preventDefault(); // Prevent form submission if there are errors
            } else {
                // Submit form to server if there are no errors
                // You can use AJAX to submit the form asynchronously, or just let it submit normally
            }
        });
        </script>
</body>

</html>
