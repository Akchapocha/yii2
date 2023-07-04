/**
 * Функция поиска вызовов по выбранным параметрам
 */
$('button').click( function () {

    $('.preload').css('display', 'block');

    var post = {
        _csrf: document.querySelector('meta[name="csrf-token"]').content,
        ts_from: $('[name="date_start"]').val(),
        phone: $('[name="phone"]').val(),
    };

    $.ajax({
        type: 'POST',
        url: "/call-search/show",
        data: post,
        dataType: 'json',
        success: function (data) {

            if (data !== ''){

                if ( data.match(/<table class="data_tbl">/) ){

                    $('.data_tbl').remove();
                    $('.tbl').append(data);
                    $('.preload').css('display', 'none');

                } else {

                    $('.data_tbl').remove();
                    $('.tbl').append('<table class="data_tbl" cellspacing=0>\n<tr class="grd_head">\n<td>Нет данных за указанный период</td>\n</table>');
                    $('.preload').css('display', 'none');
                    alert(data);

                }

            } else {

                $('.data_tbl').remove();
                $('.tbl').append('<table class="data_tbl" cellspacing=0>\n<tr class="grd_head">\n<td>Нет данных за указанный период</td>\n</table>');
                $('.preload').css('display', 'none');

            }

        }
    });

});