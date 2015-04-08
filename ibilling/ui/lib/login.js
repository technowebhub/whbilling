$(document).ready(function () {
    $("#sysfrm-msg").hide();
    $("#fsubmit").click(function (e) {
        e.preventDefault();
        $(this).removeClass("primary");
        $(this).addClass("info");
        $(this).val('Checking...');

        $.post("index.php?_route=admin/ajaxlogin", {

            username: $('#username').val(),
            password: $('#password').val()

        })
            .done(function (data) {

                setTimeout(function () {
                    var btn = $("#fsubmit");
                    var msg = $("#sysfrm-msg");
                    // alert (data);
                    //  $("#rating").html(data);
                    //  $("#vote").fadeOut('slow');
                    if (data == 'ok') {
                        msg.hide();
                        btn.removeClass("primary");
                        btn.addClass("success");
                        btn.val('Login Successful! Redirecting...');
                        window.location = 'a.php?_route=admin/dashboard';
                    }
                    else {

                        msg.html(data);
                        msg.fadeIn("slow");
                        btn.removeClass("info");
                        btn.addClass("primary");
                        btn.val('Login');


                    }


                }, 2000);
            });


    });
});