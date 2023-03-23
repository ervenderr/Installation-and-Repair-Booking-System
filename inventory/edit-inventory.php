<?php
session_start();

require_once '../homeIncludes/dbconfig.php';


if(isset($_POST['inv_id'])){
    $output = '';
    $_SESSION['inv_id'] = $_POST['inv_id'];
    $query = "SELECT * FROM inventory WHERE inv_id = '".$_POST['inv_id']."'";
    $result = mysqli_query($conn, $query);
    $inventory = mysqli_fetch_assoc($result); // Fetch the data from the result set

    $output .= '
    <form method="POST" action="edit-inventory.php" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="supplierSelect" class="form-label">Supplier</label>
            <select class="form-select" id="supplierSelect" name="supplierSelect">
                <option value="None">--- select ---</option>';

    // Query the supplier table
    $sql = "SELECT * FROM supplier";
    $supplier_result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($supplier_result) > 0) {
        while($row = mysqli_fetch_assoc($supplier_result)) {
        $_SESSION['products_id'] = $inventory['product_id'];
        
          $selected = '';
          if ($row["supplier_id"] == $inventory['supplier_id']) {
            $selected = 'selected';
          }
          $output .= "<option value='" . $row["supplier_id"] . "' $selected>" . $row['fname'] . '  ' . $row['lname'] . "</option>";
        }
    }

    $output .= '
            </select>
          </div>
          <div class="mb-3">
            <label for="quantityInput" class="form-label">Stock-in</label>
            <input type="number" class="form-control" id="quantityInput" name="quantityInput" value="' . $inventory['stock_in'] . '">
          </div>
          <div class="mb-3">
            <label for="stockout" class="form-label">Stock-out</label>
            <input type="number" class="form-control" id="stockout" name="stockout" value="' . $inventory['stockout'] . '">
          </div>
          <div class="mb-3">
            <label for="stockInDateInput" class="form-label">Stock-out Date</label>
            <input type="date" class="form-control" id="stockInDateInput" name="stockInDateInput" value="' . $inventory['stock_in_date'] . '">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <input name="submit" type="submit" class="btn btn-success" value="Save"/>
          </div>
    </form>';

    echo $output;
}

if(isset($_POST['submit'])) {
    $productId = htmlentities($_SESSION['rowid']);
    $invId = htmlentities($_SESSION['inv_id']);
    $supplierId = htmlentities($_POST['supplierSelect']);
    $quantityInput = htmlentities($_POST['quantityInput']);
    $stockout = htmlentities($_POST['stockout']);
    $stockInDateInput = htmlentities($_POST['stockInDateInput']);

    $query = "UPDATE inventory SET 
              product_id = '$productId', 
              supplier_id = '$supplierId', 
              stock_in = '$quantityInput',
              stockout = '$stockout',
              stock_in_date = '$stockInDateInput'
          WHERE inv_id = '$invId'";

    $result = mysqli_query($conn, $query);
    

    if ($result) {
        header("location: view-inventory.php?msg=Record updated Successfully.&prod_id=" . $productId);
    } else {
       echo "FAILED: " . mysqli_error($conn);
    }
}

?>
