import Cookies from 'js-cookie'
import $ from 'jquery'

var validationButton = document.getElementById("CookiesValidation");
var rejectButton = document.getElementById("CookiesRefuse");


$(document).ready(function () {

    if (!Cookies.get('Cookies')) {

        function modalDisplay() {
            $('.modal').modal('show')
        }


        setTimeout(modalDisplay, 1000);

    }

    var setCookies = function () {
        Cookies.set('Cookies', "Valided", {expires: 365});
        $('.modal').modal('hide');

    };
    var getOut = function () {
        history.back();
    };

    rejectButton.addEventListener("click", getOut);
    validationButton.addEventListener("click", setCookies);

})