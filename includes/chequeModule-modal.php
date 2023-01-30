<!-- Add Cheque Entry-->
<div class="modal fade" id="entryNewCheque">
    <div class="modal-dialog" style="width: 55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Cheque Entry</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form_addCheque" method="POST" action="#" enctype="multipart/form-data">
                    <div class="form-group">
                        
                        <div class="col-sm-6">
                            <label for="receivingDate">Cheque Receiving Date</label> 
                            <input type="date" class="form-control" id="receivingDate" name="receivingDate" placeholder="Enter Receiving Date ">
                        </div>
                        <div class="col-sm-6">
                            <label for="paymentFrom">Payment From [party]</label> 
                            <select class="form-control" name="paymentFrom" id="paymentFrom" >
                                <option value="" selected>~~ Select Brands ~~</option>
                                <?php
                                $sql = "SELECT id,brandName FROM `tbl_brands` WHERE status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['brandName'] . "</option>
									";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                    <div class="col-sm-6">
                            <label for="bankName ">Bank Name</label> 
                            <select class="form-control" name="bankName" id="bankName" >
                                <option value="" selected>~~ Select Brands ~~</option>
                                <?php
                                $sql = "SELECT id,brandName FROM `tbl_brands` WHERE status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['brandName'] . "</option>
									";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="branchName">Branch Name</label> 
                            <select class="form-control" name="branchName" id="branchName" >
                                <option value="" selected>~~ Select Branch ~~</option>
                                <?php
                                $sql = "SELECT id,brandName FROM `tbl_brands` WHERE status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['brandName'] . "</option>
									";
                                }
                                ?>
                            </select>
                        </div>
                            </div>
                    <div class="form-group">
                   
                    <div class="col-sm-6">
                            <label for="chequeType">Cheque Type</label> 
                            <select class="form-control" name="chequeType" id="chequeType">
                                <option value="" selected>~~ Select Type ~~</option>
							    <option value='Account pay'>Account pay</option>
                                <option value='Account pay'>Cash Cheque</option>		
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="payTo">Pay To</label>  
                            <input type="text" class="form-control" id="payTo" name="payTo" placeholder="Pay To">
                         </div>
                        </div>
                        <div class="form-group">
                         
                         <div class="col-sm-6">
                            <label for="depositeAccount">Deposite Account</label> 
                            <select class="form-control" name="depositeAccount" id="depositeAccount">
                                <option value="" selected>~~ Select Unit ~~</option>
                                <?php
                                $sql = "SELECT id,unitName FROM `tbl_units` WHERE status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['unitName'] . "</option>
									";
                                }
                                ?>
                             </select>
                         </div>
                         	
						 <div class="col-sm-6">
                            <label for="chequeNo">Cheque No</label> 
                            <input type="text" class="form-control" id="chequeNo" value='0' autocomplete="off" name="chequeNo" placeholder="Enter Cheque No">
                         </div>
                        </div>
                        <div class="form-group">
                        
                        <div class="col-sm-6">
                            <label for="chequeDate">Cheque Date</label> 
                            <input type="date" class="form-control" id="chequeDate" name="chequeDate" placeholder="Enter Cheque Date">
                        </div>
                        <div class="col-sm-6">
                            <label for="amount">Amount</label> 
                            <input type="text" class="form-control" id="amount" value='0'  name="amount" placeholder="Enter Amount">
                        </div>
                    </div> 
                    <div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-success btn-flat" name="addItem" id="btn_saveItem"><i class="fa fa-save"></i> Save </button>
                    </div>
                    </form>
				  
				</div>
			</div>
        </div>
    </div>



 


<!-- Add Cheque Entry-->
<div class="modal fade" id="chequePlacement">
    <div class="modal-dialog" style="width: 45%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Cheque Placement</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form_addCheque" method="POST" action="#" enctype="multipart/form-data">
                <div class="form-group">
                         
                <div class="col-md-4">
                           Cheque No
                           </div>
                           <div class="col-md-8">
                           Something.......
                           </div>
            
                           
                           <div class="col-md-4">
                            Party Name
                            </div>
                            <div class="col-md-8">
                            Something.......
                            </div>
                    
                       
                            <div class="col-md-4">
                            Receiving Date
                            </div>
                            <div class="col-md-8">
                            Something.......
                            </div>
                      
                       
                            <div class="col-md-4">
                            Cheque date
                            </div>
                            <div class="col-md-8">
                            Something.......
                            </div>
                         	
						
                         </div>
                <div class="form-group">
                        
                        <div class="col-sm-12">
                        <label for="receivingDate">Placement date</label> 
                            <input type="date" class="form-control" id="receivingDate" name="receivingDate" placeholder="Enter Receiving Date ">
                            </div>
                   
                    </div>
                    <div class="form-group">
               
                        <div class="col-sm-12">
                        <label for="chequeType">Cheque Status</label> 
                            <select class="form-control" name="chequeType" id="chequeType">
                                <option value="" selected>~~ Select Status ~~</option>
							    <option value='Account pay'>Bounce</option>
                                <option value='Account pay'>Passed</option>		
                            </select>
                        </div>
                            </div>
                    <div class="form-group">
                   
                    
                        <div class="col-sm-12">
                        <label for="chequeType">Cheque Status</label> 
                            <select class="form-control" name="chequeType" id="chequeType">
                                <option value="" selected>~~ Select Status ~~</option>
							    <option value='Account pay'>Bounce</option>
                                <option value='Account pay'>Passed</option>		
                            </select>
                          </div>
                        </div>
                        
                        
                    <div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-success btn-flat" name="addItem" id="btn_saveItem"><i class="fa fa-save"></i> Save </button>
                    </div>
                    </form>
				  
				</div>
			</div>
        </div>
    </div>




    


