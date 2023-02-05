var manageEntryChequeTable;


// retrive entry cheque data

$(document).ready(function() {
	manageEntryChequeTable = $("#manageEntryChequeTable").DataTable({
		'ajax': 'phpScripts/manageChequeModule.php',
		'order': [],
		'dom': 'Bfrtip',
        'buttons': [
            'pageLength','copy', 'csv', 'pdf', 'print'
        ],
		language: {
            processing: "<img src='../images/loader.gif'>"
        },

	});
});

function loadParty(partyType){
		var fd = new FormData();
		fd.append('partyType',partyType);
		fd.append('loadParty',"loadParty");
		$.ajax({
		type: 'POST',
		url: 'phpScripts/manageChequeModule.php',
		data: fd,
		dataType:"json",
		contentType: false,
		processData: false,
    		success:function(data)
    		{
				$("#partyId").html('');
				for(var i=0; i<data.length; i++){
					$("#partyId").append("<option value='"+data[i].id+"'>"+data[i].partyName+"</option>")
				}
    		},error: function (xhr) {
			alert(xhr.responseText);
		}
	  });
}
//Delete Expense Type
function deleteEntryCheque(id){
	var conMsg = confirm("Are you sure to delete??")
	if(conMsg){
		var Id = id;
		var fd = new FormData();
		fd.append('Id',Id);
		fd.append('action_delete',"deleteCheque");
		$.ajax({
		type: 'POST',
		url: 'phpScripts/manageChequeModule.php',
		data: fd,
		contentType: false,
		dataType : 'json',
		processData: false,
    		success:function(response)
    		{
				if(response.status == "Success"){
					manageEntryChequeTable.ajax.reload(null, false);
					$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Deleted");
				    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
					  $(this).hide(); n();
					});
				}
    		},error: function (xhr) {
			alert(xhr.responseText);
		}
	  });
	}
}

