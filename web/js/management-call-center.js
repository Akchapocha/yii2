$('button').click(function () {

   $('.preload').css('display', 'block');

   var post = {
      _csrf: document.querySelector('meta[name="csrf-token"]').content,
      ts_from: $('[name="date_start"]').val(),
      ts_to: $('[name="date_end"]').val(),
      operator: $('select').val(),
   };

   $.ajax({
      type: 'POST',
      url: "/management-call-center/show",
      data: post,
      dataType: 'json',
      success: function (data) {

         if (data !== ''){

            if ( data.match(/<table class="data_tbl">/) ){

               $('.data_tbl').remove();
               $('.tbl').append(data);
               $('.preload').css('display', 'none');

            } else {

               $('.grd_row').remove();
               $('.grd_bottom').remove();

               $('.preload').css('display', 'none');
               alert(data);

            }

         } else {

            $('.preload').css('display', 'none');

         }

      }
   });

});