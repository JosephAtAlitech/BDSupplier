<!-- modal -->
<div class="modal fade" id="addExpense-modal">
    <div class="modal-dialog" style="width:45%;">
        <div class="modal-content">
            <div class="modal-header float-left">
                <h4 class="modal-title float-left"> Add Expense</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div> 
            <div class="modal-body">
                    <form class="form-horizontal" id="form_addExpenses" method="POST" action="#" enctype="multipart/form-data">
                        <div class="form-group row">
                        <div class="col-md-4">
                            
                                <label for="ItemName">Expanse Date</label> 
                                <input type="Date" class="form-control" id="expenseDate" name="expenseDate" placeholder="Enter Expense Date">
                            </div>
                            <div class="col-md-8">
                                <label for="productCode">Expense Cause</label> 
                                <input type="text" class="form-control" id="expenseCause" autocomplete="off" name="expenseCause" placeholder="Enter Expense Cause" />
                            </div>
                
                        </div>
                        <div class="form-group row">
                           
                            <div class="col-sm-6">
                            <label for="expenseType">Expense Type</label> 
                            <select class="form-control" name="expenseType" id="expenseType">
                                <?php
                                $sql = "SELECT id,name FROM `expense_types` WHERE status='Active' AND deleted='No'  ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($row = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $row['id'] . "'>" . $row['name'] . "</option>
									";
                                }
                                ?>
                            </select>
                            </div>
                        
                            <div class="col-sm-6">
                                <label for="expenseBy">Expense By</label> 
                                <select class="form-control" name="expenseBy" id="expenseBy" >
                                    <?php
                                    $sql = "SELECT `id`,`fname`,`lname` FROM `tbl_users` WHERE accountStatus='approved' AND deleted='No' ORDER BY `id`  DESC";
                                    $query = $conn->query($sql);
                                    while ($row = $query->fetch_assoc()) {
                                        echo "
                                        <option value='" . $row['id'] . "'>" . $row['fname'] ." ".$row['lname'] . "</option>
                                        ";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="minPrice">Amount</label> 
                                <input type="Number" class="form-control" id="amount" name="amount" onblur="MinimumNValidate()" placeholder="Enter Amount">
                            </div>
                            <div class="col-sm-6">
                                <label for="status">Status</label> 
                                <select id="status" name="status" class="form-control input-sm">
                                    <option value="No"> Active </option>
                                    <option value="Yes"> Inactive</option>
                                </select>
                            </div>
                        </div>
                        
            
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="submit" class="btn btn-success btn-flat" name="addItem" id="btn_saveExpense"><i class="fa fa-save"></i> Save </button>
                    </form>
				</div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- modal -->
<div class="modal fade" id="editExpenseType">
    <div class="modal-dialog" style="width:35%;">
        <div class="modal-content">
            <div class="modal-header float-left">
                <h4 class="modal-title float-left"> Edit Expense Type</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div> 
            <div class="modal-body">
                <form id="expenseTypeFormUpdate" method="POST" enctype="multipart/form-data" action="#">

                    <input type="hidden" id="editExpenseTypeId" name="id">
                  
                    <div class="form-group">
                        <label> ExpenseType Name <span class="text-danger"> * </span></label>
                        <input class="form-control input-sm" id="editExpenseTypeName" type="text" name="name" placeholder="Edit Expense Type" >
                        <span class="text-danger" id="nameError"></span>
                    </div>
                    <div class="form-group">
                        <label> Status <span class="text-danger"> * </span></label>
                        <select class="form-control input-sm" id="editExpenseTypeStatus" name="status">
                                    <option value="Active" >Active</option>
                                    <option value="Inactive" >Inactive</option>
                        </select>
                        <span class="text-danger" id="nameError"></span>
                    </div>
                    <div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-success btn-flat" name="addItem" id="btn_saveItem"><i class="fa fa-save"></i> Save </button>
				</form>
				</div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

   