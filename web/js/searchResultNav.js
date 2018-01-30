'use strict';


(
    function(){
        let inputSearch = $('.input-search');

        inputSearch.on('keyup', onKeyUpInputSearch);

        function onKeyUpInputSearch(e)
        {
            let value = $(this).val();
            console.log(value);

            $.ajax({
                data: {'searchValue' : value},
                dataType: 'json',
                url: '/fr/ajax/datalist',
                type: 'post',
                success: onSuccessKeyUpInputSearch
            })
        }
        function onSuccessKeyUpInputSearch(response)
        {
            let datalistSearch = $('#datalist-search');
            datalistSearch.empty();
            let html = ';';
            response.map(function(el){
               html+= `<option value="${el.name}">`;
            });
            datalistSearch.append(html);
            console.log(response)
        }
    }
)();