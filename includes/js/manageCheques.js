var manageExpenseTypeTable;


// retrive Expense type data

$(document).ready(function() {
	manageExpenseTypeTable = $("#manageExpanseTypeTable").DataTable({
		'ajax': 'phpScripts/manageExepnseType-add.php',
		'order': [],
		'dom': 'Bfrtip',
        'buttons': [
            'pageLength','copy', 'csv', 'pdf', 'print'
        ],
		language: {
            processing: "<img src='../images/loader.gif'>"
        },
        processing: true
	});
});

//Delete Expense Type
function deleteExpenseType(id){
	var conMsg = confirm("Are you sure to delete??")
	if(conMsg){
		
		var Id = id;
		var fd = new FormData();
		fd.append('Id',Id);
		fd.append('action_delete',"deleteExpenseType");
		$.ajax({
		type: 'POST',
		url: 'phpScripts/manageExepnseType-add.php',
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
		

	/*------------------ Start Save Expense Type & validation panel ---------------------- */
		
		
		$(document).ready(function() {
		$('#form_addCheque').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_addCheque button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
		
		  var receivingDate = $("#receivingDate").val();
          var paymentFrom = $("#paymentFrom").val();
          var bankName = $("#bankName").val();
          var brandName = $("#brandName").val();
          var chequeType = $("#chequeType").val();
          var payTo = $("#payTo").val();
          var depositeAccount = $("#depositeAccount").val();
          var chequeNo = $("#chequeNo").val();
          var chequeDate = $("#chequeDate").val();
          var amount = $("#amount").val();
			
		  var fd = new FormData();
		  fd.append('saveCheque',"saveCheque");
		  fd.append('receivingDate',receivingDate);
          fd.append('paymentFrom',paymentFrom);
          fd.append('bankName',bankName);
          fd.append('branchName',branchName);
          fd.append('chequeType',chequeType);
          fd.append('payTo',payTo);
          fd.append('depositeAccount',depositeAccount);
          fd.append('chequeNo',chequeNo);
          fd.append('chequeDate',chequeDate);
          fd.append('amount',amount);
          
		  $.ajax({
				type: 'POST',
				url: 'phpScripts/manageExepnseType-add.php',
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

						manageExpenseTypeTable.ajax.reload(null, false);

						$("#name").val('');
					}
				},error: function (xhr) {
					alert(xhr.responseText);
				}
			  });
		  
			$('#addExpenseType').modal('hide');
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
		
		
	    //Expense Type Form Update

		$(document).ready(function() {

		$('#expenseTypeFormUpdate').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'#expenseTypeFormUpdate button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){

		var expenseTypeId = $("#editExpenseTypeId").val();
		var expenseTypeName = $("#editExpenseTypeName").val();
		var expenseTypeStatus = $("#editExpenseTypeStatus").val();
		 
		 var fd = new FormData();
	
		  fd.append('expenseTypeId',expenseTypeId);
		  fd.append('expenseTypeName',expenseTypeName);
		  fd.append('expenseTypeStatus',expenseTypeStatus);
		  fd.append('action','updateExpenseType');
		  $.ajax({
			type: 'POST',
			url: 'phpScripts/manageExepnseType-add.php',
			data: fd,
			contentType: false,
			processData: false,
			dataType: 'json',
           success: function(msg) {
              $("#loading-image").hide();
           },
			success: function(response){
				
				if(response == "Success"){
					$('#editExpenseType').modal('hide');
						$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Update Successfully");
					    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
						  $(this).hide(); n();
						});
					    manageExpenseTypeTable.ajax.reload(null, false);
					   
				}else{
				    alert(response);
				}
			},error: function (xhr) {
				alert(JSON.stringify(xhr))
			}
		  });
		  
		  $('#editExpenseType').modal('hide');
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
							message: 'Please Insert Type '
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
		
		//Expense Type Edit

		function editExpenseType(id) {

		    var dataString = "page=editExpenseType&id="+id;
			//alert(id);
            $.ajax({
                method: "GET",
                url: 'phpScripts/manageExepnseType-add.php',
                data: dataString,
                dataType: "json",
                success: function(result) {	
                    $("#editExpenseType").modal('show');
                    $("#editExpenseTypeId").val(result.id);
                    $("#editExpenseTypeName").val(result.name);
                    $("#editExpenseTypeStatus").val(result.status);
					//$('#loading').hide();
                },
                error: function(response) {
                    alert(JSON.stringify(response));
                },
                beforeSend: function() {
                   // $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        }

			/*------------------ End Save Expense Type & validation panel ---------------------- */
	
