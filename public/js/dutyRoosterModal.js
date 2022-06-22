let listOfUsers = [];
let timesheets = [];
let usersToAdd = []
$(document).ready(function () {

    var todayDate = new Date().toISOString().slice(0, 10);

    $.datepicker.setDefaults({
        dateFormat: 'dd-mm-yy'
    }, $.datepicker.regional["pl"]);
    $('#date-from').datepicker();
    $('#date-to').datepicker();
    $('#time-from').timepicker({
        timeFormat: 'H:i',
        interval: 30,
    });
    $('#time-to').timepicker({
        timeFormat: 'H:i',
        interval: 30,
    });

    $('#date-from').on('change',function(){
        var selectedDate = $(this).val();
        selectedDate = selectedDate.split("-").reverse().join("-");
       if(selectedDate <= todayDate){
           $('#add-new-duty-rooster-btn').attr("disabled",true)
       }else{
           $('#add-new-duty-rooster-btn').attr("disabled",false)
       }
    })

    //dispalying modal
    $("#addRooster").on('click', function () {
        usersToAdd = [];
        $('#addUserRoosterModal').modal();
        $('#not-normalized-form').hide();
        $('#normalize').addClass('button-group-bord-dark');
        $('.users-table-body').children().removeClass('bg-primary');
        enableDisableDutyButton();
        ;
    })

    $('#addRoosterUser-btn').on('click', function () {
        removeErrorMessages();
        $('#addUserRoosterModal').modal('hide');
        $('#addDutyRoosterModal').modal();

        if($('#date-from').val() <= todayDate){
            $('#add-new-duty-rooster-btn').attr("disabled",true)
        }else{
            $('#add-new-duty-rooster-btn').attr("disabled",false)
        }

        let $date = $('#dutyRoosterDate').attr('data-date');
        $day = $date.substr(0, 2);
        $month = $date.substr(3, 2);
        $year = $date.substr(6, 4);
        let dateFrom = new Date($year, $month - 1, 1);

        let dateTo = new Date($year, $month, 0);

        $('#date-from').val(formatDate(dateFrom));
        $('#date-to').val(formatDate((dateTo)));

        let buttons = $('#rooster-btns').children().addClass('button-group-bord-dark');
        $('#sob').removeClass('button-group-bord-dark');
        $('#nd').removeClass('button-group-bord-dark');

    })

    //get user data and change row color on list
    $(".timesheet-user-row").on('click', function () {
        let tickClass = 'bg-primary';
        row = $(this);
        userId = row.attr('data-user-id');

        if (row.hasClass(tickClass)) {
            row.removeClass(tickClass);
            let id = usersToAdd.indexOf(userId);
            usersToAdd.splice(id, 1);
            enableDisableDutyButton();
        } else {
            row.addClass(tickClass);
            if (!usersToAdd.includes(userId)) {
                usersToAdd.push(userId);
            }
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
        }
        ;
    })

    $('#add-new-duty-rooster-btn').on('click', function () {
        removeErrorMessages()
        var users = usersToAdd;
        let unique1 = usersToAdd.filter((o) => listOfUsers.indexOf(o) === -1);
        let unique2 = listOfUsers.filter((o) => usersToAdd.indexOf(o) === -1);

        const unique = unique1.concat(unique2);

        var dutyRooster = {};
        var weekWorkingDays = [];
        dutyRooster.dateFrom = $('#date-from').val();
        dutyRooster.dateTo = $('#date-to').val();
        var weekDays = $('#rooster-btns').children();

        weekDays.each(function () {
            if ($(this).hasClass('button-group-bord-dark')) {
                weekWorkingDays.push($(this).attr('value'));
            }
        });

        dutyRooster.weekDays = weekWorkingDays;

        if ($('#normalize').hasClass('button-group-bord-dark')) {
            dutyRooster.timeFrom = $('#time-from').val();
            dutyRooster.timeTo = $('#time-to').val();
        } else {
            dutyRooster.hoursAmount = $('#hours-amount').val();
        }

        dutyRooster.countTimeBeforeWork = $('#count-time-before-work').prop('checked');
        dutyRooster.countTimeAfterWork = $('#count-time-after-work:checked').prop('checked');
        dutyRooster.overtimeDutyRooster = $('#overtime-dutyrooster:checked').prop('checked');
        dutyRooster.excludeChristmas = $('#exclude-christmas:checked').prop('checked');
        dutyRooster.listOfUsers = listOfUsers;

        for (var i = 0; i < users.length; i++) {
            if (timesheets.some(timesheet => timesheet.userId == users[i])) {
                var user = timesheets.find(timesheet => timesheet.userId = users[i])
                var index = timesheets.indexOf(user);
                timesheets.splice(index, 1);

            }
            let timesheet = Timesheet(dutyRooster, users[i])

            timesheets.push(timesheet);
        }
        var validated = validateDutyRoosters(dutyRooster);

        if(validated == false)return;

        createDutyRoosters(usersToAdd, dutyRooster);

        $('#addDutyRoosterModal').modal('hide');
        if (timesheets.length > 0) {
            $('#publish').removeClass('btn-light').addClass('btn-primary').removeAttr("disabled");
            $('#restore').removeClass('btn-light').addClass('btn-danger').removeAttr("disabled");
        }
    })

    $('#publish').on('click', function () {
        $.ajax({
            type: 'POST',
            url: $('.rooster-table-body').attr('data-path'),
            data: {timesheet: timesheets},
            success: function (data) {
                $('#publish').removeClass('btn-primary').addClass('btn-light').attr("disabled","disabled");
                $('#restore').removeClass('btn-danger').addClass('btn-light').attr("disabled","disabled");
            },
        })
    })
})

