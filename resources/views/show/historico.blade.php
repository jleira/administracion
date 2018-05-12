
  <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title">productos</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <table id="productosf" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>cantidad</th>
                    <th>bodega</th>
                    <th>desde</th>
                    <th>hasta</th>
                    <th>cliente</th>
                  <th>estado</th>
                  </tr>
                  </thead>
                  <tbody>
                    @for ($i = 0; $i < $ciclos; $i++)
                    <tr>
                      <td>{{$cantidades[$i]}}</td>
                      <td>{{validarbodega($bodegas[$i])}}</td>
                      <td>{{formatofecha($desde[$i])}}</td>
                      <td>{{formatofecha($hasta[$i])}}</td>
                      <td>{{cliente($clientes[$i],1)}}</td>

                      <td>{{validarlista($estados[$i],6)}}</td>


                      </tr>
@endfor
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>cantidad</th>
                    <th>bodega</th>
                    <th>desde</th>
                    <th>hasta</th>
                    <th>cliente</th>
                  <th>estado</th>
                  </tr>
                </tfoot>
                   </table>
                 </div>
                 </div>


               <script type="text/javascript">
               $(function () {
               	$("#productosf").DataTable({
               		"language": {
               					"lengthMenu": "Mostrar _MENU_ productos por pagina",
               					"zeroRecords": "No hay productos registrados",
               					"info": "Pagina _PAGE_ de _PAGES_",
               					"infoEmpty": "",
               					"infoFiltered": "(Filtrado de _MAX_ bodegas )",
               					"search":'',
        "searchPlaceholder": "Buscar",
               					"paginate": {
               			"first":      "Primero",
               			"last":       "Ultimo",
               			"next":       "Siguiente",
               			"previous":   "Anterior"
               	}
               			}
               	});
               });

               </script>
