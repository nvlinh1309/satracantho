$(document).ready(function () {
    $(".translations").change(function () {


        var id = $(this).data("id");
        var value = $(this).val();
        var locale = $(".locale").val()
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/update',
            type: "POST",
            data: {id: id, value:value, locale:locale},
            success: function (data) {
                $('.save'+id).show();
                setInterval(function(){
                    $('.save'+id).hide();
                },2000);
            }
        });
    });
});
