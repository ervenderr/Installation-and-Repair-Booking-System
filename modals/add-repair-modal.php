<!-- Add service Transaction - The modal -->
<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTransactionModalLabel">Add New Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form-sample" action="add-transaction.php" method="POST" enctype="multipart/form-data">
                    <p class="card-description"> Personal info </p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-form-label" for="fname">First Name</label>
                                <div class="">
                                    <input type="text" name="fname" class="form-control" placeholder="ex. Erven" />
                                    <span class="error-input"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-form-label" for="lname">Last Name</label>
                                <div class="">
                                    <input type="text" name="lname" class="form-control" placeholder="ex. Idjad" />
                                    <span class="error-input"></span>
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
                                        placeholder="ex. ervenidjad12@gmail.com">
                                    <span class="error-input"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="phone" class="col-form-label">Phone</label>
                                <div class="">
                                    <input name="phone" class="form-control" type="tel" placeholder="ex. 09123456789" />
                                    <span class="error-input"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="address" class="col-form-label">Address</label>
                                <div class="">
                                    <input name="address" class="form-control" type="text"
                                        placeholder="ex. Recodo, Zamboanga City">
                                    <span class="error-input"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="etype" class="col-form-label">Electronic Type</label>
                                <div class="">
                                    <select name="etype" class="form-control">
                                        <option value="None">--- Select ---</option>
                                        <?php
                                        $sql = "SELECT * FROM electronics";
                                        $result = mysqli_query($conn, $sql);
                                        while($row = mysqli_fetch_assoc($result)) { 
                                            echo "<option value='" . $row['elec_id'] . "'>" . $row['elec_name'] . "</option>";
                                        }
                                    ?>
                                    </select>
                                    <span class="error-input"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="technician" class="col-form-label">Assigned Electrician</label>
                                <div class="">
                                    <select name="technician" class="form-control">
                                        <option value="None">--- Select ---</option>
                                        <?php
                                        $sql = "SELECT * FROM technician";
                                        $result = mysqli_query($conn, $sql);
                                        while($row = mysqli_fetch_assoc($result)) { 
                                            echo "<option value='" . $row['tech_id'] . "'>" . $row['fname'] ." ". $row['lname'] . "</option>";
                                        }
                                    ?>
                                    </select>
                                    <span class="error-input"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="defective" class="col-form-label">Defects</label>
                                <div class="">
                                    <select name="defective" id="defective" class="form-control">
                                        <option value="None">--- Select ---</option>
                                        <?php
                                            $sql = "SELECT * FROM defects";
                                            $result = mysqli_query($conn, $sql);
                                            while($row = mysqli_fetch_assoc($result)) { 
                                                echo "<option value='" . $row['defect_id'] . "'>" . $row['defect_name'] . "</option>";
                                            }
                                            ?>
                                        <option value="other">Other</option>
                                    </select>
                                    <span class="error-input"></span>
                                </div>
                                <div class="form-group" id="other-defect-input" style="display:none;">
                                    <label for="other_defective" class="col-form-label">Other Defect</label>
                                    <input type="text" name="other_defective" id="other_defective" class="form-control">
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="shipping" class="col-form-label">Shipping option</label>
                                <div class="">
                                    <select name="shipping" class="form-control">
                                        <option value="None">--Select--</option>
                                        <option value="Pickup">Pickup</option>
                                        <option value="Deliver">Deliver</option>
                                        <option value="Home Service">Home Service</option>
                                    </select>
                                    <span class="error-input"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="date" class="col-form-label">Date</label>
                                <div class="">
                                    <input name="date" type="date" class="form-control" placeholder="dd/mm/yyyy" />
                                    <span class="error-input"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="completed" class="col-form-label">Estimated Completion</label>
                                <div class="">
                                    <input name="completed" type="date" class="form-control" placeholder="dd/mm/yyyy" />
                                    <span class="error-input"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="inipayment" class="col-form-label">Initial Payment</label>
                                <div class="">
                                    <input name="inipayment" class="form-control" type="text" />
                                    <span class="error-input"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input name="submit" type="submit" class="btn btn-primary" value="Add Transaction" />

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    document.getElementById('defective').addEventListener('change', function() {
        if (this.value === 'other') {
            document.getElementById('other-defect-input').style.display = 'block';
        } else {
            document.getElementById('other-defect-input').style.display = 'none';
        }
    });
</script>
