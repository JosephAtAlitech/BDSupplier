var manageEntryChequeTable;


// retrive Expense type data

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
		processData: false,
		
    		success:function(response)
    		{
				if(response == "Success"){
					$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
				    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
					  $(this).hide(); n();
					});
					manageExpenseTypeTable.ajax.reload(null, false);
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

		alert(chequeNo)
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
				//alert(response)
			
					$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
				    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
					  $(this).hide(); n();
					});
					$(".btnattr").removeAttr("disabled");
					manageEntryChequeTable.ajax.reload(null, false);
    		},error: function (xhr) {
			alert(xhr.responseText);
		}
	  });
	}
}
		

	/*------------------ Start Save Expense Type & validation panel ---------------------- */
		
		
		$(document).ready(function() {
		$('#form_addCheque').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_addCheque button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
		
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
			$("#bankName").val(row.bank_name);
			$("#amount").val(row.amount);
			$("#place_receivingDate").val(row.cheque_receiving_date);
			$("#place_chequeDate").val(row.cheque_date);

			if(row.status == "Completed"){
				$("#placementDate").attr("disabled","disabled");
				$("#chequeStatus").attr("disabled","disabled");
				$("#bounceAndClearanceDate").attr("disabled","disabled");
			}else{
				$("placementDate").removeAttr("disabled");
				$("#chequeStatus").removeAttr("disabled");
				$("#bounceAndClearanceDate").removeAttr("disabled");
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
					var btn = '';
					for( i=0; i<prow.length; i++){
						if(prow[i].cheque_status == "Clear"){
							  btn = "<button onclick='deletePlacement("+prow[i].id +","+prow[i].tbl_cheque_id+","+prow[i].cheque_no+")' class='btn ml-3 btn-danger btn-sm btn-space btn-flat'><i class='fa fa-trash'></i> delete</button>";
						}
						else{
							  btn = "<button onclick='deletePlacement("+prow[i].id +","+null+","+null+")' class='btn ml-3 btn-danger btn-sm btn-space btn-flat'><i class='fa fa-trash'></i> delete</button></a>";
						}
						//var button ="<a href='#'  onclick='deletePlacement("+prow[i].cheque_status+","+prow[i].id +")'><button class='btn ml-3 btn-danger btn-sm btn-space btn-flat'><i class='fa fa-trash'></i> delete</button>"
						$("#PlacementedTableBody").append("<tr>" +
						   " <td class='text-left'>" +  i  + "</td>" +
						   "<td class='text-center'> " + prow[i].placement_date + "</td>" +
						   "<td class='text-center'> " +prow[i].clearance_date+ "</td>" +
						   "<td class='text-center'> " + prow[i].cheque_status + "</td>" +
						   "<td>"+btn+"</td>" +
						   "</tr>");
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
				  var bankName = $("#bankName").val();
				  var amount = $("#amount").val();
				  var chequeStatus = $("#chequeStatus").val();
				  var bounceAndClearanceDate = $("#bounceAndClearanceDate").val();
				
			
				  var fd = new FormData();
				  fd.append('saveChequePlacement',"saveChequePlacement");
				  fd.append('chequeId',chequeId);
				  fd.append('chequeNo',chequeNo);
				  fd.append('amount',amount);
				  fd.append('partyId',partyId);
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
		
								manageEntryChequeTable.ajax.reload(null, false);
		
								// $("#chequeId").val("");
								// $("#chequeStatus").val("");
							
							}
						},error: function (xhr) {
							alert(xhr.responseText);
						}
					  });
				  
					$('#chequePlacement').modal('hide');
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
				
				