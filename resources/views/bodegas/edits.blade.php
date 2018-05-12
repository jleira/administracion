@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.home') }}
@endsection

@section('main-content')
<div class="row">
		<div class="col-lg-10">
			<div class="box box-primary">
			            <div class="box-header">
			              <h3 class="box-title">Bodegas</h3>
			            </div>
			            <!-- /.box-header -->
			            <div class="box-body">
			              <table id="bodegas" class="table table-bordered table-striped">
			                <thead>
			                <tr>
												<th>Id</th>
			                  <th>Nombre</th>
			                  <th>Ciudad</th>
			                  <th>Direccion</th>
			                  <th>Telefono</th>
			                  <th>Editar</th>
			                </tr>
			                </thead>
			                <tbody>
											@foreach ($bodegas as $bodega)
												<tr>
													<td>{{$bodega->id}}</td>
													<td>{{$bodega->nombre}}</td>
													<td>{{$bodega->ciudad}}</td>
													<td>{{$bodega->direccion}}</td>
													<td>{{$bodega->telefono}}</td>
													<td><a href="{{url('editarbodega/'.$bodega->id)}}" >editar</a></td>
												</tr>
											@endforeach
											</tbody>
										  <tfoot>
										  <tr>
												<th>Nombre</th>
		 									 <th>Ciudad</th>
		 									 <th>Direccion</th>
		 									 <th>Telefono</th>
		 									 <th>Editar</th>
											 </tr>
											 </tfoot>
											 </table>
										 </div>
										 </div>
									 </div>
	</div>

<script type="text/javascript">
$(function () {
	$("#bodegas").DataTable({
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

@endsection
