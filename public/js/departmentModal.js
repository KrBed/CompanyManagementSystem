$('#department').on('click', function () {
    $('.js-department-error').empty();
    $('#departmentModal').modal();
})
// $('#departmentModal').on('shown.bs.modal', function () {
//     $('#myInput').trigger('focus')
// })
$(document).ready(function () {

    $("#department-btn").on('click', function (e) {
        e.preventDefault();

        let $link = $("#department-form").attr('action');
        let department_name = $("#department_name").val();

        $.ajax({
            method: "POST",
            url: $link,
            data: {name: department_name}
        }).done(function (data) {
            console.log(data)
            if ('error' in data) {
                $('.js-department-error').text(data['error']);
                return;
            }
            $('#departmentModal').modal('hide');
            $messageDiv = $('#message');
            $messageDiv.attr('hidden', false)
            $messageDiv.text(data['name']);
            $messageDiv.fadeOut(5000);
        })
    })
})
