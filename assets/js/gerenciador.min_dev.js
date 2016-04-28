
$(document).tooltip({
    selector: '[data-toggle="tooltip"]'
});
$(document).on('focus', '[data-toggle="maskMoney"]', function () {
    $(this).maskMoney({
        thousands: '.',
        decimal: ',',
        allowZero: true
    });
});

$(document).on('focus', '[data-toggle="datepicker"]', function () {
    $(this).datepicker({
        language: "pt-BR",
        format: "dd/mm/yyyy",
        todayBtn: true,
        todayHighlight: true,
        autoclose: true
    });
});

$("#loading2").modal({keyboard: false, backdrop: 'static', show: false});

$(window).on('beforeunload', function () {
    $('#loading2').modal('show');
});

$('div.collapse').on('show.bs.collapse hidden.bs.collapse', function (e) {
    $('a[href=#' + e.currentTarget.id + ']').find('span.glyphicon').toggleClass('glyphicon-circle-arrow-down').toggleClass('glyphicon-circle-arrow-up');
});
messagesModal = function (data) {
    if (!$.isEmptyObject(data)) {
        if (data.error) {
            $('<div class="modal" aria-hidden="true">'
                    + '<div class="modal-dialog">'
                    + '<div class="modal-content panel-danger">'
                    + '<div class="modal-header panel-heading">'
                    + '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                    + '<h4 class="modal-title" id="exampleModalLabel">&nbsp;Alerta</h4>'
                    + '</div>'
                    + '<div class="modal-body">'
                    + ($.isEmptyObject(data.error) ? data.error.join('<br>') : data.error)
                    + '</div>'
                    + '</div>'
                    + '</div>'
                    + '</div>')
                    .on('shown.bs.modal', function () {
                        var _modal = $(this);
                        _modal.find("button:first").focus();
                        setTimeout(function () {
                            _modal.modal('hide');
                        }, 3000);
                    })
                    .on('hidden.bs.modal', function () {
                        $(this).remove();
                        if (data.link) {
                            location.href = data.link;
                        }
                        if (data.reload === true) {
                            location.reload();
                        }
                    })
                    .modal('show');
        } else if (data.success) {
            $('<div class="modal fade" aria-hidden="true">'
                    + '<div class="modal-dialog">'
                    + '<div class="modal-content panel-success">'
                    + '<div class="modal-header panel-heading">'
                    + '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                    + '<h4 class="modal-title" id="exampleModalLabel">&nbsp;Alerta</h4>'
                    + '</div>'
                    + '<div class="modal-body">'
                    + ($.isEmptyObject(data.success) ? data.success.join('<br>') : data.success)
                    + '</div>'
                    + '</div>'
                    + '</div>'
                    + '</div>')
                    .on('shown.bs.modal', function () {
                        var _modal = $(this);
                        _modal.find("button:first").focus();
                        setTimeout(function () {
                            _modal.modal('hide');
                        }, 3000);
                    })
                    .on('hidden.bs.modal', function (e) {
                        $(this).remove();
                        if (data.link) {
                            location.href = data.link;
                        }
                        if (data.reload === true) {
                            location.reload();
                        }
                    })
                    .modal('show');
        } else if (data.link) {
            location.href = data.link;
        } else if (data.reload === true) {
            location.reload();
        }
    }
};


$('form:not(.noAjax,.submitFileType)').submit(function (e) {
    e.preventDefault();
    var form = $(this);
    form.find('[type=submit]').attr('data-loading-text', 'Aguarde...').button('loading');

    $.post(form.attr('action'), form.serialize(), function (data) {
        messagesModal(data);
    }, 'json').always(function () {
        form.find('[type=submit]').button('reset');
    }).fail(function () {
        alert('Tente mais tarde.');
    });
});

$('.submitFileType').submit(function (e) {
    e.preventDefault();
    var form = $(this);
    form.find('[type=submit]').attr('data-loading-text', 'Aguarde...').button('loading');

    if (window.FormData !== undefined)  // for HTML5 browsers
    {

        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            data: formData,
            dataType: "json",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            success: function (data, textStatus, jqXHR)
            {
                messagesModal(data);
            },
            error: function (jqXHR, textStatus, errorThrown) { }
        }).always(function () {
            form.find('[type=submit]').button('reset');
        }).fail(function () {
            alert('Tente mais tarde.');
        });
        e.preventDefault();
    }
});

$("a.Ajax, button.Ajax").on("click", function (e) {
    e.preventDefault();
    var btn = $(this);
    $('#loading2').modal('show');
    btn.prop('disabled', true);

    $.get(btn.attr('href'), function (data) {
        messagesModal(data);
    }, 'json').always(function () {
        btn.prop('disabled', false);
        $('#loading2').modal('hide');
    }).fail(function () {
        alert('Tente mais tarde.');
    });
});

