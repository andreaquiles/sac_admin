<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                        data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Fechar</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Modal Expirações
                </h4>
            </div>
            <form role="form" method="post">
                <!-- Modal Body -->
                <div class="modal-body">
                    <input type="hidden" name="user_id" />
                    <div class="form-group" >
                        <label for="exampleInputEmail1">Número Autorespostas</label>
                        <input type="text" name="limite_auto_resposta" 
                               class="form-control" 
                               id="exampleInputEmail1" placeholder=""/>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Dias Autorespostas</label>
                        <input type="text" class="form-control" name="dias_auto_resposta" 
                               id="exampleInputPassword1" placeholder=""/>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Número Atendentes</label>
                        <input type="text" name="limite_atendentes"  class="form-control"
                               id="exampleInputEmail1" placeholder=""/>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Data</label>
                        <input type="text" name="data" data-toggle="datepicker"  class="form-control"
                               id="exampleInputPassword1" placeholder=""/>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Dias de login</label>
                        <input type="text" name="dias_login"  class="form-control"
                               id="exampleInputEmail1" placeholder=""/>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
