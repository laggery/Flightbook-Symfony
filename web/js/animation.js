$(document).ready(function () {
    // @hack Permet d'avoir l'affichage souhaité de la checkbox (moche)
    $('#glider_tandem').appendTo(".form-group:last");
    $('#glider .checkbox label').appendTo(".form-group:last");
    $('#glider_tandem').appendTo(".form-group:last");
    $('#glider .checkbox').hide();

	var $label = $("<label>").text($('#toAck').text());
	$('#flight_filter_date_left_date').after($label);

    // autocomplete start and landing
    $("#flight_startText, #flight_landingText").autocomplete({
        source: "../place/autocomplete",
        minLength: 2
    });

    //Permet de cacher le filtre si on clique sur la legende
    var close = true;
    $('#filter legend').click(function () {
        if (close) {
            $('#filter .glyphicon').removeClass('glyphicon-chevron-down');
            $('#filter .glyphicon').addClass('glyphicon-chevron-up');
            close = false;
        } else {
            $('#filter .glyphicon').removeClass('glyphicon-chevron-up');
            $('#filter .glyphicon').addClass('glyphicon-chevron-down');
            close = true;
        }
        $(this).siblings().stop().slideToggle('slow', function () {});
    });
	
	$.fn.datepicker.defaults.autoclose = true;
	$('.js-datepicker').datepicker({
		format: 'dd.mm.yyyy'
	}).val();
 

});