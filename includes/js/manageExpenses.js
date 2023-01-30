var manageExpenseTable;


// retrive Expense data

$(document).ready(function() {
	manageExpenseTable = $("#manageExpenseTable").DataTable({
		'ajax': 'phpScripts/manageExpenses-add.php',
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



// Delete Expenses

function deleteExpense(id){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
        var action = "action_delete";
        $.ajax({
    	    url: 'phpScripts/manageExpenses-add.php',
    		method:"POST",
    		data:{action:action, id:id},
		
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
		
	
		
	/*------------------ Start Save Expenses validation panel ---------------------- */

		$(document).ready(function() {
		$('#form_addExpenses').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_addExpenses button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
		  var expenseDate = $("#expenseDate").val();
		  var expenseCause = $("#expenseCause").val();
		  var expenseType = $("#expenseType").val();
		  alert(expenseType)
		  var expenseBy = $("#expenseBy").val();
		  var amount = $("#amount").val();
		  var status = $("#status").val();

		  var fd = new FormData();
		  fd.append('saveExpense',"saveExpense");
		  fd.append('expenseDate',expenseDate);
		  fd.append('expenseCause',expenseCause);
		  fd.append('expenseType',expenseType);
		  fd.append('expenseBy',expenseBy);
		  fd.append('amount',amount);
		  fd.append('status',status);

		  $.ajax({
				type: 'POST',
				url: 'phpScripts/manageExpenses-add.php',
				
				data: fd,
				contentType: false,
				processData: false,
				dataType: 'json',
				success: function(response){
					alert(JSON.stringify(response))
					if(response == "Success"){
						$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
					   $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
						  $(this).hide(); n();
						});

						manageExpenseTable.ajax.reload(null, false);
						 $("#expenseDate").val('');
						$("#expenseCause").val('');
						 $("#expenseType").trigger('change');
						 $("#expenseBy").trigger('change');
						 $("#amount").val('');
						 $("#status").trigger('change');
					
					}
				},error: function (xhr) {
					alert(JSON.stringify(xhr))
					alert(xhr.responseText);
				}
			  });
		  
			$('#addExpense-modal').modal('hide');
		},
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		excluded: [':disabled'],
        fields: {
				
				expenseCause: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Cause'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				productCode: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Product Name'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				
				
				amount: {
					validators: {
						
						regexp: {
							regexp: /^((-)?(0|([1-9][0-9]*))(\.[0-9]+)?)$/,
							message: 'Insert Amount like : 200 '
						}
					}
				},
			
			}
			});
		}); 
		
	
	
	/*------------------ End Save Expenses validation panel ---------------------- */
	

		

	/*------------------ Start Save Expenses select2 panel ---------------------- */
	    
	$("#expenseType").select2( {
		placeholder: "Select Type",
		dropdownParent: $("#addExpense-modal"),
		allowClear: true
	});
	$("#expenseBy").select2( {
		placeholder: "Expense By",
		dropdownParent: $("#addExpense-modal"),
		allowClear: true
	} );
	
		
	/*------------------ End Save Expenses select2 panel ---------------------- */
