$(function () {
    $("#company_startdate").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#company_enddate").datepicker({ dateFormat: 'yy-mm-dd' });
});

const formsave_btn = document.getElementById('company_submit');
formsave_btn.addEventListener('click', function (e) {
    const startdate = document.getElementById('company_startdate').value;
    const enddate = document.getElementById('company_enddate').value;


    let proceed = true;
    if (startdate > enddate) {
        proceed = false;
        display_message('error', "start date can't be greater than end date");
    }


    if (!proceed) {
        // console.log('startdate: ' + startdate, 'enddate: ' + enddate);
        e.preventDefault();
    }
});