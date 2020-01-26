$('#position').on('click', function () {
    $('.js-position-error').empty();
    $('#positionModal').modal();
})

$('#myModal').on('shown.bs.modal', function () {
    $('#myInput').trigger('focus')
})

$(document).ready(function () {

    $("#position-btn").on('click', function (e) {
        e.preventDefault();

        let $link = $("#position-form").attr('action');
        let name = $("#name").val();

        $.ajax({
            method: "POST",
            url: $link,
            data: {name: name}
        }).done(function (data) {

            if ('error' in data) {
                $('.js-position-error').text(data['error']);
                return;
            }
            $('#positionModal').modal('hide');
            $messageDiv = $('#message');
            $messageDiv.removeAttr('style');
            $messageDiv.attr('hidden', false)
            $messageDiv.text(data['name']);
            $messageDiv.fadeOut(5000);
            setTimeout(function () {
                $messageDiv.attr('hidden', 'hidden')
            },5000)
        })
    })
})
