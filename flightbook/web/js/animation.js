$(document).ready(function () {
    // @hack Permet d'avoir l'affichage souhaité de la checkbox (moche)
    $('#glider_tandem').appendTo(".form-group:last");
    $('#glider .checkbox label').appendTo(".form-group:last");
    $('#glider_tandem').appendTo(".form-group:last");
    $('#glider .checkbox').hide();

    // autocomplete start and landing
    $("#flight_startText, #flight_landingText").autocomplete({
        source: "../place/autocomplete",
        minLength: 2
    });

});