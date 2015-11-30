jQuery(function () {
    $('form input').on('keypress', function (e) {
        return e.which !== 13;
    });
    $(document).on('focus', '[data-toggle="maskMoney"]', function () {
        $(this).maskMoney({
            thousands: '.',
            decimal: ',',
            allowZero: true
        });
    });
    $(".numeric").numeric({ decimal : ".",  negative : false, scale: 2 });

    NumeroInteiros = function (campo) {
        $("input[name='" + campo + "']").bind("keyup blur focus", function (e) {
            e.preventDefault();
            var expre = /[^0-9]/g;
            // REMOVE OS CARACTERES DA EXPRESSAO ACIMA
            if ($(this).val().match(expre))
                $(this).val($(this).val().replace(expre, ''));
        });
    };
    /* CARREGAR ITENS 
     *
     */
    NumeroInteiros('qtde_autorespostas');
    NumeroInteiros('qtde_atendentes');
    
   

});
inserirCampo = function (id) {
    $('input[name=users_id]').val(id);
}
 new Autocomplete("nome_procurado", function () {
        this.setValue = function (id) {
            inserirCampo(id);
        };
        this.setText = function (nome, id) {
            this.text.value = nome;
            return this;
        };
        if (this.isModified)
            this.setValue("");
        if (this.value.length < 1 && this.isNotClick)
            return;
        // O arquivo php abaixo é que será chamado via AJAX, sendo passado o parâmetro q com o valor digitado no campo
        return "ajax/ajax_autocomplete.php?action=_usuario&request=" + this.value;
//
    });