function validateDutyRoosters(dutyRooster){

    if(typeof (dutyRooster.hoursAmount) != 'undefined'){
        let $hoursAmount = parseInt(dutyRooster.hoursAmount.substr(0, 2));
        if($hoursAmount <= 0  ){
            $('.rooster-error').prepend('<p class="text-danger pb-2">Ilość godzin jest za niska</p>')
            return false;
        }
    }else{
        let $hourFrom = parseInt(dutyRooster.timeFrom.substr(0, 2));
        let $minuteFrom = parseInt(dutyRooster.timeFrom.substr(3, 2));
        let $timeFrom = $hourFrom + $minuteFrom;
        let $hourTo = parseInt(dutyRooster.timeTo.substr(0, 2));
        let $minuteTo = parseInt(dutyRooster.timeTo.substr(3, 2));
        let $timeTo = $hourTo + $minuteTo;
        let $hour = $hourTo - $hourFrom;
        let $minutes = $minuteTo - $minuteFrom;

        var thirdChenge = 0
        if($hourFrom > $hourTo){
            var thirdChenge = 24 - $hourFrom;
            thirdChenge + $hourTo;
        }
        if(thirdChenge > 16  ){
            $('.rooster-error').prepend('<p class="text-danger pb-2">Czas pracy nie może wynosić więcej jak 16 godzin</p>')
            return false;
        }
        if($hour == 0){
            if($minuteFrom>= $minuteTo){
                $('.rooster-error').prepend('<p class="text-danger pb-2">Czas od nie może być mniejszy niż czas do</p>')
                return false;
            }
        }
        if (dutyRooster.timeFrom.length < 5 || dutyRooster.timeTo < 5) {
            $('.rooster-error').prepend('<p class="text-danger pb-2">Pola czas od i czas do nie mogą być puste</p>')
            return false;
        }
    }
    removeErrorMessages();

    if(dutyRooster.weekDays.length == 0){
        $('.rooster-error').prepend('<p class="text-danger pb-2">Dni tygodnia są wymagane</p>')
        return false;
    }
    return true;
}
function removeErrorMessages(){
    var errorDiv = $('.rooster-error');
    var p = errorDiv.find('p');
    p.remove();
}

function Timesheet(dutyRooster, userId) {
    var timesheet = {};
    timesheet.dateFrom = dutyRooster.dateFrom;
    timesheet.dateTo = dutyRooster.dateTo;
    timesheet.weekDays = dutyRooster.weekDays;
    timesheet.timeFrom = dutyRooster.timeFrom;
    timesheet.timeTo = dutyRooster.timeTo;
    timesheet.hoursAmount = dutyRooster.hoursAmount;
    timesheet.countTimeBeforeWork = dutyRooster.countTimeBeforeWork;
    timesheet.countTimeAfterWork = dutyRooster.countTimeAfterWork;
    timesheet.overtimeDutyRooster = dutyRooster.overtimeDutyRooster;
    timesheet.overrideExistingRoosters = dutyRooster.overrideExistingRoosters;
    timesheet.excludeChristmas = dutyRooster.excludeChristmas;
    timesheet.listOfUsers = dutyRooster.listOfUsers;
    timesheet.userId = userId;

    return timesheet;
}

function createDutyRoosters(listOfUsers, dutyRooster) {
    for (var i in listOfUsers) {
        let cellId = listOfUsers[i];
        let cells = $(".rooster-table-body").find("[data-user-id ='" + cellId + "']").children();
        cells.each(function (i, e) {
            let dayTo = dutyRooster.dateTo.substring(0, 2);
            let dayFrom = dutyRooster.dateFrom.substring(0, 2);
            let cell = $(e);
            let cellDate = cell.find('div').attr('data-date');
            let day = cell.find('div').attr('value');
            let dayNumber = cellDate.substring(0, 2);
            if (dayNumber >= dayFrom && dayNumber <= dayTo) {

                let numericDay = cell.find('div').attr('data-numericDay');
                cell.find('.rooster-cell').remove();
                var wrapper = cell.find('.rooster-wrapper');
                var roosterTime =''

                if(typeof(dutyRooster.hoursAmount) != 'undefined') {
                     roosterTime = '<span class="rooster-time">' + dutyRooster.hoursAmount + ' godzin pracy.</span>'
                }else{
                     roosterTime = '<span class="rooster-time">' + dutyRooster.timeFrom + '-' + dutyRooster.timeTo + '</span>'
                }
                if (dutyRooster.weekDays.includes(numericDay)) {
                    if (dutyRooster.countTimeBeforeWork == true || dutyRooster.countTimeAfterWork == true) {
                        wrapper.prepend('<div class="rooster-cell" >' + roosterTime + '<span class="rooster-overtime">' + "+nadgodziny." + '</span></div>');
                    } else if (dutyRooster.overtimeDutyRooster == true) {
                        wrapper.prepend('<div class="rooster-cell" >' + roosterTime + '<span class="rooster-overtime">' + "grafik nadgodz." + '</span></div>');
                    } else {
                        wrapper.prepend('<div class="rooster-cell" >' + roosterTime);
                    }

                } else {
                    wrapper.prepend('<div class="rooster-cell">' +
                        '<span class="rooster-time"></span></div>')
                }
            }
        })
    }
}

$('#not-normalize').on('click', function () {
    let $notnormalize = $(this);
    let $normalize = $('#normalize');
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
        $notnormalize.addClass('button-group-bord-dark')
        $normalize.addClass('button-group-bord-light')
        $normalize.removeClass('button-group-bord-dark')
    }
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


