$(document).ready(function () {


    // Delete key

    $('.deleteKey').click(function () {

        if (confirm("Are you sure delete this item?")) {
            var id = $(this).data('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/delete',
                type: "POST",
                data: {id: id},
                success: function (data) {
                    $('.tr_key' + id).hide();

                }
            });
        }
        return false;
    })

    //Update keyword

    $(".keyword").change(function (){
        var id = $(this).data('id');
        var value = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/updateKey',
            type: "POST",
            data: {id: id, value: value},
            success: function (data) {
                $('.save' + id).show();
                setInterval(function () {
                    $('.save' + id).hide();
                }, 2000);
            }
        });

    })

    $(".translations").change(function () {

        var id = $(this).data("id");
        var value = $(this).val();
        var locale = $(".locale" + id).val()
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/update',
            type: "POST",
            data: {id: id, value: value, locale: locale},
            success: function (data) {
                $('.save' + id).show();
                setInterval(function () {
                    $('.save' + id).hide();
                }, 2000);
            }
        });
    });


    $('#search_keyword').keyup(function () {
        var query = $(this).val();
        if (query != '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/autocomplete/fetch',
                method: "POST",
                data: {query: query},
                success: function (data) {
                    $('#keywordsList').fadeIn();
                    $('#keywordsList').html(data);
                }
            });
        }
    });

    $(document).on('click', 'li', function () {
        $('#search_keyword').val($(this).text());
        $('#keywordsList').fadeOut();
    });

});
