/**
 * Функция перехода к файлам выбранной папки
 */
$('tbody>tr').click(function () {

    $('.preload').css('display', 'block');

    var post = {
        _csrf: document.querySelector('meta[name="csrf-token"]').content,
        nameDir: $('#' + this.id + '').attr('name'),
        numOfPage: 1,
        strOnPage: 50
    };

    $.ajax({
        type: 'POST',
        url: '/file-records/dir',
        data: post,
        dataType: 'json',
        success: function (data) {

            if (data !== ''){

                if ( data.match(/<table class="table table-bordered table-hover">/) ){

                    $('.records>table').remove();
                    $('.records').append(data);
                    $('.preload').css('display', 'none');

                } else {

                    $('.preload').css('display', 'none');
                    alert(data);

                }

            } else {

                $('.preload').css('display', 'none');
                alert('Ошибка');

            }

        }
    });

});

/**
 * Функция отправки данных на 'actionDir()' контроллера 'FileRecordsController'
 * при изменении страницы для отображения
 *
 * @param post
 */
function sendPost(post) {

    $.ajax({
        type: 'POST',
        url: '/file-records/dir',
        data: post,
        dataType: 'json',
        success: function (data) {

            if (data !== ''){

                if ( data.match(/<table class="table table-bordered table-hover">/) ){

                    $('.pagNav').remove();
                    $('#nameDir').remove();
                    $('#del').remove();
                    $('.countPag').remove();

                    $('.records>table').remove();
                    $('.records').append(data);
                    $('.preload').css('display', 'none');

                } else {

                    $('.preload').css('display', 'none');
                    alert(data);

                }

            }

        }
    });

}

/**
 * Функция изменения вида страницы при выборе страницы из списка
 *
 */
function changeNumStr () {

    $('.preload').css('display', 'block');

    var nameDir = $('#nameDir').text();

    nameDir = nameDir.replace(/Текущая папка: /, '');

    var post = {
        _csrf: document.querySelector('meta[name="csrf-token"]').content,
        nameDir: nameDir,
        numOfPage: $('[name = "numOfPage"]').val(),
        strOnPage: $('[name = "strOnPage"]').val(),
    };

    sendPost(post);

}

/**
 * Функция изменения вида страницы при выборе количества отображаемых строк из списка
 */
function changeStrOnPage() {

    $('.preload').css('display', 'block');

    var nameDir = $('#nameDir').text();

    nameDir = nameDir.replace(/Текущая папка: /, '');

    var post = {
        _csrf: document.querySelector('meta[name="csrf-token"]').content,
        nameDir: nameDir,
        numOfPage: 1,
        strOnPage: $('[name = "strOnPage"]').val(),
    };

    sendPost(post);

}

/**
 * Функция изменения вида страницы при нажатии 'предыдущие 10|следующие 10'
 *
 * @param string
 */
function plus_minus(string) {

    $('.preload').css('display', 'block');

    var delta = Number.parseInt(string);

    var nameDir = $('#nameDir').text();
    nameDir = nameDir.replace(/Текущая папка: /, '');

    var post = {
        _csrf: document.querySelector('meta[name="csrf-token"]').content,
        nameDir: nameDir,
        numOfPage: +$('[name = "numOfPage"]').val() + delta,
        strOnPage: $('[name = "strOnPage"]').val(),
    };

    sendPost(post);

}

/**
 * Функция изменения вида страницы при нажатии
 * на номер страницы в ленте пагинации
 *
 * @param numPage - номер выбранной страницы
 */
function changePage(numPage) {

    $('.preload').css('display', 'block');

    var nameDir = $('#nameDir').text();
    nameDir = nameDir.replace(/Текущая папка: /, '');

    var post = {
        _csrf: document.querySelector('meta[name="csrf-token"]').content,
        nameDir: nameDir,
        numOfPage: numPage,
        strOnPage: $('[name = "strOnPage"]').val(),
    };

    sendPost(post);

}
