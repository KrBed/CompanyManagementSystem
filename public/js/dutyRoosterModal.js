$(document).ready(function () {
    let listOfUsers = [];

    $.datepicker.setDefaults({
        dateFormat: 'dd-mm-yy'
    }, $.datepicker.regional["pl"])
    $('#date-from').datepicker();
    $('#date-to').datepicker();
    $('#time-from').timepicker({});


    //dispalying modal
    $("#addRooster").on('click', function () {
        $('#addUserRoosterModal').modal();
        $('.users-table-body').children().removeClass('bg-primary')
        enableDisableDutyButton()
    })

    $('#addRoosterUser-btn').on('click', function () {
        $('#addDutyRoosterModal').modal();
        let $date = $('#dutyRoosterDate').attr('data-date');
        $day = $date.substr(0, 2);
        $month = $date.substr(3, 2);
        $year = $date.substr(6, 4);
        let dateFrom = new Date($year, $month - 1, 1);

        let dateTo = new Date($year, $month, 0);

        $('#date-from').val(formatDate(dateFrom));
        $('#date-to').val(formatDate((dateTo)));
    })

    //get user data and change row color on list

    $(".user-row").on('click', function () {
        let tickClass = 'bg-primary';
        row = $(this);
        userId = row.attr('data-user-id');
        if (row.hasClass(tickClass)) {
            row.removeClass(tickClass);
            let id = listOfUsers.indexOf(userId);
            listOfUsers.splice(id, 1);
            enableDisableDutyButton();
        } else {
            row.addClass(tickClass);
            listOfUsers.push(userId);
            enableDisableDutyButton();
        }
    })
    $("#rooster-btns button").on("click", function () {
        let $btn = $(this);
        if ($btn.hasClass('button-group-bord-dark')) {
            $btn.removeClass('button-group-bord-dark')
            $btn.addClass('button-group-bord-light')
        } else {
            $btn.removeClass('button-group-bord-light')
            $btn.addClass('button-group-bord-dark');
        }
        ;
    })

    $('#normalize').on('click', function () {
        let $normalize = $(this);
        let $notnormalize = $('#not-normalize');
        let $normalizeform = $('#normalized-form');
        let $notnormalizeform = $('#not-normalized-form');
        if ($normalize.hasClass('button-group-bord-dark')) {
            $normalizeform.hide();
            $notnormalizeform.show();
            $normalize.removeClass('button-group-bord-dark')
            $notnormalize.addClass('button-group-bord-dark')
            $notnormalize.removeClass('button-group-bord-light')
            $normalize.addClass('button-group-bord-light')
        } else {
            $normalizeform.show();
            $notnormalizeform.hide();
            $normalize.removeClass('button-group-bord-light')
            $normalize.addClass('button-group-bord-dark');
            $notnormalize.addClass('button-group-bord-light')
            $notnormalize.removeClass('button-group-bord-dark')
        };
    })
})
$('#not-normalize').on('click', function () {
    let $notnormalize = $(this);
    let $normalize = $('#normalize');
    let $normalizeform = $('#normalized-form');
    let $notnormalizeform = $('#not-normalized-form');
    if ($notnormalize.hasClass('button-group-bord-dark')) {
        $('#normalized-form').show();
        $('#not-normalized-form').hide();
        $notnormalize.removeClass('button-group-bord-dark')
        $notnormalize.addClass('button-group-bord-light')
        $normalize.addClass('button-group-bord-dark')
        $normalize.removeClass('button-group-bord-light')
    } else {
        $('#normalized-form').hide();
        $('#not-normalized-form').show();
        $notnormalize.removeClass('button-group-bord-light')
        $notnormalize.addClass('button-group-bord-dark');
        $normalize.addClass('button-group-bord-light')
        $normalize.removeClass('button-group-bord-dark')
    }
    ;
})

function enableDisableDutyButton() {
    let button = $('#addRoosterUser-btn');
    let userRows = $('.users-table-body').find('tr');
    userRows.each(function () {
        if ($(this).hasClass('bg-primary')) {
            button.attr('disabled', false)
            return false;
        } else {
            button.attr('disabled', true);
        }
    })
}

function formatDate($date) {
    $day = $date.getDate().toString();
    $month = ($date.getMonth() + 1).toString();
    $year = $date.getFullYear().toString();
    if ($day.length == 1) {
        $day = "0" + $day;
    }
    if ($month.length == 1) {
        $month = "0" + $month;
    }
    return $day + "-" + $month + "-" + $year;
}


