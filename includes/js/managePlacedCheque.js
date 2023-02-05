
var managePlacedChequeTable;


// retrive Expense type data

$(document).ready(function() {
	managePlacedChequeTable = $("#managePlacedChequeTable").DataTable({
		'ajax': 'phpScripts/managePlacedCheque.php',
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





function deletePlacedCheque(id){
    var conMsg = confirm("Are you sure to delete??")
	if(conMsg){
		var Id = id;
		var fd = new FormData();
		fd.append('Id',Id);
		fd.append('action_delete',"deletePlacedCheque");
		$.ajax({
		type: 'POST',
		url: 'phpScripts/managePlacedCheque.php',
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
					managePlacedChequeTable.ajax.reload(null, false);
				}
    		},error: function (xhr) {
			alert(xhr.responseText);
		}
	  });
	}
}