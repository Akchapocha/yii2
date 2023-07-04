/**
 * Функция поиска данных по выбранной очереди
 */
$('button').click( function () {

    $('.preload').css('display', 'block');

    var post = {
        _csrf: document.querySelector('meta[name="csrf-token"]').content,
        ts_from: $('[name="date_start"]').val(),
        ts_to: $('[name="date_end"]').val(),
        queue: $('select').val(),
    };

    $.ajax({
        type: 'POST',
        url: "/queue/show",
        data: post,
        dataType: 'json',
        success: function (data) {

            if (data !== ''){

                if ( data.match(/<table class="data_tbl">/) ){

                    $('.data_tbl').remove();
                    $('.tbl').append(data);
                    $('.preload').css('display', 'none');

                } else {

                    $('.preload').css('display', 'none');
                    alert(data);

                }

            } else {

                $('.preload').css('display', 'none');

            }

        }
    });

});