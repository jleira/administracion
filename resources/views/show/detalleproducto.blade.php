  <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title">{{$producto}} - {{validarproductoporcodigo($producto)}}</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <table id="productosfin" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Cantidad</th> 
                    <th>Cliente</th>
                    <th>desde</th>
                    <th>hasta</th>
                    <th>lugar de evento</th>
                    <th>dia de evento</th>
                    <th>telefonos</th>
                  </tr>
                  </thead>
                  <tbody>
                    @for ($i = 0; $i < $iteraciones; $i++)
                    <tr> 
                      <td>{{$cantidades[$i]}}</td>
                      <td>{{cliente($cliente[$i],1)}}</td>
                      <td>{{formatofecha($desde[$i])}}</td>
                      <td>{{formatofecha($hasta[$i])}}</td>
                      <td>{{$recepeciones[$i]}}</td>
                      <td>{{$evento[$i]}}</td>
                      <td>{{cliente($cliente[$i],3)}} - {{cliente($cliente[$i],5)}}</td>
                      </tr>
@endfor
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Cantidad</th> 
                    <th>Cliente</th>
                    <th>desde</th>
                    <th>hasta</th>
                    <th>lugar de evento</th>
                    <th>dia de evento</th>
                    <th>telefonos</th>
                  </tr>
                </tfoot>
                   </table>
                 </div>
                 </div>


               <script type="text/javascript">
               $(function () {
               	$("#productosfin").DataTable({
                   "paging": true,
                  "lengthChange": false,
                  "searching": false,
                  "ordering": true,
                  "info": true,
                  "autoWidth": false
               	});
               });

               </script>