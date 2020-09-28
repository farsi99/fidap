$(function () {
    $(".clickmembre").click(function () {
        var id = $(this).attr('id');
        console.log(id);
        $.ajax({
            type: 'POST',
            url: g_domain + 'admin-uprad/vueAbonne',
            data: { 'id': id },
            success: function (data) {
                $(".pourProfile").html(data);
                return false;
            }
        });

    });
});