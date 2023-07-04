jQuery(function(){

    var op = {
        "login" : "вошел",
        "logout" : "вышел",
        "pause" : "встал на паузу",
        "unpause" : "снялся с паузы",
    };

    var debug = false;

    $('button').click( function () {

        var post = {
            _csrf: document.querySelector('meta[name="csrf-token"]').content,
            number: $('[name="number"]').val(),
            date_start: $('[name="bydate1"]').val(),
            date_end: $('[name="bydate2"]').val()
        };

        $.ajax({
            url: '/working-hours/charts',
            type: 'POST',
            data: post,
            success: function(responce){
                var data = JSON.parse(responce);
                $('#data-content').empty();

                if (typeof(data) == "object"){
                    $('#data-content').append('<table class="table"></table>');

                    ObjectProcessing(data);

                    $('#data-content h3').click(function() {
                        if($(this).siblings().is(':visible')){
                            $(this).siblings().hide();
                        }
                        else{
                            $(this).siblings().show();
                        }
                    });
                }
                else{
                    var output = '<div class="alert alert-danger alert-dismissable text-center">'
                    + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                    + '<strong>' + data + '</strong></div>';
                    $('#data-content').append(output);
                }
            },

            datetype: JSON
        });
    });

    function TimeDiff(date_1, date_2){
        var date_1 = new Date(date_1);
        var date_2 = new Date(date_2);
        var interval = new Date(date_2 - date_1);
        var h = interval.getUTCHours();
        if (h < 10) h = '0' + h;
        var m = interval.getMinutes();
        if (m < 10) m = '0' + m;
        var s = interval.getUTCSeconds();
        if (s < 10) s = '0' + s;

        var diff = h + ':' + m + ':' + s;
        return diff;
    }


function ObjectProcessing(object){

var j = 0;
    for (var key in object){
        //Внутри объекта с датами начало
        var array_pause = [];
        var array_session = [];
        var array_usernames = [];
        var date_object =  sortObject(object[key]);
        var u = 0;
        for(var user_key in date_object){
            //Внутри объекта с пользователями начало
            var user_object = date_object[user_key];

            for (var user_session in user_object){
                //Внутри объекта с сессиями начало
                var session = user_object[user_session];
                var temp_array_session = [];
                var temp_array_pause = [];

                if (user_session != 0){ //если номер сессии не равен нулю
                    for (var i = 0; i < session.length; i++) // перебор элементов внутри сессии - начало
                    {
                        var ended = true;

                        if (i == 0){
                            var login = session[i]['date_time'];
                            
                            if (session[session.length - 1]['op'] == 'logout'){
                                var logout = session[session.length - 1]['date_time'];
                            }
                            else{
                                var logout = (NotTheSameDay(login, Math.floor(+Date.now()))) ? Math.floor(+Date.now()) : new Date(login).setHours(21, 0);
                                var ended = false;
                            }

                            temp_array_session = [u, login, logout];
                            array_session.push(temp_array_session);
                        }
                        else if (i > 0){
                            if (session[i]['op'] != 'pause'){
                                continue;
                            }
                            else{
                                var pause = session[i]['date_time'];

                                if (typeof session[i+1] !== 'undefined'){
                                    var unpause = session[i+1]['date_time'];
                                }
                                else{
                                    var unpause = (NotTheSameDay(pause, Math.floor(+Date.now()))) ? Math.floor(+Date.now()) : new Date(pause).setHours(21, 0)
                                    var ended = false;
                                }
                            }
                            temp_array_pause = [u, pause, unpause];
                            array_pause.push(temp_array_pause);
                        }
                    } // перебор элементов внутри сессии - конец

                }
                else{
                    //если login предыдущий день то logout тоже ставить предыдущий 21:00
                    var login = user_object[user_session][0]['date_time'];
                    var logout = (NotTheSameDay(login, Math.floor(+Date.now()))) ? Math.floor(+Date.now()) : new Date(login).setHours(21, 0);
                    temp_array_session = [u, login, logout];
                    var ended = false;
                    array_session.push(temp_array_session);
                }
                var user_name = session[0]['agent_name'] + " " + session[0]['internal'];
                
                //Внутри объекта с сессиями конец
            }
            array_usernames.push(user_name);
            //Внутри объекта с пользователями начало
            u++;
        }
        //Внутри объекта с датами конец
        j++;
        $('#data-content').append('<div id="container_'+j+'"></div>');
        RenderData(array_session, array_pause, array_usernames, key, j);

    }
}

function RenderData(data_session, data_pause, categories, date, el_id){

        // console.log(data_session);

    Highcharts.setOptions({
        global: {
            timezoneOffset: -3 * 60
        }
    });

    if (categories.length < 20){
        var chart_height = categories.length * 200;
    }
    else {
        var chart_height = categories.length * 64;
    }
    
    Highcharts.chart('container_'+el_id, {
        
            chart: {
                type: 'columnrange',
                inverted: true,
                height: chart_height,
            },
        
            title: {
                text: 'Отчет за ' + date
            },
        
            subtitle: {
            },
        
            xAxis: {
                categories: categories,
            },
        
            yAxis: [{
                type: 'datetime',
                title: {
                    text: 'Время'
                },
            },{
                linkedTo: 0,
                opposite: true,
                type: 'datetime',
                title: {
                    text: 'Время'
                },
            }],

            plotOptions: {
                columnrange: {
                    dataLabels: {
                        align: 'right',
                        enabled: true,
                        formatter: function () {
                            var d = new Date(this.y);
                            if (this.y == this.point.high){
                                var label = TimeDiff(this.point.low, this.point.high);
                            }
                            return label;
                        }
                    }
                }
            },
        
            legend: {
                enabled: false
            },
        
            series: [{
                name: 'сессия',
                data: data_session,
                color: '#7CB5EC'
            },
            {
                name: 'пауза',
                data: data_pause,
                color: '#999999'
            }],

            tooltip: {
                formatter: function() {
                    var output = '<span>'+this.key+'</span>'
                    + '<br>'
                    + '<span style="font-weight:bold; text-align:center;">'+ this.series.userOptions.name +'</span>'
                    + '<br>'
                    + '<span style="font-weight:bold;">Время: ' + Highcharts.dateFormat('%H:%M', new Date(this.point.low)) + ' - ' + Highcharts.dateFormat('%H:%M', new Date(this.point.high)) + '</span>';

                    return output;
                },
            }

        
        }); 
}

    function sortObject(obj) {

        return Object.keys(obj).sort().reduce(function (result, key) {
            result[key] = obj[key];
            return result;
        }, {});

    }

    function NotTheSameDay(a, b){

        date_a = new Date(a);
        date_b = new Date(b);

        var result = (date_a.getDate() == date_b.getDate()) ? true : false

        return result;
    }

});