<!-- Add Event Entry-->
<div class="modal fade" id="eventEntry">
    <div class="modal-dialog" style="width: 45%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Event Entry</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form_addCheque" method="POST" action="#" enctype="multipart/form-data">
                        
                


                         
                <div class="form-group">
                        <div class="col-sm-12">
                            <label for="eventDateFrom">Event Date From</label> 
                            <input type="date" class="form-control" id="eventDateFrom" name="eventDateFrom" placeholder="Event Date From">
                        </div>
                    </div>

                    <div class="form-group">
               
                        <div class="col-sm-12">
                        <label for="eventDateTo">Event Date To</label> 
                            <input type="date" class="form-control" id="eventDateTo" name="eventDateTo" placeholder="Event Date To ">
                        </div>
                            </div>


                            
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="eventTitle">Event Title</label>  
                            <input type="text" class="form-control" id="eventTitle" name="eventTitle" placeholder="Event Title">
                        </div>
                    </div>

                    <div class="form-group">
                   
                    
                        <div class="col-sm-12">
                        <label for="chequeType">Entry Type</label> 
                            <select class="form-control" name="chequeType" id="chequeType">
                                <option value="" selected>~~ Select Status ~~</option>
							    <option value='Account pay'>Bounce</option>
                                <option value='Account pay'>Passed</option>		
                            </select>
                          </div>
                        </div>
                        
                        
                    <div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-success btn-flat" name="eventEntry" id="btn_eventEntry"><i class="fa fa-save"></i> Save </button>
                    </div>
                    </form>
				  
				</div>
			</div>
        </div>
    </div>








    
<!-- Add Expense Head-->
<div class="modal fade" id="expenseHead">
    <div class="modal-dialog" style="width: 45%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Expense Head</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form_addCheque" method="POST" action="#" enctype="multipart/form-data">
                        
            

                            
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="expenseHead">Expense Head</label>  
                            <input type="text" class="form-control" id="expenseHead" name="expenseHead" placeholder="Expense Head">
                        </div>
                    </div>

                    <div class="form-group">
                   
                    
                        <div class="col-sm-12">
                        <label for="expenseType">Expense Type</label> 
                            <select class="form-control" name="expenseType" id="expenseType">
                                <option value="" selected>~~ Select Status ~~</option>
							    <option value='Account pay'>Direct Expense</option>
                                <option value='Account pay'>Indirect Expense</option>		
                            </select>
                          </div>
                        </div>
                        
                        
                    <div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-success btn-flat" name="expenseHead" id="btn_expenseHead"><i class="fa fa-save"></i> Save </button>
                    </div>
                    </form>
				  
				</div>
			</div>
        </div>
    </div>




    
    
<!-- Add Expense Form-->
<div class="modal fade" id="expenseForm">
    <div class="modal-dialog" style="width: 45%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Expense Form</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form_addCheque" method="POST" action="#" enctype="multipart/form-data">
                        
                <div class="form-group">
        
                   <div class="col-sm-6">
                        <label for="paymentMethod">Payment Method</label> 
                           <select class="form-control" name="paymentMethod" id="paymentMethod">
                               <option value="" selected>~~ Select Status ~~</option>
                               <option value='Account pay'>Direct Expense</option>
                               <option value='Account pay'>Indirect Expense</option>		
                       </select>
                     </div>
                     <div class="col-sm-6">
                            <label for="availableAmount">Available Amount</label>  
                            <input type="text" class="form-control" id="availableAmount" name="availableAmount" placeholder="Expense Head" disabled>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <div class="col-sm-6">
                        <label for="expenseHead">Expense Head</label> 
                            <select class="form-control" name="expenseHead" id="expenseHead">
                                <option value="" selected>~~ Select Status ~~</option>
							    <option value='Account pay'>Direct Expense</option>
                                <option value='Account pay'>Indirect Expense</option>		
                            </select>
                          </div>
                          <div class="col-sm-6">
                            <label for="amount">Amount</label>  
                            <input type="text" class="form-control" id="Amount" name="Amount" placeholder="Enter Amount">
                        </div>
                    </div>
                  
                    <div class="form-group">
                        <div class="col-sm-12">
                        <label for="remark" class="form-label">Remark</label>
                        <textarea class="form-control" id="remark" rows="3"></textarea>
                        </div>
                    </div>
                        
                    <div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-success btn-flat" name="expenseHead" id="btn_expenseHead"><i class="fa fa-save"></i> Save </button>
                    </div>
                    </form>
				  
				</div>
			</div>
        </div>
    </div>


