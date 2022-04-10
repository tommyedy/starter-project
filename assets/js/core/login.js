$(document).ready(function () {
    $("#form_login").submit(function (e) {
        var username = $('#username').val();
        var password = $('#password').val();
        var crypt_password = CryptoJS.SHA256(password);
        var data = {
            username: username,
            password: crypt_password.toString()
        }
       // console.log(crypt_password.toString());

        //Validate if pattern not valid

        //Validate Object is null        

        $.ajax({
            url: baseUrl + 'Auth/do_login',
            type: 'POST',
            datatype: 'json',
            data: data,
            success: function (response) {
                var response = $.parseJSON(response);
                //console.log(response);
                if (response.status) {
                    window.location = baseUrl + 'home';
                } else {
                    show_notification_error(response.messages)
                }
            },
            error: function () {
               show_notification_error('Please Contact Administrator');
            }
        });
        e.preventDefault();
    });
});

