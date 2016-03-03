$(document).ready(function () {
    $('.register-link').on('click', function () {
        $('.login-small').hide();
        $('.app-links').hide();
        $('.text-right').show();
    });

    $('.cancel-link').on('click', function () {
        $('.login-small').show();
        $('.app-links').show();
        $('.text-right').hide();
    });

    $(window).resize(function () {
        if (window.innerWidth > 767) {
            $('.login-small').hide();
            $('.app-links').show();
            $('.text-right').show();
        } else {
            $('.login-small').show();
        }
    });

    $("#reset").on('click', function () {
        console.log("asd");
        $.ajax({
            type: "POST",
            url: "http://localhost/flight/rest/User/PasswordReset",
            data: '{"email":"a@a.com"}',
            success: function() {
                console.log("yeah");
            },
            dataType: function() {
                console.log("bad");
            }
        });
    });
});