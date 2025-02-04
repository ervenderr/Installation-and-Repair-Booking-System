<?php
session_start();
include_once('../admin_includes/header.php');
require_once '../homeIncludes/dbconfig.php';
include_once('../tools/variables.php');

$search = "transaction.php";

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin'){
    header('location: ../login/login.php');
  }


include_once('../admin_includes/header.php');
require_once '../homeIncludes/dbconfig.php';

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
                                <i class="fas fa-tools menu-icon"></i>
                            </span> Repair Transaction
                        </h3>
                        <?php
            if (isset($_SESSION['msg'])) {
                $msg = $_SESSION['msg'];
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                '. $msg .'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
              unset ($_SESSION['msg']);
            }

            if (isset($_SESSION['msg2'])) {
                $msg2 = $_SESSION['msg2'];
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                '. $msg2 .'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }
            unset ($_SESSION['msg']);
        ?>
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item active btn-group-sm" aria-current="page">
                                    <button type="button" class="btn addnew" data-bs-toggle="modal"
                                        data-bs-target="#addTransactionModal">
                                        <i class=" mdi mdi-plus ">Transaction</i>
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row mg-btm">
                                <div class="col-sm-12 col-md-6 flex">
                                    <h4 class="card-title">List of Repair Transaction</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 grid-margin">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="myDataTable2">
                                            <thead>
                                                <tr class="bg-our">
                                                    <th> # </th>
                                                    <th> Transaction Code </th>
                                                    <th> Customer </th>
                                                    <th> Status </th>
                                                    <th> Date </th>
                                                    <th> Backlog </th>
                                                    <th> Action </th>
                                                </tr>
                                            </thead>
                                            <tbody id="myTable">
                                                <?php
                                                    // Perform the query
                                                    $query = "SELECT *
                                                        FROM rprq
                                                        JOIN customer ON rprq.Cust_id = customer.Cust_id
                                                        WHERE rprq.status != 'Pending' AND rprq.status != 'Diagnosing'
                                                        ORDER BY rprq.time DESC;";

                                                    $result = mysqli_query($conn, $query);
                                                    $id = 1;
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $modalId = 'editTransactionModal-' . $id;
                                                        echo '<tr>';
                                                        echo '<td>' . $id . '</td>';
                                                        echo '<td>' . $row['transaction_code'] . '</td>';
                                                        echo '<td>' . $row['fname'] . '  ' . $row['lname'] . '</td>';
                                                    
                                                        $statusClass = '';
                                                        
                                                        if ($row['status'] == 'Pending') {
                                                            $statusClass = 'badge-gradient-warning';
                                                        } else if ($row['status'] == 'In-progress' || $row['status'] == 'To repair') {
                                                            $statusClass = 'badge-gradient-info';
                                                        } else if ($row['status'] == 'Cancelled') {
                                                            $statusClass = 'badge-gradient-secondary';
                                                        } else {
                                                            $statusClass = 'badge-gradient-info';
                                                        }

                                                        $backlog = '';
                                                        if ($row['backlog'] == 1) {
                                                            $backlog = 'Yes';
                                                        }else{
                                                            $backlog = 'No';
                                                        }
                                                    
                                                        echo '<td><label class="badge ' . $statusClass . '">' . $row['status'] . '</label></td>';
                                                        echo '<td>' . $row['date_req'] . '</td>';
                                                        echo '<td><span class="not-back">'.$backlog.'</span></td>';
                                                        echo '<td>';
                                                        echo '<a class="icns" href="view-transaction.php?transaction_code=' . $row['transaction_code'] . '&rowid=' .  $row['id'] . '">';
                                                        echo '<i class="fas fa-eye text-white view-accoun view" data-rowid="' .  $row['id'] . '"></i>';
                                                        echo '</a>';
                                                        echo '<a class="icns" href="delete-transaction.php?transaction_code=' . $row['transaction_code'] . '&rowid=' .  $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this product?\')">';
                                                        echo '<i class="fas fa-trash-alt text-white view-account delete" data-rowid="' .  $row['id'] . '"></i>';
                                                        echo '</a>';
                                                        echo '</td>';
                                                        echo '</tr>';
                                                        $id++;
                                                    }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="addElecModal" tabindex="-1" aria-labelledby="addElecModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addElecModalLabel">Add New Electronic</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="add-electronic.php" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="electronic" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="electronic" name="electronic">
                                        <label for="cost" class="form-label">Cost</label>
                                        <input type="number" class="form-control" id="cost" name="cost">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <input name="submit" type="submit" class="btn btn-success" value="Save" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="addDefectModal" tabindex="-1" aria-labelledby="addDefectModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addDefectModalLabel">Add New Defect</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="add-defect.php" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="defect" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="defect" name="defect">
                                        <label for="cost" class="form-label">Cost</label>
                                        <input type="number" class="form-control" id="cost" name="cost">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <input name="submit" type="submit" class="btn btn-success" value="Save" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include_once('../modals/add-repair-modal.php') ?>
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

    <script>
    $(document).ready(function() {
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
    </script>



    <script>
    const form = document.querySelector('.form-sample');
    const fname = form.querySelector('input[name="fname"]');
    const lname = form.querySelector('input[name="lname"]');
    const phone = form.querySelector('input[name="phone"]');
    const address = form.querySelector('input[name="address"]');
    const etype = form.querySelector('#etype');
    const ebrand = form.querySelector('#ebrand');
    const other_brand = form.querySelector('#other_brand');
    const defective = form.querySelector('#defective');
    const other_defective = form.querySelector('#other_defective');
    const shipping = form.querySelector('[name="shipping"]');

    etype.addEventListener('change', () => {
        if (etype.value !== 'None') {
            etype.nextElementSibling.innerText = '';
        }
    });

    ebrand.addEventListener('change', () => {
        if (ebrand.value === 'other') {
            document.getElementById('other-brand-input').style.display = 'block';
        } else {
            document.getElementById('other-brand-input').style.display = 'none';
            other_brand.value = '';
        }
    });

    defective.addEventListener('change', () => {
        if (defective.value === 'other') {
            document.getElementById('other-defect-input').style.display = 'block';
        } else {
            document.getElementById('other-defect-input').style.display = 'none';
            other_defective.value = '';
        }
    });


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

        if (etype.value === 'None') {
            etype.nextElementSibling.innerText = 'Please select electronic type';
            error = true;
        } else {
            etype.nextElementSibling.innerText = '';
        }

        if (ebrand.value === 'None') {
            ebrand.nextElementSibling.innerText = 'Please select a brand';
            error = true;
        } else if (ebrand.value === 'other' && other_brand.value.trim() === '') {
            ebrand.nextElementSibling.innerText = 'Please enter other brand name';
            error = true;
        } else {
            ebrand.nextElementSibling.innerText = '';
        }

        if (defective.value === 'None') {
            defective.nextElementSibling.innerText = 'Please select a defect';
            error = true;
        } else if (defective.value === 'other' && other_defective.value.trim() === '') {
            defective.nextElementSibling.innerText = 'Please enter other defect description';
            error = true;
        } else {
            defective.nextElementSibling.innerText = '';
        }

        if (shipping.value === 'None') {
            shipping.nextElementSibling.innerText = 'Please select a shipping option';
            error = true;
        } else {
            shipping.nextElementSibling.innerText = '';
        }

        if (error) {
            event.preventDefault(); // Prevent form submission if there are errors
        } else {
            // Submit form to server if there are no errors
            // You can use AJAX to submit the form asynchronously, or just let it submit normally
        }
    });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2({});
    });
    </script>

    <script>
    j(document).ready(function() {
        j('#myDataTable').DataTable();
    });

    j(document).ready(function() {
        j('#myDataTable2').DataTable();
    });
    </script>

    <script>
    $(document).ready(function() {
        $('#etype').change(function() {
            var etype_id = $(this).val();

            if (etype_id === "None") {
                $('#defective').html('<option value="None">--- Select ---</option>');
            } else {
                $.ajax({
                    url: 'fetch_defects.php',
                    type: 'POST',
                    data: {
                        etype_id: etype_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        var options = '<option value="None">--- Select ---</option>' +
                            '<option value="other">Other</option>';
                        for (var i = 0; i < data.length; i++) {
                            options += '<option value="' + data[i].defect_id + '">' + data[
                                    i]
                                .defect_name + '</option>';

                        }
                        $('#defective').html(options);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            }
        });

        $('#etype').change(function() {
            var etype_id = $(this).val();

            if (etype_id === "None") {
                $('#ebrand').html('<option value="None">--- Select ---</option>');
            } else {
                $.ajax({
                    url: 'fetch_brands.php',
                    type: 'POST',
                    data: {
                        etype_id: etype_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        var options = '<option value="None">--- Select ---</option>' +
                            '<option value="other">Other</option>';
                        for (var i = 0; i < data.length; i++) {
                            options += '<option value="' + data[i].eb_id + '">' + data[i]
                                .eb_name + '</option>';
                        }
                        console.log(data)
                        $('#ebrand').html(options);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            }
        });
    });
    </script>
</body>

</html>