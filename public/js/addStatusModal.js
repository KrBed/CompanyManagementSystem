let $enterBtn = $('#enter-btn');
let $exitBtn = $('#exit-btn');
let $addStatusBtn = $('#addStatus-btn');

$('#rcp-btn').on('click', function () {
    $('.js-addStatus-error').empty();
    $exitBtn.removeClass('act').addClass('btn-outline-danger');
    $enterBtn.removeClass('act').addClass('btn-outline-success');
    disableEnableStatusBtn();
    $('#addStatusModal').modal();
})

$(document).ready(function () {
    let $link = $('#addStatus-form').attr('action');
    disableEnableStatusBtn();
    $enterBtn.on('click', function () {
        if ($enterBtn.hasClass('act')) {
            $enterBtn.removeClass('act').addClass('btn-outline-success');
        } else {
            $enterBtn.addClass('act').removeClass('btn-outline-success');
            $exitBtn.removeClass('act').addClass('btn-outline-danger');
        }
        disableEnableStatusBtn();
    });

    $exitBtn.on('click', function () {
        if ($exitBtn.hasClass('act')) {
            $exitBtn.removeClass('act').addClass('btn-outline-danger');
        } else {
            $exitBtn.addClass('act').removeClass('btn-outline-danger');
            $enterBtn.removeClass('act').addClass('btn-outline-success');
        }
        disableEnableStatusBtn();
    })

    $addStatusBtn.on('click', function () {
        let $status;
        if ($enterBtn.hasClass('act')) {
            $status = $enterBtn.attr('data-status');
        } else {
            $status = $exitBtn.attr('data-status');
        }

        $.ajax({
            method: 'POST',
            dataType: "json",
            url: $link,
            data: {status: $status}
        }).done(function (data) {
            appendNewStatusToList(data);
            $('#addStatusModal').modal('hide');
        })
    })
});

function disableEnableStatusBtn() {

    if ($enterBtn.hasClass('act') || $exitBtn.hasClass('act')) {
        $addStatusBtn.attr('disabled', false);
    } else {
        $addStatusBtn.attr('disabled', true);
    }
}

function appendNewStatusToList(data) {

    var status = JSON.parse(data);
    let statusNameColor = '';
    let lastEventDate = $('#events').find(".list-container").first().attr("id");

    if (status.name == 'Wejście') {
        statusNameColor = '<span class="event-time text-success">';
    } else {
        statusNameColor = '<span class="event-time text-danger">';
    }

    if (lastEventDate !== status.date) {
        addEventsContainer(status, statusNameColor);
    } else {
        let $listContainer = $('#' + status.date);
        let $element = '<div class="list-element">' + statusNameColor + '' + status.time + ' - ' + status.name + '</span> <span class="user-name">' + status.userName +
            '</span><span class="department-name">' + status.department + '</span> <span class="send-by">Przesłane przez ' + status.sendBy + '</span> </div>';

        $listContainer.prepend($element);
    }
}

function addEventsContainer($status, statusNameColor) {
    let day = $status.date.substring(0, 2);
    if (day[0] == 0) day = day.substring(1, 2);
    let container = '<div class="events-container row"><div class="events-date p-3 col">' +
        '<div class="date-container"> <span class="month-day">' + day + ' </span> <span class="week-day">' + $status.day + ' </span> </div>' +
        '<div class="events-list col"> <div class="list-container" id="18-02-2020"> <div class="list-element"> ' + statusNameColor + '' + $status.time + ' - ' + $status.name + '</span> <span class="user-name">' + $status.userName + '</span>' +
        '<span class="department-name">' + $status.department + '</span> <span class="send-by">Wysłane przez ' + $status.sendBy + '</span> </div>' +
        '</div> </div> </div> </div>';
    $('#events').prepend(container);
}
