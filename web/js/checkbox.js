$('input.winning').on('change', function() {
    $('input.winning').not(this).prop('checked', false);  
});

var checkboxes = $("input[type='checkbox']"),
    submitButt = $("button[type='submit']");

checkboxes.click(function() {
    submitButt.attr("disabled", !checkboxes.is(":checked"));
});