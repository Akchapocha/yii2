/**
 * Функция срабатывания нажатия на изменить, скрыть, удалить
 */
$('.list-operators span').click(function () {

    var action = this.id;

    var post = {
        _csrf: document.querySelector('meta[name="csrf-token"]').content,
        idAgent: this.parentElement.parentElement.id
    };

    $.ajax({
        type: 'POST',
        url: '/operators-hidden/'+action,
        data: post,
        dataType: 'json',
        success: function (data) {

            if ( (data === 'Оператор был успешно скрыт.') || (data === 'Оператор был успешно удален.') ){

                alert(data);
                location.reload();

            } else {

                alert(data);

            }

        }
    });

});

/**
 * Функция открытия/закрытия модального окна
 */
function open_close_Modal(action){

    if (action === 'open'){

        $('.modal input').val('');
        $('.modal').css('display', 'block');
        $('.modal-overlay').css('display', 'block');

        var post = {
            _csrf:document.querySelector('meta[name="csrf-token"]').content,
            action: 'getRandomPin'
        };

        $.ajax({
            type: 'POST',
            url: '/operators-hidden/get-pins',
            data: post,
            dataType: 'json',
            success: function (data) {

                if (data) {

                    $('.modal [name="pin"]').val(data);

                }

            }
        });

    } else {

        $('.modal').css('display', 'none');
        $('.modal-overlay').css('display', 'none');

    }
}



/**
 * Ссохранение нового оператора
 */
$('#save').click(function () {

    var post = {
        _csrf: document.querySelector('meta[name="csrf-token"]').content,
        fio: $('[name = "fio"]').val(),
        pin: $('[name = "pin"]').val(),
        queue1: $('[name = "queue1"]').val(),
        queue2: $('[name = "queue2"]').val(),
        queue3: $('[name = "queue3"]').val(),
    };

    if ( post['fio'] === '' ){

        alert('Введите Имя Фамилию Отчество');

    } else {

        $.ajax({
            type: 'POST',
            url: '/operators-hidden/create',
            data: post,
            dataType: 'json',
            success: function (data) {

                if (data === 'Оператор был успешно добавлен.'){

                    alert(data);
                    location.href = '/operators-visible';

                } else {

                    alert(data);

                }

            }
        });

    }

});