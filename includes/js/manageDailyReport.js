$(function() {
    $("select").select2();
});

const viewCalculation = () => {

    let remainingDate = [];
    $("input[name=remainingDate]").each(function() {
        remainingDate.push($(this).val());
    });

    
    
    //--start check previous calculation---//
    var todayDate = new Date($('#date').val());
    var date= todayDate.getDate()  + '/' + (todayDate.getMonth() + 1) + '/' + todayDate.getFullYear();
   // alert(date);
    if (remainingDate.length > 0 && remainingDate < date) {
        var preDate = remainingDate[0];
        let confirmCheck = confirm("Are you sure ? Save Previous Report!");
  
        if(confirmCheck==true){
            var prevDate = new Date(preDate);

            $('#date').val(prevDate);
            //alert(preDate)

            getCalculation(preDate);
        }  
    } else {
        let date = $("#date").val();
        getCalculation(date);
    }
    //--end check previous calculation---//
}

 var tempDate = '';
const getCalculation = (preDate) => {
    //alert(preDate)
    let fd = new FormData();
    fd.append('action', "getDailyReport");
    fd.append('date', preDate);

    $.ajax({
        type: 'POST',
		url: 'phpScripts/manageDailyReport.php',
		data: fd,
		contentType: false,
		dataType : 'json',
		processData: false,
        success: function(result){
        //alert(JSON.stringify(result));
            $("#manageReportTable").html(result[0]);
            let totalAmount = result[2][3];
            let openingBalance = result[1] == null ? 0 :result[1].opening_balance;
            let closingAmount = parseFloat(openingBalance) + parseFloat(totalAmount);
            $("#openingBalance").val(openingBalance);
            $("#totalAmount").val(totalAmount);
            $("#closingAmount").val(closingAmount);
            tempDate = String(preDate);
        },
        beforeSend: function() {
            $('#loading').show();
        },
        complete: function() {
            $('#loading').hide();
        },
        error: function(error) {
            alert(JSON.stringify(error))
        }
    });
}

const saveTodayReport = () => {

//     let date = $("#date").val();
    let date = tempDate;
    let openingBalance = $("#openingBalance").val();
    let totalAmount = $("#totalAmount").val();
    let closingAmount = $("#closingAmount").val();
  
    if (date == "" || openingBalance == "" || totalAmount == "" || closingAmount == "") {
    
        let confirmCheck = confirm("Error! Fill up form!");
  
        return 0;
    }
   // let _token = $('input[name="_token"]').val();
    let fd = new FormData();
    fd.append('date', date);
    fd.append('openingBalance', openingBalance);
    fd.append('totalAmount', totalAmount);
    fd.append('closingAmount', closingAmount);
    fd.append('saveReport', "saveReport");
    //fd.append('_token', _token);


    var confirmCheck = confirm("Are you sure ? Save Report!");
  
    if(confirmCheck=true){
            $.ajax({
                url: "phpScripts/manageDailyReport.php",
                method: "POST",
                data: fd,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(result) {
                   
                    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully saved!");
                },
                error: function(response) {
                    alert(JSON.stringify(response));
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    clearData();
                    $('#loading').hide();
                    location.reload();
                }

            });

         }

}

const clearInput = () => {
    var conMsg = confirm("Are you sure ? : Data will clear");
	if(conMsg){
            clearData();

        } else {
           
        }
    
}

function clearData() {
    $("#manageReportTable").html('');
    $("#openingBalance").val('');
    $("#totalAmount").val('');
    $("#closingAmount").val('');
}