$("a.AjaxConfirm, button.AjaxConfirm").on("click", function (e) {
    e.preventDefault();
    var btn = $(this);
    bootbox.confirm("<b><br>Deseja realmente executar a ação?<br><br></b>", function (result) {
        if (result) {
            btn.prop('disabled', true);
            $.get(btn.attr('href'), function (data) {
                messagesModal(data);
            }, 'json').always(function () {
                btn.prop('disabled', false);
            }).fail(function () {
                alert('Tente mais tarde.');
            });
        }
    });
});


$('.excluir').click(function (e) {
    e.preventDefault();
    var btn = $(this);
    var page = $("table input[name=page]").val();
    if (confirm('Deseja remover o(s) item(s) selecionado(s)?')) {

        btn.attr('data-loading-text', 'Aguarde...').button('loading');
        var ids = $("table input[name=selecao]:checkbox:checked").map(function () {
            return $(this).val();
        }).get();

        $.post(location.href, {action: 'excluir', ids: ids, page: page}, function (data) {
            messagesModal(data);
            $("table input[name=selecao]:checkbox:checked").parent().parent().fadeOut(function () {
                $(this).remove();
            });
        }, 'json').always(function () {
            btn.button('reset');
        }).fail(function () {
            alert('Tente mais tarde.');
        });
    }
});

$(document).on('change', '#check_all', function () {
    $('input[name=selecao]:checkbox').prop("checked", $(this).is(':checked'));
});

$('.imprimir').click(function (e) {
    e.preventDefault();
    var btn = $(this);
    //alert($( 'select[name="valor_inicial"]' ).val());
    //if (confirm('Deseja remover o(s) item(s) selecionad(s)?')) {         btn.attr('data-loading-text', 'Aguarde...').button('loading');
    $.post(location.href, {action: 'imprimir'}, function (data) {
        //if (!$.isEmptyObject(data)) {
        if (data.error) {
            $('#alerta').html('<div class="alert alert-danger fade in" role="alert">' + data.error.join('<br>') + '</div>');
        } else if (data.success) {
            $('#alerta').html('<div class="alert alert-success fade in" role="alert"><a class="btn btn-primary view-pdf" target="_blank" href="' + data.link + '">Relatório Clientes</a></div>');
            // + /*data.success.join('<br>') */+
            //location.href = data.link;
            //window.open( data.link,'_blank');

        } else if (data.link) {
            location.href = data.link;
        }
        //}         }, 'json').always(function () {
        btn.button('reset');
    }).fail(function () {
        alert('Tente mais tarde.');
    });

});

resumoTexto = function (texto, quantidade) {
    if (texto.length > quantidade) {
        //Se for maior, corta o texto sem cortar palavra no meio             return texto.substring(0, quantidade);
    } else {
        //Se não for maior, mostra o texto completo
        return texto;
    }
}

NumeroInteiros = function (campo) {
    $("input[name='" + campo + "']").bind("keyup blur focus", function (e) {
        e.preventDefault();
        var expre = /[^0-9]/g;
        // REMOVE OS CARACTERES DA EXPRESSAO ACIMA
        if ($(this).val().match(expre))
            $(this).val($(this).val().replace(expre, ''));
    });
};

formatted_date = function (date) {
    var myArray = date.split('/');
    return  myArray[2] + '-' + myArray[1] + '-' + myArray[0];
};

alerta_danger = function (msg) {
    $('#alerta').html('<div class="alert alert-danger fade in" role="alert">' + msg + '</div>');
};

MaskMoneyToFloat = function (value) {
    return value.replace(/\./g, '').replace(',', '.');
};

FloatToMaskMoney = function (value) {
    return value.replace(".", ",").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

keyupblurfocus_despesas = function (name) {
    $('input[name=' + name + ']').bind(" keyup blur focus ", function (e) {
        e.preventDefault();
        somaDespesas();
        labelAvista();
        replaceParcelas();
    });
};

DividirValorFloat = function (valor, divisor) {
    var resultado = 0;
    var resultado_1 = 0;
    var arrayResultado = [];

    if (valor % divisor !== 0) {
        resultado = (valor / divisor);
        if (resultado < 0.01) {
            messagesModal({error: 'Não é divisivel'});
            return false;
        } else {
            resultado = (valor / divisor).toFixed(2);
            resultado_1 = valor - ((divisor - 1) * resultado);
            arrayResultado[0] = resultado_1.toFixed(2);
            for (i = 1; i < divisor; i++) {
                arrayResultado[i] = resultado;
            }
            return (arrayResultado.reverse());
        }
    } else { //////////*****  MOD == 0
        resultado = (valor / divisor).toFixed(2);
        for (i = 0; i < divisor; i++) {
            arrayResultado[i] = resultado;
        }
        return (arrayResultado.reverse());
    }
};