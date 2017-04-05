Editar = function (nameForm, value1, value2) {
    resetsimpleform(nameForm);
    $('form[name="' + nameForm + '"] .input1').val(value1);
    $('form[name="' + nameForm + '"] .input2').val(value2);
//    $.ajax({
//        type: "POST",
//        data: {user_id: user_id, acao: 'load_expiracao'},
//        dataType: "json",
//        url: "./ajax/ajax_load.php",
//        beforeSend: function () {
//            $('#loading2').modal('show');
//        },
//        success: function (data, textStatus, jqXHR)
//        {
//            resetsimpleform(nameForm);
//            var result = data.dado;
//            $.each(result, function (k, v) {
//                $('form[name="' + nameForm + '"] .' + k).val(v);
//            });
//            $('#loading2').modal('hide');
//        },
//        error: function (jqXHR, textStatus, errorThrown) { }
//    }).fail(function () {
//        $('#loading2').modal('hide');
//        alert('Tente mais tarde.');
//    });
}

resetsimpleform = function (nameForm) {
    $('form[name="' + nameForm + '"]')[0].reset();
}