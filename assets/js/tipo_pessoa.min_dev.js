jQuery(function ($) {
    NumeroInteiros = function (campo) {
        $("input[name='" + campo + "']").bind("keyup blur focus", function (e) {
            e.preventDefault();
            var expre = /[^0-9]/g;
            // REMOVE OS CARACTERES DA EXPRESSAO ACIMA
            if ($(this).val().match(expre))
                $(this).val($(this).val().replace(expre, ''));
        });
    };
    NumeroInteiros('inscricao_estadual');
    NumeroInteiros('inscricao_municipal');
    $('[name=tpPessoa]').change(function () {
        if ($(this).select().val() == 'F') {
            $('.pFisica').show();
            $('.pJuridica').hide()
        } else {
            $('.pFisica').hide();
            $('.pJuridica').show()
        }
    });
    $('input[name=cep]').mask('99999-9999');
    $('input[name=cnpj]').mask('99.999.999/9999-99');
    $('input[name=cpf]').mask('999.999.999-99');
    $('input[name=data_nascimento], input[name=data_fundacao]').mask('99/99/9999');
    $('input[name=endereco_cep]').mask('99999-999');
    $('input[name=telefone], input[name=telefone_celular], input[name=contato_telefone]').mask('(99) 9999-9999?9');
    $('input[name="endereco_cidade"]').typeahead({delay: 300, showHintOnFocus: true, source: function (query, process) {
            return $.get('ajax_autocomplete_cidades', {q: query}, function (data) {
                process(data)
            }, 'json')
        }});
    $('[name=inscricao_estadual_isento]').change(function () {
        if ($(this).select().val() == 'sim') {
            $('[name=inscricao_estadual').attr('readonly', true).val('ISENTO')
        } else
            $('[name=inscricao_estadual').attr('readonly', false).val('')
    });
    var ie_tmp = '';
    $('.inscricao_estadual').click(function () {
        var ie = $('[name=inscricao_estadual]');
        if (ie.val() == 'ISENTO') {
            $(this).text('ISENTO');
            ie.attr('readonly', false).val(ie_tmp)
        } else {
            $(this).text('NÃO ISENTO');
            ie_tmp = ie.val();
            ie.attr('readonly', true).val('ISENTO')
        }
    });
    var im_tmp = '';
    $('.inscricao_municipal').click(function () {
        var im = $('[name=inscricao_municipal]');
        if (im.val() == 'ISENTO') {
            $(this).text('ISENTO');
            im.attr('readonly', false).val(im_tmp)
        } else {
            $(this).text('NÃO ISENTO');
            im_tmp = im.val();
            im.attr('readonly', true).val('ISENTO')
        }
    });
    var is_tmp = '';
    $('.inscricao_suframa').click(function () {
        var is = $('[name=inscricao_suframa]');
        if (is.val() == 'ISENTO') {
            $(this).text('ISENTO');
            is.attr('readonly', false).val(is_tmp)
        } else {
            $(this).text('NÃO ISENTO');
            is_tmp = is.val();
            is.attr('readonly', true).val('ISENTO')
        }
    })
})