'use strict';

(
    function(){
        let selectCategory = $('[name="select-category"]');
        selectCategory.on('change', onSelectCategory);//change = valeur des champs qui change

        function onSelectCategory(e)
        {
          let value = ($(this).val());
          $.ajax({
             dataType: 'json',
             data: {'selectValue' : value},
              type: 'post',
              url: '/fr/ajax/search',
              success: onSuccessSelectCategory
          });
        }
        function onSuccessSelectCategory(response){
            console.log(response['products']);
            let searchResults = $('.colDroite');

                searchResults.empty();
           let products = response.products;
           let html = '';
           products.map(function(products){
               html += `
                    <div class="row" >
                   <div class="col-sm-2">
                   <img src="/img/product/${products.image}" class="img-fluid" alt="">
                   </div>
                   <div class="col-sm-8">
                   ${products.translations.fr.name}
                   </div>
                   <div class="col-sm-2">
                    ${products.price}
                   </div>
                   </div>`
           });
            searchResults.append(html);

        }
    }

)()