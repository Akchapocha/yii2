/**
 * Функция срабатывания нажатия на изменить, скрыть
 */
$('.list-operators span').click(function () {

    var action = this.id;

    var post = {
        _csrf: document.querySelector('meta[name="csrf-token"]').content,
        idAgent: this.parentElement.parentElement.id
    };

    if (action === 'edit'){

        open_close_Modal('openEdit', post['idAgent'] );

    } else {

        $.ajax({
            type: 'POST',
            url: '/operators-visible/'+action,
            data: post,
            dataType: 'json',
            success: function (data) {

                if ( data === 'Оператор был успешно скрыт.' ){

                    alert(data);
                    location.reload();

                } else {

                    alert(data);

                }

            }
        });

    }

});


/**
 * Ввод ПИН "вручную" при редактировании оператора
 */
$('.modal-edit [name = "changePin"]').click(function () {

    var pin = prompt('Введите ПИН (от 1000 до 9999)', $('.modal-edit [name="pin"]').val());

    if (pin){

        if ( /^[1-9]\d{3}$/.test(pin) ) {

            var post = {
                _csrf: document.querySelector('meta[name="csrf-token"]').content,
                action: 'getAvailablePins'
            };

            var availablePins;

            $.ajax({
                type: 'POST',
                async: false,
                url: '/operators-visible/get-pins',
                data: post,
                dataType: 'json',
                success: function (data) {

                    if (data) {

                        availablePins = data;

                    }

                }
            });

            var coincides = 0;

            for (var key in availablePins) {

                if (pin == availablePins[key]) {

                    $('.modal-edit [name="pin"]').val(pin);

                    coincides = coincides + 1;
                }

            }

            if (coincides == 0) {

                alert('Пин: ' + pin + ' уже занят, введите другой пин.');

            }

            if (coincides == 1) {

                $('.modal-edit [name="pin"]').val(pin);

            }

        } else {

            alert( "Не допустимый пин: " + pin + '. Пин может быть от 1000 до 9999.');

        }

    }

});

/**
 * Функция открытия/закрытия модального окна
 */
function open_close_Modal(action, id){

    if (action === 'open') {


        $('.modal input').val('');
        $('.modal').css('display', 'block');
        $('.modal-overlay').css('display', 'block');

        var post = {
            _csrf:document.querySelector('meta[name="csrf-token"]').content,
            action: 'getRandomPin'
        };

        $.ajax({
            type: 'POST',
            url: '/operators-visible/get-pins',
            data: post,
            dataType: 'json',
            success: function (data) {

                if (data) {

                    $('.modal [name="pin"]').val(data);

                }

            }
        });

    }

    if (action === 'openEdit') {

        $('.modal-edit input').val('');

        var post = {
            _csrf:document.querySelector('meta[name="csrf-token"]').content,
            idAgent: id
        };

        $.ajax({
            type: 'POST',
            url: '/operators-visible/get-operator',
            data: post,
            dataType: 'json',
            success: function (data) {

                if ( data['id'] ){

                    $('.modal-guts-edit [name="fio"]').val(data['agent_name']);
                    $('.modal-guts-edit [name="pin"]').val(data['pin']);
                    $('.modal-guts-edit [name="queue1"]').val(data['q1']);
                    $('.modal-guts-edit [name="queue2"]').val(data['q2']);
                    $('.modal-guts-edit [name="queue3"]').val(data['q3']);
                    $('.modal-guts-edit [name="idAgent"]').val(data['id']);

                    $('.modal-edit').css('display', 'block');
                    $('.modal-overlay').css('display', 'block');

                } else {

                    alert(data);

                }

            }
        });

    }

    if (action === 'close') {

        $('.modal').css('display', 'none');
        $('.modal-edit').css('display', 'none');
        $('.modal-overlay').css('display', 'none');

    }

}

/**
 * Сохранение нового оператора
 */
$('.modal #save').click(function () {

    var post = {
        _csrf: document.querySelector('meta[name="csrf-token"]').content,
        fio: $('.modal [name = "fio"]').val(),
        pin: $('.modal [name = "pin"]').val(),
        queue1: $('.modal [name = "queue1"]').val(),
        queue2: $('.modal [name = "queue2"]').val(),
        queue3: $('.modal [name = "queue3"]').val(),
        id: $('.modal [name = "idAgent"]').val(),
    };

    if ( post['fio'] === '' ){

        alert('Введите Имя Фамилию Отчество');

    } else {

        $.ajax({
            type: 'POST',
            url: '/operators-visible/create',
            data: post,
            dataType: 'json',
            success: function (data) {

                if (data === 'Оператор был успешно добавлен.'){

                    alert(data);
                    location.reload();

                } else {

                    alert(data);

                }

            }
        });

    }

});

/**
 * Сохранение отредактированного оператора
 */
$('.modal-edit #save').click(function () {

    var post = {
        _csrf: document.querySelector('meta[name="csrf-token"]').content,
        fio: $('.modal-edit [name = "fio"]').val(),
        pin: $('.modal-edit [name = "pin"]').val(),
        queue1: $('.modal-edit [name = "queue1"]').val(),
        queue2: $('.modal-edit [name = "queue2"]').val(),
        queue3: $('.modal-edit [name = "queue3"]').val(),
        id: $('.modal-edit [name = "idAgent"]').val(),
    };

    if ( post['fio'] === '' ){

        alert('Введите Имя Фамилию Отчество');

    } else {

        $.ajax({
            type: 'POST',
            url: '/operators-visible/edit',
            data: post,
            dataType: 'json',
            success: function (data) {

                if (data === 'Оператор был успешно изменен.'){

                    alert(data);
                    location.reload();

                } else {

                    alert(data);

                }

            }
        });

    }

});