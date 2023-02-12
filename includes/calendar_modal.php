
<!-- Edit -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Update Calendar Events</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="calendar-edit.php">
            		<input type="hidden" id="calenderid" name="id">
                <div class="form-group">
                    <label for="edit_day_type" class="col-sm-3 control-label">Day Type</label>

                    <div class="col-sm-9">
                    	<select class="form-control"  id="edit_day_type" name="edit_day_type" required>
						<option value="" selected>- Select One -</option>
						<?php
						  $sql = "SELECT id,day_type FROM `calender_tbl` GROUP BY day_type";
						  $query = $conn->query($sql);
						  while($prow = $query->fetch_assoc()){
							echo "
							  <option value='".$prow['day_type']."'>".$prow['day_type']."</option>
							";
						  }
						?>
					  </select>
                  	</div>
                </div>
				<div class="form-group">
                    <label for="edit_offday_cause" class="col-sm-3 control-label">Cause</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="edit_offday_cause" name="edit_offday_cause" placeholder="Device Name">
                    </div>
                </div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Update</button>
            	</form>
          	</div>
        </div>
    </div>
</div>

<!-- Edit for Onday calendar -->
<div class="modal fade" id="ondayedit">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Onday Calendar Events</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="calendar-edit.php">
            		<input type="hidden" id="ondayid" name="id">
                <div class="form-group">
                    <label for="edit_onday_type" class="col-sm-3 control-label">Day Type</label>

                    <div class="col-sm-9">
                    	<select class="form-control"  id="edit_onday_type" name="edit_day_type" required>
						<option value="" selected>- Select One -</option>
						<?php
						  $sql = "SELECT id,day_type FROM `calender_tbl` GROUP BY day_type";
						  $query = $conn->query($sql);
						  while($prow = $query->fetch_assoc()){
							echo "
							  <option value='".$prow['day_type']."'>".$prow['day_type']."</option>
							";
						  }
						?>
					  </select>
                  	</div>
                </div>
				<div class="form-group">
                    <label for="edit_on_offday_cause" class="col-sm-3 control-label">Cause</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="edit_on_offday_cause" name="edit_offday_cause" placeholder="Device Name">
                    </div>
                </div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-success btn-flat" name="onday_edit"><i class="fa fa-check-square-o"></i> Update</button>
            	</form>
          	</div>
        </div>
    </div>
</div>
     
<!-- Edit for Offday calendar -->
<div class="modal fade" id="offdayedit">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Offday Update Calendar Events</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="calendar-edit.php">
            		<input type="hidden" id="offdayid" name="id">
                <div class="form-group">
                    <label for="edit_offday_type" class="col-sm-3 control-label">Day Type</label>

                    <div class="col-sm-9">
                    	<select class="form-control"  id="edit_offday_type" name="edit_day_type" required>
						<option value="" selected>- Select One -</option>
						<?php
						  $sql = "SELECT id,day_type FROM `calender_tbl` GROUP BY day_type";
						  $query = $conn->query($sql);
						  while($prow = $query->fetch_assoc()){
							echo "
							  <option value='".$prow['day_type']."'>".$prow['day_type']."</option>
							";
						  }
						?>
					  </select>
                  	</div>
                </div>
				<div class="form-group">
                    <label for="edit_off_offday_cause" class="col-sm-3 control-label">Cause</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="edit_off_offday_cause" name="edit_offday_cause" placeholder="Device Name">
                    </div>
                </div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-success btn-flat" name="offdayedit"><i class="fa fa-check-square-o"></i> Update</button>
            	</form>
          	</div>
        </div>
    </div>
</div>

<!-- Edit for holiday calendar -->
<div class="modal fade" id="holidayedit">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Holiday Update Calendar Events</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="calendar-edit.php">
            		<input type="hidden" id="holidayid" name="id">
                <div class="form-group">
                    <label for="edit_holi_day_type" class="col-sm-3 control-label">Day Type</label>

                    <div class="col-sm-9">
                    	<select class="form-control"  id="edit_holi_day_type" name="edit_day_type" required>
						<option value="" selected>- Select One -</option>
						<?php
						  $sql = "SELECT id,day_type FROM `calender_tbl` GROUP BY day_type";
						  $query = $conn->query($sql);
						  while($prow = $query->fetch_assoc()){
							echo "
							  <option value='".$prow['day_type']."'>".$prow['day_type']."</option>
							";
						  }
						?>
					  </select>
                  	</div>
                </div>
				<div class="form-group">
                    <label for="edit_holi_offday_cause" class="col-sm-3 control-label">Cause</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="edit_holi_offday_cause" name="edit_offday_cause" placeholder="Device Name">
                    </div>
                </div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-success btn-flat" name="holidayedit"><i class="fa fa-check-square-o"></i> Update</button>
            	</form>
          	</div>
        </div>
    </div>
</div>




     