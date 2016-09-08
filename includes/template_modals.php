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
            <form name="formexpiracoes" role="form" method="post">
                <!-- Modal Body -->
                <div class="modal-body">
                    <input type="hidden" name="user_id" class="user_id" />
                    <div class="form-group" >
                        <label for="exampleInputEmail1">Número Autorespostas</label>
                        <input type="text" name="limite_auto_resposta" 
                               class="form-control limite_auto_resposta" 
                               id="exampleInputEmail1" placeholder=""/>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Dias Autorespostas</label>
                        <input type="text" class="form-control dias_auto_resposta" name="dias_auto_resposta" 
                               id="exampleInputPassword1" placeholder=""/>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Número Atendentes</label>
                        <input type="text" name="limite_atendentes"  class="form-control limite_atendentes"
                               id="exampleInputEmail1" placeholder=""/>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Data</label>
                        <input type="text" name="data" data-toggle="datepicker"  class="form-control date"
                               id="exampleInputPassword1" placeholder=""/>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Dias de login</label>
                        <input type="text" name="dias_login"  class="form-control dias_login"
                               id="exampleInputEmail1" placeholder=""/>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

 <div class="modal fade LoginSignup" id="myModalxx" tabindex="-1" role="dialog"
    aria-labelledby="LoginLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title">Login</h3>
          </div>
          <div class="modal-body">
            <form method="post" action="https://www.yourwebsite.com/client/dologin.php">
              <div class="form-group">
                <input class="form-control input-lg" type="text" name="username" size="50"
                placeholder="Email ID">
              </div>
              <div class="form-group">
                <input class="form-control input-lg" type="password" name="password" size="20"
                placeholder="Password">
              </div>
              <div class="form-group">
                <input type="submit" value="Login to my Account" class="btn btn-success btn-lg">
              </div>
            </form>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>