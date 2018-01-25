'use strict';
    //()(); fonction autolancer s'apelle automatiquement
(
    function () {
        //cibler la croix du cookies disclaimer
        let cookiesDisclaimerButton = $('.close-cookies-disclaimer');
        
        //tester l'existence du bouton
        if(cookiesDisclaimerButton)
        {
            console.log('ok');
            //écouteur d'événements
            cookiesDisclaimerButton.on('click',onClickCloseCookiesDisclaimer)
        }
        //click sur la croix du cookeis disclaimer
        function onClickCloseCookiesDisclaimer(e)
        {
            //requette ajax
            //alert('onClickCloseCookiesDisclaimer');
            $.ajax({
                dataType : 'json',
                url: '/fr/ajax/cookies-disclaimer',
                type: 'post',
                data: {'disclaimerValue': false},
                success: onSuccessCloseCookiesDisclaimer

            });
        }
        // si la requete a reussie
        function onSuccessCloseCookiesDisclaimer(response){
            console.log(response);
        }
    }
)();

