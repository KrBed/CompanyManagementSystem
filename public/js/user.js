$(document).ready(function () {
    $('.user-row').hover(function () {
        let element = $(this);
        let path = element.children().last().attr('data-update-path')
        element.children().last().append('<span class="float-right"><a href="' + path + '" class="pr-2"><i style="font-size: 12px" class="material-icons create">create</i></a><i id="delete" style="font-size: 12px" class="material-icons delete">delete</i> </span>');
    }, function () {
        $(this).children().last().find('span').remove();
    })

    $('.user-menagement-row').hover( function () {
        let element = $(this);
        let path = element.attr('data-info-path')
        element.children().last().append('<span class="float-right"><a href="' + path + '" class="pr-2"><i style="font-size: 12px" class="material-icons create">visibility</i></a>');
    },function () {
        $(this).children().last().find('span').remove();
    })

    $(document).on('click', '#delete', function () {
        let element = $(this);
        let path = element.closest("td").attr('data-delete-path');
        let id = element.closest("tr").attr('data-user-id');

        $.ajax({
            type: 'DELETE',
            url: path,
            data: {id: id},
            success: function (data) {
                $("tr[data-user-id=" + id + "]").remove();
            }
        })
    })
})