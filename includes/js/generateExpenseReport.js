function generateExpenseReport(){
    
    var date=$('#Date').val();
    $.ajax({
        url: "phpScripts/generateExpenseReport.php",
        method:"GET",
        data:{"date":date},
        success:function(result){
            //alert(JSON.stringify(result));
            $("#reportBtn").attr("href", "http://localhost/BDSupplier/expenseReport-view.php?date="+date);
            $("#manageExpenseReportTable").html(result);
            $("#reportBtn").show();
        }, error: function(response) {
           // alert(JSON.stringify(response));
            $("#dateFromError").text("Enter a date");
           // $("#dateToError").text(response.dateTo);
        }, beforeSend: function () {
            $('#loading').show(); 

        },complete: function () {
            $('#loading').hide();                           
        }
    })

}

function expenseReport(){
    id = $('#Date').val();
    var date=$('#Date').val();
    $.ajax({
        url: "phpScripts/ExpenseReport-view.php",
        method:"GET",
        data:{"date":date},
        success:function(result){
            //alert(JSON.stringify(result));
            //$("#manageExpenseReportTable").html(result);
           // $("#reportBtn").show();
        }
    })
}

