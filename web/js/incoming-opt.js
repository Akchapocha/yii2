/**
 * Функция поиска входящих оптовых вызовов по выбранным параметрам
 */
$('button').click( function () {

    $('.preload').css('display', 'block');

    var post = {
        _csrf: document.querySelector('meta[name="csrf-token"]').content,
        ts_from: $('[name="date_start"]').val(),
        ts_to: $('[name="date_end"]').val(),
        operator: $('.operator').val(),
        phone: $('[name="phone"]').val(),
        sheet: $('.pages').val(),
    };

    $.ajax({
        type: 'POST',
        url: "/incoming-opt/sheets",
        data: post,
        dataType: 'json',
        success: function (sheetsCount) {

            if (sheetsCount !== ''){

                $('.pages option').remove();

                var i = 1;
                var option = '';

                if ( +post['sheet'] > +sheetsCount ){
                    post['sheet'] = 1;
                }

                for (i; i <= sheetsCount; i++){

                    if (+i === +post['sheet']){

                        option = option+'<option selected>'+i+'</option>';

                    } else {

                        option = option+'<option>'+i+'</option>';

                    }

                }

                $('.pages').append(option);

            }

        }
    });

    $.ajax({
        type: 'POST',
        url: "/incoming-opt/show",
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

                $('.data_tbl').remove();
                $('.tbl').append('<table class="data_tbl" cellspacing=0>\n<tr class="grd_head">\n<td>Нет данных за указанный период</td>\n</table>');
                $('.preload').css('display', 'none');

            }

        }
    });

});