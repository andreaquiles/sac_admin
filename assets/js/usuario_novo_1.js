NumeroInteiros('limite_auto_resposta');
NumeroInteiros('dias_auto_resposta');
NumeroInteiros('limite_atendentes');
NumeroInteiros('dias_login');
EditarExp = function (user_id ,nameForm) {
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
            var result = data.dado;
            $.each(result, function (k, v) {
                $('form[name="' + nameForm + '"] .' + k).val(v);
            });
//            $("input[name='user_id']").val(data.dado.user_id);
//            $("input[name='limite_auto_resposta']").val(data.dado.limite_auto_resposta);
//            $("input[name='dias_auto_resposta']").val(data.dado.dias_auto_resposta);
//            $("input[name='limite_atendentes']").val(data.dado.limite_atendentes);
//            $("input[name='dias_login']").val(data.dado.dias_login);
//            $("input[name='data']").val(data.dado.date);
            $('#loading2').modal('hide');
        },
        error: function (jqXHR, textStatus, errorThrown) { }
    }).fail(function () {
        $('#loading2').modal('hide');
        alert('Tente mais tarde.');
    });
}