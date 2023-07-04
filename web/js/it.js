/**
 * Функция отправки данных о правах групп пользователей
 * на '/it/apply-group-rule' при нажатии на кнопку 'Применить правила'
 */
$('[name = "applyGroup"]').click(function () {

    var post = {
         _csrf: $('[name = "_csrf"]').val(),
        groups: {},
         pages: {}
    };

    var i = 1;

    $('[name = "groupsCheck"] input:checkbox:checked').each(function(){
        post.groups[i] = $(this).attr('name');
        i++;
    });

    $('[name = "groupsRadio"] input:radio:checked').each(function(){
        post.pages[$(this).attr('name')] = $(this).val();
    });

    $.ajax({
        type: 'POST',
        url: "/it/apply-group-rule",
        data: post,
        dataType: 'json',
        success: function (data) {

            if (data !== ''){

                alert(data);

            }

        }
    });

});

/**
 * Функция поиска пользователей
 */
function findUser() {

    if ($('[name = "findUser"]').val() !== '') {

        var post = {
            _csrf: $('[name = "_csrf"]').val(),
            findUser: $('[name = "findUser"]').val()
        };


        $.ajax({
            type: 'POST',
            url: "/it/find-user",
            data: post,
            dataType: 'json',
            success: function (data) {

                if (data[0]) {
                    showSearchResult(data);
                }

            }
        });

    } else {
        alert('Введите данные для поиска!');
    }
}


/**
 * Вызов поиска пользователей по нажатию кнопки 'Поиск'
 */
$('[name = "find"]').click(function () {

        findUser();

});

/**
 * Вызов поиска пользователей по нажатию 'enter'
 */
$(document).ready(function() {

    $('form').keydown(function(event){

        if(event.keyCode == 13) {

            event.preventDefault();/**  запрет на отправку формы по нажатию 'enter'  */
            return false;

        }

    });

    $('[name = "findUser"]').keydown(function(e) {

        if(e.keyCode === 13) {

            findUser();

        }

    });

});



/**
 *  Функция вывода найденых пользователей в виде 'option' в 'select'
 */
function showSearchResult(users) {


        $( '[name = "users"]' ).empty();

        users.forEach(function (item, i, users){

            $( '[name = "users"]' ).append('<option id="'+ item['id'] +'">'+ item['username'] +'</option>');

        });

}

/**
 * Функция отправки данных о правах пользователей
 * на '/it/apply-user-rule' при нажатии на кнопку 'Применить правила'
 *
 */
$('[name = "applyUser"]').click(function () {

    var post = {
        _csrf: $('[name = "_csrf"]').val(),
        usersId: {},
        pages: {}
    };

    $('[name = "users"]').children(':selected').each(function(){
        post['usersId'][$(this).attr('id')] = $(this).val();
    });

    $('[name = "usersRadio"] input:radio:checked').each(function(){
        post.pages[$(this).attr('name')] = $(this).val();
    });

    $.ajax({
        type: 'POST',
        url: "/it/apply-user-rule",
        data: post,
        dataType: 'json',
        success: function (data) {

            if (data !== ''){

                alert(data);

            }

        }
    });

});