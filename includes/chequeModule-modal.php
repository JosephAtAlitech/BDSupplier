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
                
                <div class="col-sm-6 ">
                            <label for="partyType">Party Type</label> 
                            
                            <select class="form-control"  onchange="loadParty(this.value)" name="partyType" id="partyType">
							    <option selected>Select Type </option>
							    <option value='Customers'>Customers</option>
                                <option value='WICustomer'>WICustomer</option>		
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="partyId">Payment From [party]</label> 
                            <select class="form-control" name="partyId" id="partyId">
                              
                            </select>
                        </div>
                        </div>
                <div class="form-group">
                        <div class="col-sm-6">
                            <label for="chequeReceivingDate">Cheque Receiving Date</label> 
                            <input type="date" class="form-control" id="chequeReceivingDate" name="chequeReceivingDate" placeholder="Enter Cheque Receiving Date" value="<?php echo date('Y-m-d');?>">
                        </div>
                        <div class="col-sm-6 ">
                            <label for="voucherType">Voucher Type</label> 
                            
                            <select class="form-control"   name="voucherType" id="voucherType">
							    <option value='paymentVoucher'>Payment</option>
                                <option value='paymentReceivedVoucher'>Payment Received</option>		
                            </select>
                        </div>
                        
                    </div>
                    <div class="form-group">
                    <div class="col-sm-6">
                            <label for="bankId ">Bank Name</label> 
                            <select class="form-control" name="bankId" id="bankId" >
                             
                                <?php
                                $sql = "SELECT id, bank_name FROM `tbl_bank` WHERE deleted='No' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['bank_name'] . "</option>
									";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="branchId">Branch Name</label> 
                            <select class="form-control" name="branchId" id="branchId" >
                              
                                <?php
                                $sql = "SELECT id,branch_name FROM `tbl_bank_branch` WHERE deleted='No' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['branch_name'] . "</option>
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
                                <?php
                                $sql = "SELECT id,accountNo,accountName FROM `tbl_bank_account_info` WHERE status='Active' AND deleted='No' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['accountNo'] . " - " . $prow['accountName'] . "</option>
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
                            <input type="date" value="<?php echo date('Y-m-d');?>" class="form-control" id="chequeDate" name="chequeDate" placeholder="Enter Cheque Date">
                        </div>
                        <div class="col-sm-6">
                            <label for="amount">Amount</label> 
                            <input type="text" class="form-control" id="amount" value='0'  name="amount" placeholder="Enter Amount">
                        </div>
                    </div> 
                    <div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-success btn-flat" name="form_addCheque" id="form_addCheque"><i class="fa fa-save"></i> Save </button>
                    </div>
                    </form>
				  
				</div>
			</div>
        </div>
    </div>




<!-- Add Cheque Placement-->
<div class="modal fade" id="chequePlacement">
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Cheque Placement</b></h4>
            </div>
            <div class="modal-body">
            
            <div class="row">
            <form class="form-horizontal" id="form_addChequePlacement" method="POST" action="#" enctype="multipart/form-data">
                <input disabled type="hidden" value="" class="form-control col-sm-6" id="chequeId" name="chequeId" >
                <input disabled type="hidden" value="" class="form-control col-sm-6" id="amount" name="amount" >
                <input disabled type="hidden" value="" class="form-control col-sm-6" id="bankName" name="bankName" >
                <input disabled type="hidden" value="" class="form-control col-sm-6" id="tbl_partyId" name="tbl_partyId" >
                <div class="col-sm-6">
                <div class="form-group">
            
            <div class="col-sm-12">
               <label for="place_chequeNo" class="">Cheque No</label> 
               <input disabled type="text" class="form-control col-sm-6" id="place_chequeNo" name="place_chequeNo" >
           </div>
            <div class="col-sm-12">
               <label for="place_partyName" class="">Party Name</label> 
               <input disabled type="text" value="" class="form-control col-sm-6" id="place_partyName" name="place_partyName" >
           </div>
            <div class="col-sm-6">
               <label for="place_receivingDate" class="">Receiving Date</label> 
               <input disabled type="text" class="form-control col-sm-6" id="place_receivingDate" name="place_receivingDate" >
            </div>
            <div class="col-sm-6">
               <label for="place_chequeDate" class="">Cheque Date</label> 
               <input disabled type="text" class="form-control col-sm-6" id="place_chequeDate" name="place_chequeDate">
            </div>
   
   </div>
   <hr>
   
       <div class="form-group">
           <div class="col-sm-6">
               <label for="placementDate">Placement date</label> 
               <input type="date" class="form-control" id="placementDate" name="placementDate" placeholder="Enter Placement Date" value="<?php echo date('Y-m-d');?>">
           </div>
    
           <div class="col-sm-6">
             <label for="chequeStatus">Cheque Status</label> 
               <select class="form-control" name="chequeStatus" id="chequeStatus">
                   <option value="" selected>~~ Select Status ~~</option>
                   <option value='Bounce'>Bounce</option>
                   <option value='Clear'>Passed</option>
                   <option value='Pending'>Running</option>				
               </select>
            </div>
           </div>
        <div class="form-group">
            <div class="col-sm-6">
                <label for="bounceAndClearanceDate">Bounce/ Clearance Date</label> 
                <input type="date" class="form-control" id="bounceAndClearanceDate" name="bounceAndClearanceDate" placeholder="Bounce/ Clearance Date" value="<?php echo date('Y-m-d');?>">
            </div>
        </div>
        <div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn  btnattr btn-success btn-flat" name="addItem" id="btn_saveItem"><i class="fa fa-save"></i> Save </button>
                    </div>
                    </form>
                </div>
                <div class="col-sm-6">
                <table id="managePlacementedTable" class="table table-bordered" style="width:100%;">
                            <thead>
                                <th width="4%">SN</th>
                                <th>Placement Date</th>
                                <th>Clearance Date</th>
                                <th>Status</th>
                                <th width="4%">Action</th>
                            </thead>
                            <tbody id="PlacementedTableBody">
                            </tbody>
                    </table>
                 </div>
             </div>
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


