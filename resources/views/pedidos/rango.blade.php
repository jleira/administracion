		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Pedidos</h3>
			</div>
			<div class="box-body">
				<table id="pedidosfinal" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>id</th>
							<th>fecha de montaje</th>
							<th>fecha de desmontaje</th>
							<th>cliente</th>
							<th>recepcion</th>
							<th>bodega</th>
							<th>estado</th>
							<th>MAS</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($reservas as $reserva)
						<tr>
							<td>{{$reserva->id}}</td>
							<td>{{formatofecha($reserva->desde)}}</td>
							<td>{{formatofecha($reserva->hasta)}}</td>
							<td>{{cliente($reserva->cliente,1)}}</td>
							<td>{{$reserva->recepcion}}</td>
							<td>{{validarbodega($reserva->bodega)}}</td>
							<td>{{validarlista($reserva->estado,6)}}</td>
							<td><a href="{{url('orden/'.$reserva->id)}}">mas</a></td>
						</tr>
						@endforeach

					</tbody>
					<tfoot>
						<tr>
							<th>Codigo</th>
							<th>Categoria</th>
							<th>Nombre</th>
							<th>Valor Mayorista</th>
							<th>Valor Minorista</th>
							<th>Detalles</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<script type="text/javascript">
			$(function () {

					$("#pedidosfinal").DataTable({
			"language": {
				"lengthMenu": "Mostrar _MENU_ pedidos por pagina",
				"zeroRecords": "No hay pedidos registrados",
				"info": "Pagina _PAGE_ de _PAGES_",
				"infoEmpty": "",
				"infoFiltered": "(Filtrado de _MAX_ pedido )",
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