function deletePlacement(id, chequeId, chequeNo){
	var conMsg = confirm("Are you sure to delete??")
	if(conMsg){
		var Id = id;
		var chequeId = chequeId;
		var chequeNo = chequeNo;

		//alert(chequeNo)
		//var chequeStatus = chequeStatus;
		var fd = new FormData();
		fd.append('id',Id);
		fd.append('chequeId',chequeId);
		fd.append('chequeNo',chequeNo);
		//fd.append('chequeStatus',chequeStatus);
		fd.append('deletePlacement',"deletePlacement");
		$.ajax({
		type: 'POST',
		url: 'phpScripts/manageChequeModule.php',
		data: fd,
		contentType: false,
		processData: false,
		
    		success:function(response)
    		{
					$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
				    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
					  $(this).hide(); n();
					});
				 	$("#placementDate").removeAttr("disabled");
				    $("#chequeStatus").removeAttr("disabled");
				    $("#bounceAndClearanceDate").removeAttr("disabled");
					$(".btnattr").removeAttr("disabled");
					manageEntryChequeTable.ajax.reload(null, false);
					ChequePlanement(chequeId);
    		},error: function (xhr) {
			alert(xhr.responseText);
		}
	  });
	}
}
		

	/*------------------ Start Save cheque & validation panel ---------------------- */
		
		
		$(document).ready(function() {
		$('#form_addCheque').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_addCheque button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
		  $(".entryBtn").removeAttr("disabled");
		
		  var chequeReceivingDate = $("#chequeReceivingDate").val();
		  var partyType = $("#partyType").val();
		 
		  var voucherType = $("#voucherType").val();
          var partyId = $("#partyId").val();
          var bankId = $("#bankId").val();
          var branchId = $("#branchId").val();
          var chequeType = $("#chequeType").val();
          var payTo = $("#payTo").val();
          var depositeAccount = $("#depositeAccount").val();
          var chequeNo = $("#chequeNo").val();
          var chequeDate = $("#chequeDate").val();
          var amount = $("#amount").val();
			
		  var fd = new FormData();
		  fd.append('saveCheque',"saveCheque");
		  fd.append('chequeReceivingDate',chequeReceivingDate);
		  fd.append('partyType',partyType);
		  fd.append('voucherType',voucherType);
          fd.append('partyId',partyId);
          fd.append('bankId',bankId);
          fd.append('branchId',branchId);
          fd.append('chequeType',chequeType);
          fd.append('payTo',payTo);
          fd.append('depositeAccount',depositeAccount);
          fd.append('chequeNo',chequeNo);
          fd.append('chequeDate',chequeDate);
          fd.append('amount',amount);

		  $.ajax({
				type: 'POST',
				url: 'phpScripts/manageChequeModule.php',
				data: fd,
				contentType: false,
				processData: false,
				dataType: 'json',
				success: function(response){
					if(response == "Success"){
						$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
					    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
						  $(this).hide(); n();
						});
						$(".entryBtn").removeAttr("disabled");
						manageEntryChequeTable.ajax.reload(null, false);

						$("#chequeReceivingDate").val("");
						$("#partyType").val("");
						$("#partyId").val("");
						$("#bankId").val("");
						$("#branchId").val("");
						$("#chequeType").val("");
						$("#payTo").val("");
						$("#depositeAccount").val("");
						$("#chequeNo").val("");
						$("#chequeDate").val("");
					    $("#amount").val("");
					}
				},error: function (xhr) {
					alert(xhr.responseText);
				}
			  });
			$('#entryNewCheque').modal('hide');
		},
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		excluded: [':disabled'],
        fields: {
				
				name: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Type'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				}
			}
			});
		}); 
		
	
	

 function openChequePlanement(id) {
	$(".btnattr").removeAttr("disabled");
	//alert(id)
	var dataString = "page1=ShowChequeData&id="+id;
	// fd.append('Id',Id);
	// fd.append('ShowChequeData',"ShowChequeData");
	$.ajax({
	type: 'GET',
	caches: false,
	url: 'phpScripts/manageChequeModule.php',
	data: dataString,
    dataType: "json",
		success:function(row)
		{
			$("#chequePlacement").modal('show');
			//alert(row.cheque_date)
			$("#chequeId").val(row.id);
			$("#place_chequeNo").val(row.cheque_no);
			$("#place_partyName").val(row.partyName);
			$("#tbl_partyId").val(row.tbl_party_id);
			$("#partyType").val(row.party_type);
			$("#bankName").val(row.bank_name);
			$("#amount").val(row.amount);
			$("#place_receivingDate").val(row.cheque_receiving_date);
			$("#place_chequeDate").val(row.cheque_date);

			if(row.status == "Completed"){
				$("#placementDate").attr("disabled","disabled");
				$("#chequeStatus").attr("disabled","disabled");
				$("#bounceAndClearanceDate").attr("disabled","disabled");
				$(".btnattr").prop("disabled", true);
			}else{
				$("#placementDate").removeAttr("disabled");
				$("#chequeStatus").removeAttr("disabled");
				$("#bounceAndClearanceDate").removeAttr("disabled");
				$(".btnattr").removeAttr("disabled");
			}
			ChequePlanement(row.id);
		},error: function (xhr) {
		alert(xhr.responseText);
	}
  });


}
function ChequePlanement(id) {
		var fd= new FormData();
		fd.append('chequeId',id);
		fd.append('chequePlacementData',"chequePlacementData");
		//alert(chequeId)
		$("#PlacementedTableBody").html('');
		$.ajax({
			url: "phpScripts/manageChequeModule.php",
			method:"POST",
			data:fd,
			dataType: 'json',
			contentType: false,
			processData: false,
		
			success:function(prow){
					
					var i=0;
					var j=1;
					var btn = '';
					
					for( i=0; i<prow.length; i++){
						
						if(prow[i].cheque_status == "Clear"){
							  btn = "<button onclick='deletePlacement("+prow[i].id +","+prow[i].tbl_cheque_id+","+prow[i].cheque_no+")' class='btn ml-3 btn-danger btn-sm btn-space btn-flat'><i class='fa fa-trash'></i> delete</button>";
						}
						else{
							  btn = "<button onclick='deletePlacement("+prow[i].id +","+prow[i].tbl_cheque_id+","+null+")' class='btn ml-3 btn-danger btn-sm btn-space btn-flat'><i class='fa fa-trash'></i> delete</button></a>";
						}

						$("#PlacementedTableBody").append("<tr>" +
						   " <td class='text-left'>" +  j  + "</td>" +
						   "<td class='text-center'> " + prow[i].placement_date + "</td>" + 
						   "<td class='text-center'> " +prow[i].clearance_date + "</td>" +
						   "<td class='text-center status'>" + prow[i].cheque_status + "</td>" +
						   "<td>"+btn+"</td>" +
						   "</tr>");

						   if(i==0){
							$(".status").append("<span>     </span><i class='fa fa-circle text-green' style='font-size:9px' aria-hidden='true'></i>");
						}
						j++;
					}
					
					if(prow[0].cheque_status == "Clear"){
						$("#placementDate").attr("disabled","disabled");
						$("#chequeStatus").attr("disabled","disabled");
						$("#bounceAndClearanceDate").attr("disabled","disabled");
						$(".btnattr").prop("disabled", true);
					}
					else if(prow[0].cheque_status != "Clear"){
						$(".btnattr").removeAttr("disabled");
						$("#placementDate").removeAttr("disabled");
			        	$("#chequeStatus").removeAttr("disabled");
			         	$("#bounceAndClearanceDate").removeAttr("disabled");
					}
				
				manageEntryChequeTable.ajax.reload(null, false);
			},
			complete: function () {
				$('#loading').hide();
			},
			error: function (xhr) {
				alert(xhr.responseText);
			}
		});
}




			/*------------------ End Save Expense Type & validation panel ---------------------- */
	

		
			$("#partyId").select2( {
				allowClear: true,
				selectOnClose: true
			} );
			$("#bankId").select2({
				placeholder: "~~ Select Bank ~~",
				allowClear: true
			});
			$("#branchId").select2({
				placeholder: "~~ Select Bank Branch ~~",
				allowClear: true
			});
			



			$(document).ready(function() {
				$('#form_addChequePlacement').bootstrapValidator({
				live:'enabled',
				message:'This value is not valid',
				submitButton:'$form_addChequePlacement button [type="Submit"]',
				submitHandler: function(validator, form, submitButton){
				  var chequeId = $("#chequeId").val();
				  var chequeNo = $("#place_chequeNo").val();
				  var placementDate = $("#placementDate").val();
				  var chequeDate = $("#place_chequeDate").val();
				  var partyId= $("#tbl_partyId").val();
				  var partyName= $("#place_partyName").val();
				  var partyType= $("#partyType").val();
				  var bankName = $("#bankName").val();
				  var amount = $("#amount").val();
				  var chequeStatus = $("#chequeStatus").val();
				  var bounceAndClearanceDate = $("#bounceAndClearanceDate").val();
				  alert(partyType);
			
				  var fd = new FormData();
				  fd.append('saveChequePlacement',"saveChequePlacement");
				  fd.append('chequeId',chequeId);
				  fd.append('chequeNo',chequeNo);
				  fd.append('amount',amount);
				  fd.append('partyId',partyId);
				  fd.append('partyType',partyType);
				  fd.append('partyName',partyName);
				  fd.append('bankName',bankName);
				  fd.append('chequeDate',chequeDate);
				  fd.append('placementDate',placementDate);
				  fd.append('chequeStatus',chequeStatus);
				  fd.append('bounceAndClearanceDate',bounceAndClearanceDate);
				  $.ajax({
						type: 'POST',
						url: 'phpScripts/manageChequeModule.php',
						data: fd,
						contentType: false,
						processData: false,
						dataType: 'json',
						success: function(response){
							
							if(response == "Success"){
								
								$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Placed");
								$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
								  $(this).hide(); n();
								});
								$(".btnattr").removeAttr("disabled");
								ChequePlanement(chequeId);
								manageEntryChequeTable.ajax.reload(null, false);
		
								// $("#chequeId").val("");
								// $("#chequeStatus").val("");
							
							}
						},error: function (xhr) {
							alert(xhr.responseText);
						}
					  });
				},
				feedbackIcons: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-refresh'
				},
				excluded: [':disabled'],
				fields: {
						
						name: {
							validators: {
									stringLength: {
									min: 3,
								},
									notEmpty: {
									message: 'Please Insert Type'
								},
								regexp: {
									regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
									message: 'Please insert alphanumeric value only'
								}
							}
						}
					}
					});
				}); 
				
				