@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.home') }}
@endsection


@section('main-content')

  <div class="row">
						<div class="col-lg-12">
							<div class="box box-primary">
							            <div class="box-header">
							              <h3 class="box-title">productos</h3>
							            </div>
							            <div class="box-body">
							              <table id="productos" class="table table-bordered table-striped">
							                <thead>
							                <tr>
															<th>Codigo</th>
															<th>Categoria</th>
							                <th>Nombre</th>
															<th>Valor  de Compra</th>
							                  <th>Alquiler mayorista</th>
                                <th>Alquiler minorista</th>
                                  <th>MAS</th>
							                </tr>
							                </thead>
							                <tbody>
															@foreach ($productos as $producto)
																<tr>
																	<td>{{$producto->codigo}}</td>
																	<td>{{validarlista($producto->categoria,4)}}</td>
																	<td>{{$producto->nombre}}</td>
																	<td>{{formatoprecio($producto->vl_unitario)}}</td>
																	<td>{{formatoprecio($producto->vl_mayorista)}}</td>
																	<td>{{formatoprecio($producto->vl_minorista)}}</td>
																	<td><a href="./producto/{{$producto->codigo}}">mas</a></td>
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
													 </div>


</div>
<script type="text/javascript">
$(function () {
	$("#productos").DataTable({
		"language": {
					"lengthMenu": "Mostrar _MENU_ productos por pagina",
					"zeroRecords": "No hay productos registrados",
					"info": "Pagina _PAGE_ de _PAGES_",
					"infoEmpty": "Ninguna bodega encontrada",
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
@endsection
