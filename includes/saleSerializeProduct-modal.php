<!-- Start Serialize Product Modal -->
<div class="modal fade" id="serialNumsModal">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 30%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Add Serialize Product</span> </b></h4>
                <div id='divErrorMsgShow' class='alert alert-danger alert-dismissible errorMessage'></div>
            </div>
            <div class="modal-body card-body">
                <form id="serializeProductForm">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <table border="1" style="font-size: 13px; width:100%;" class="table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL.</th>
                                        <th>SL. Number</th>
                                        <th>Remaining Qty</th>
                                        <th style="width: 25%">Sale Qty</th>
                                    </tr>
                                </thead>
                                <input type="hidden" id="serializProductId" name="serializProductId" value="">
                                <input type="hidden" id="serializProductWarehouseId" name="serializProductWarehouseId" value="">
                                <input type="hidden" id="checkSesssinStatus">
                                <tbody id="serializeProductTable" class="text-center">
                                </tbody>
                            </table>
                            <strong>Total Sale Quantity: <span name="totalStockQuantity" id="totalStockQuantity"></span></strong><br><span class="text-danger">** Sale
                                Qty & Total Qty Must Be Same</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x
                            Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Serialize Product Modal -->