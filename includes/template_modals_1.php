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
                    Modal
                </h4>
            </div>
            <form name="formteste" role="form" method="post">
                <!-- Modal Body -->
                <div class="modal-body">
                   <div class="form-group">
                        <label for="exampleInputPassword1">Label 1</label>
                        <input type="text" class="form-control input1" name="input1" 
                               id="" placeholder=""/>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Label 2</label>
                        <input type="text" name="input2"  class="form-control input2"
                               id="" placeholder=""/>
                    </div>
                    <div class="form-group">
                        <label for="comment">Observa:</label>
                        <textarea class="form-control" rows="5" id="comment"></textarea>
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