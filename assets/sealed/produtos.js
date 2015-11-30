jQuery(function ($) {
    var campos_items = 1;
    $('input[name^="produtos"][name$="[quantidade]"]').on('keyup', function () {
        var expre = /[^0-9]/g;
        // REMOVE OS CARACTERES DA EXPRESSAO ACIMA
        if ($(this).val().match(expre))
            $(this).val($(this).val().replace(expre, ''));
    });
    function mais_campos_html(campos_numero, print) {
        //mais_campos = function () {
        if (print == true) {
            var campos_html = '<tr>'
                    + '      <td><button type="button" class="form-control btn btn-default btn-block" name="trash"><span class="glyphicon glyphicon-trash text-center"></span></button></td>'
                    + '      <td><input class="form-control" type="text" name="produtos[' + campos_numero + '][item]">'
                    + '      <td><input class="form-control" type="text" maxlength="10" name="produtos[' + campos_numero + '][quantidade]"  id="produtos_' + campos_numero + '_quantidade" value="0" ></td>'
                    + '</tr>';
            $('.mais_campos').append(campos_html);
        }

//            new Autocomplete('produtos[' + campos_numero + '][item]', function () {
//                this.setValue = function (id, venda) {
//                    inserirCamposProduto(campos_numero, id, venda);
//                };
//                this.setText = function (nome, id) {
//                    this.text.value = nome;
//                    if (!id)
//                    {
//                        $.post('insert_produto', {produto: nome}, function (dados) {
//                            if (!$.isEmptyObject(dados)) {
//                                if (dados.id_produto) {
//                                    inserirCamposProduto(campos_numero, dados.id_produto, '0.00');
//                                    $('#alerta').html('<div class="alert alert-success fade in" role="alert">' + nome + ' cadastrado com sucesso!</div>');
//                                } else if (dados.error) {
//                                    $('#alerta').html('<div class="alert alert-danger fade in" role="alert">' + dados.error + '</div>');
//                                }
//                            }
//                        }, 'json');
//                    }
//                    return this;
//                };
//                if (this.isModified)
//                    this.setValue("");
//                if (this.value.length < 1 && this.isNotClick)
//                    return;
//                // O arquivo php abaixo é que será chamado via AJAX, sendo passado o parâmetro q com o valor digitado no campo
//                return "ajax_autocomplete_produtos?q=" + this.value;
//
//            });

        campos_items++;
    }

    $('button[name=mais_campos]').click(function (e) {

        e.preventDefault();
        //var campo_anterior = '';
        //campo_anterior = $('#produtos_' + (campos_items - 1) + '_id').val();
        // if (campo_anterior !== '') {
        mais_campos_html(campos_items, true);
        // }
    });
    $('.mais_campos').on('click', 'button[name=trash]', function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        //somarTodosItens();
    });
});
