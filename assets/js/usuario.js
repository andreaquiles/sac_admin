
$(document).tooltip({
    selector: '[data-toggle="tooltip"]'
});
inserirCampo = function (value,input) {
    if (input === 'nome') {
        $('input[name=nome]').val(value);
    }
}
new Autocomplete("login", function () {
               
    this.setValue = function (value) {
        inserirCampo(value,'login');
    };
    this.setText = function (nome, id) {
        this.text.value = nome;//nome.replace(/'/g, "\\'"); 
        return this;
    };
    if (this.value.length < 1 && this.isNotClick)
        return;
    // O arquivo php abaixo é que será chamado via AJAX, sendo passado o parâmetro q com o valor digitado no campo
    return "ajax/ajax_autocomplete.php?action=_usuario&value=nome&request=" + this.value;
//
});

new Autocomplete("phone", function () {
    this.setValue = function (value) {
        inserirCampo(value,'phone');
    };
    this.setText = function (nome, id) {
        this.text.value = nome;
        return this;
    };
    if (this.value.length < 1 && this.isNotClick)
        return;
    // O arquivo php abaixo é que será chamado via AJAX, sendo passado o parâmetro q com o valor digitado no campo
    return "ajax/ajax_autocomplete.php?action=_phone&request=" + this.value;
//
});