NumeroInteiros('limite_auto_resposta');
NumeroInteiros('dias_auto_resposta');
NumeroInteiros('limite_atendentes');
NumeroInteiros('dias_login');
EditarExp = function (user_id, nameForm) {
    $.ajax({
        type: "POST",
        data: {user_id: user_id, acao: 'load_expiracao'},
        dataType: "json",
        url: "./ajax/ajax_load.php",
        beforeSend: function () {
            $('#loading2').modal('show');
        },
        success: function (data, textStatus, jqXHR)
        {
            resetsimpleform(nameForm);
            var result = data.dado;
            $.each(result, function (k, v) {
                $('form[name="' + nameForm + '"] .' + k).val(v);
            });
            $('#loading2').modal('hide');
        },
        error: function (jqXHR, textStatus, errorThrown) { }
    }).fail(function () {
        $('#loading2').modal('hide');
        alert('Tente mais tarde.');
    });
}
resetsimpleform = function (nameForm) {
    $("input[name='user_id']").val('');
    $('form[name="' + nameForm + '"]')[0].reset();
}