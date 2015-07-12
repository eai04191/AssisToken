$(function () {
    $('#consumer_list a').click(function (e) {
        e.preventDefault();
        $('#CK').val($(this).attr('data-ck'));
        $('#CS').val($(this).attr('data-cs'));
    });
    $('#xauth, #direct_oauth').click(function () {
        $('#success_area, #error_area').hide();
        $('#loading_image').show();
        var is_xauth = $(this).attr('id') === 'xauth' ? 1 : 0;
        $.ajax({
            url: 'assistoken.php',
            type: 'POST',
            dataType: 'json',
            data: {
                CK: $('#CK').val(),
                CS: $('#CS').val(),
                SN: $('#SN').val(),
                PW: $('#PW').val()
            },
            timeout: 10000,
            success: function(data) {
                $('#success_msg_AT').val(data.AT);
                $('#success_msg_AS').val(data.AS);
                $('#success_twist_msg').text($.sprintf(
                    "$to = new TwistOAuth(\n    '%s',\n    '%s',\n    '%s',\n    '%s'\n);",
                    data.CK, data.CS, data.AT, data.AS
                ));
                $('#success_area').show();
            },
            error: function(xhr, text) {
                if (xhr.status) {
                    try {
                        $('#error_msg').text(JSON.parse(xhr.responseText).error);
                    } catch (e) {
                        $('#error_msg').text(e.message);
                    }
                } else {
                    $('#error_msg').text(text);
                }
                $('#error_area').show();
            },
            complete : function(data) {
                $('#loading_image').hide();
            }
        });
    });
    
});