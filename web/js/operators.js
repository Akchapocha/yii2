/**
 * Общие функции для operators-visible.js и operators-hidden.js
 *
 */


/**
 * Ввод ПИН "вручную" при создании нового оператора
 */
$('.modal [name="changePin"]').click(function () {

    var pin = prompt('Введите ПИН (от 1000 до 9999)', $('.modal [name="pin"]').val());

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

                    $('.modal [name="pin"]').val(pin);

                    coincides = coincides + 1;
                }

            }

            if (coincides == 0) {

                alert('Пин: ' + pin + ' уже занят, введите другой пин.');

            }

            if (coincides == 1) {

                $('.modal [name="pin"]').val(pin);

            }

        } else {

            alert( "Не допустимый пин: " + pin + '. Пин может быть от 1000 до 9999.');

        }

    }

});

/**
 * Открытие модального окна при нажатии 'Добавить'
 */
$('#createOperator').click(function () {
    open_close_Modal('open');
});

/**
 * Закрытие модального окна при нажатии на 'X'
 */
$('.closeModal').click(function () {
    open_close_Modal('close');
});

/**
 * Закрытие модального окна создания при нажатии 'Отмена'
 */
$('#cancel').click(function () {
    open_close_Modal('close');
});

/**
 * Закрытие модального окна редактирования при нажатии 'Отмена'
 */
$('#cancel-edit').click(function () {
    open_close_Modal('close');
});