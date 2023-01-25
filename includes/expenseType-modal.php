<!-- modal -->
<div class="modal fade" id="addExpenseType">
    <div class="modal-dialog" style="width:35%;">
        <div class="modal-content">
            <div class="modal-header float-left">
                <h4 class="modal-title float-left"> Add ExpenseType</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div> 
            <div class="modal-body">
                <form id="expenseTypeForm" method="POST" enctype="multipart/form-data" action="#">

                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label> ExpenseType Name <span class="text-danger"> * </span></label>
                        <input class="form-control input-sm" id="name" type="text" name="name" placeholder=" Write Expense Type" >
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

   