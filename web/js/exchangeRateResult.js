'use strict';

(
    function(){
        let selectConvert = $('.money');
        selectConvert.click( onSelectConvert);





        function onSelectConvert(e)
        {
          let value = $(this).data('currency');
           // console.log(value);

              $.ajax({
                  dataType: 'json',
                  data: {'selectValue': value},
                  type: 'post',
                  url: '/fr/ajax/convert',
                  success: onSuccessSelectCategory
              });


        }
        function onSuccessSelectCategory(response){
            console.log(response);



        }
    }

)()