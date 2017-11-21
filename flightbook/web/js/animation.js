$(document).ready(function () {
    // @hack Permet d'avoir l'affichage souhaité de la checkbox (moche)
    $('#glider_tandem').appendTo(".form-group:last");
    $('#glider .checkbox label').appendTo(".form-group:last");
    $('#glider_tandem').appendTo(".form-group:last");
    $('#glider .checkbox').hide();

    $('.form-group:nth-child(1) label').insertBefore("#flight_filter_date_left_date");
    $('.filter-date-range label').clone().insertBefore("#flight_filter_date_right_date").text($('#toAck').text());

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
    
    //Permet d'afficher ou non le lien XC
    $('input[name=dataOption]').on('change', function() {
        var val = $('input[name=dataOption]:checked').val(); 
        if (val === 'xc-link'){
            $('#xcFields').show();
        } else {
            $('#xcFields').hide();
        }
     });
     
     $('#xcFields #getData').on('click', function() {
        var link = $('#xc-link').val();
        if (!link){
            alert("Please enter a link");
            return;
        }
        
        var res = link.split('detail:');
        console.log(res[1]);
        
        $.ajax({
            url: "https://www.xcontest.org/api/data/?flights/world/2018:"+res[1]+"&lng=en&key=03ECF5952EB046AC-A53195E89B7996E4-D1B128E82C3E2A66",
            jsonp: "callback",
            dataType: "jsonp",
            success: function( response ) {
                console.log(response); // server response
            }
        });
     });
});