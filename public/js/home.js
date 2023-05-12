$( function () {
    $("#company_symbol").select2({
        placeholder: "Select a company",
        allowClear: true
    });
    $("#start_date").datepicker({dateFormat: 'yy-mm-dd'});
    $("#end_date").datepicker({dateFormat: 'yy-mm-dd'});

    $("form").on("submit", function (e) {
        let valid = true;

        // validate company symbol
        if ($("#company_symbol").val() === '') {
            alert('Company Symbol is required');
            valid = false;
        }

        // validate dates
        const startDate = new Date($("#start_date").val());
        const endDate = new Date($("#end_date").val());
        const today = new Date();

        if (isNaN(startDate.getTime()) || startDate > today) {
            alert('Start Date is required and must be less than or equal to today');
            valid = false;
        }

        if (isNaN(endDate.getTime()) || endDate > today) {
            alert('End Date is required and must be less than or equal to today');
            valid = false;
        }

        if (startDate > endDate) {
            alert('Start Date must be less than or equal to End Date');
            valid = false;
        }

        // validate email
        const email = $("#email").val();
        const emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (email === '' || !emailReg.test(email)) {
            alert('A valid Email is required');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
});
