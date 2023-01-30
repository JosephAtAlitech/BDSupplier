$(function() {
    $("select").select2();
});

const viewCalculation = () => {

    let remainingDate = [];
    $("input[name=remainingDate]").each(function() {
        remainingDate.push($(this).val());
    });

    
    
    //--start check previous calculation---//
    let todayDate = $('#date').val();
    //alert(todayDate);
    if (remainingDate.length > 0 && remainingDate < todayDate) {
        date = remainingDate[0];
        let confirmCheck = confirm("Are you sure ? Save Previous Report!");
        
        if(confirmCheck=true){
            $('#date').val(date);
            getCalculation(date);
        }  
    } else {
        let date = $("#date").val();
        getCalculation(date);
    }
    //--end check previous calculation---//
}

const getCalculation = (preDate) => {
    let date = preDate;
   
   // let _token = $('input[name="_token"]').val();
    let fd = new FormData();
    fd.append('date', date);
    // fd.append('_token', _token);

    $.ajax({
        url: "phpScripts/manageDailyReport.php",
        method: "POST",
        data: fd,
        contentType: false,
        processData: false,
        success: function(result) {
            //alert(result);
            $("#manageReportTable").html(result[0]);
            let totalAmount = result[2][3];
            let openingBalance = 0
            if (result[1] != 0) {
                openingBalance = result[1]['opening_balance'];
            }

            let closingAmount = openingBalance + totalAmount;
            $("#openingBalance").val(openingBalance);
            $("#totalAmount").val(totalAmount);
            $("#closingAmount").val(closingAmount);
        },
        beforeSend: function() {
            $('#loading').show();
        },
        complete: function() {
            $('#loading').hide();
        },
        error: function(response) {
            $("#msg_error").html(JSON.stringify(response));
        }
    });
}

const saveTodayReport = () => {

    let date = $("#date").val();
    let openingBalance = $("#openingBalance").val();
    let totalAmount = $("#totalAmount").val();
    let closingAmount = $("#closingAmount").val();

    if (date == "" || openingBalance == "" || totalAmount == "" || closingAmount == "") {
        Swal.fire({
            title: 'Error!',
            text: 'Fill up form',
            icon: 'error',
            confirmButtonText: 'Ok'
        })
        return 0;
    }
    let _token = $('input[name="_token"]').val();
    let fd = new FormData();
    fd.append('date', date);
    fd.append('openingBalance', openingBalance);
    fd.append('totalAmount', totalAmount);
    fd.append('closingAmount', closingAmount);
    fd.append('_token', _token);


    Swal.fire({
        title: "Are you sure ?",
        text: "Report Confirm!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#008000",
        confirmButtonText: "Yes, Confirm!",
        closeOnConfirm: false
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('report/save-today-report') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    Swal.fire("saved!", result.success, "success");
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

        } else {
            Swal.fire("Cancelled", "report cancelled!", "error");
        }
    });

}

const clearInput = () => {
    Swal.fire({
        title: "Are you sure ?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, clear data!",
        closeOnConfirm: false
    }).then((result) => {
        if (result.isConfirmed) {
            clearData();

        } else {
            Swal.fire("Cancelled", "Your data is safe :)", "error");
        }
    })
}

function clearData() {
    $("#manageReportTable").html('');
    $("#openingBalance").val('');
    $("#totalAmount").val('');
    $("#closingAmount").val('');
}