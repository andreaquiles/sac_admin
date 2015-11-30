
$("#loading2").modal({keyboard: false, backdrop: 'static', show: false});

$(window).on('beforeunload', function () {
    $('#loading2').modal('show');
});

$(document).on('focus', '[data-toggle="maskMoney"]', function () {
    $(this).maskMoney({
        thousands: '.',
        decimal: ',',
        allowZero: true
    });
});

$(document).ready(function () { //pronto para executar o js
    //CAPAZ DE EXECUTAR O JS
    $('form:not(.noAjax)').submit(function (e) {
        e.preventDefault();
        var form = $(this);
        form.find('[type=submit]').attr('data-loading-text', 'Aguarde...').button('loading');
        $.post(form.attr('action'), form.serialize(), function (data) {
            if (data.success) {
                if (data.link) {
                    location.href = data.link;
                }
                $('#alerta').html('<div class="alert alert-success \n\ fade in" role="alert">'
                        + data.success + '</div>');

            }
            else if (data.error) {
                $('#alerta').html('<div class="alert alert-danger" role="alert">'
                        + data.error + '</div>');
            }

            else if (data.success2) {
                $('#myModal2').modal('hide');
                location.reload();
                // document.location.href = document.location.href;//+'?inserir=contato';
            }
            else if (data.error2) {
                $('#myModal2').modal('hide');
                $('#alerta2').html('<div class="alert alert-danger" role="alert">'
                        + data.error2 + '</div>');
            }
        }, 'json').always(function () {
            form.find('[type=submit]').button('reset');
        }).fail(function () {
            alert('Tente mais tarde.');
        });
    });

});

$(function () {
    $('INPUT[type="file"]').change(function () {
        //var ext = this.value.match(/\.(.+)$/)[1];
        var myFile = this.value.split(".");
        var size = myFile.length;
        var extension = myFile[size - 1];
        switch (extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'bmp':
            case 'mp3':
            case 'wav':
            case 'mp4':
            case'mov':
                //$('#uploadButton').attr('disabled', false);
                break;
            default:
                $('#alerta').html('<div class="alert alert-danger" role="alert">'
                        + 'Este arquivo não é permitido(  ' + extension + '  ) </div>');
                this.value = '';
        }
    });

    $('#form_listen').ajaxForm({
        success: function () {
            // if (data == 1) {
            //se for sucesso, simplesmente recarrego a página. Aqui você pode usar sua imaginação.
            location.reload();
        }
    });

    NumeroInteiros = function (campo) {
        $("input[name='" + campo + "']").bind("keyup blur focus", function (e) {
            e.preventDefault();
            var expre = /[^0-9]/g;
            // REMOVE OS CARACTERES DA EXPRESSAO ACIMA
            if ($(this).val().match(expre))
                $(this).val($(this).val().replace(expre, ''));
        });
    };
    NumeroInteiros('numero');
});

