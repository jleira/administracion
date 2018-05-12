@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.home') }}
@endsection


@section('main-content')


<div class="row">
<div class="col-lg-12">
	<div class="box box-primary">
							<div class="box-header">
								<h3 class="box-title">Clientes</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<table id="clientes" class="table table-bordered table-striped">
									<thead>
									<tr>
										<th>id</th>
										<th>Clase</th>
										<th>Nombre</th>
										<th>Cedula/Nit</th>
										<th>Telefono - celular</th>
										<th>Correo</th>
										<th>Direccion</th>
										<th>Tipo</th>
										<th>Credito</th>
										<th>Mas</th>
									</tr>
									</thead>
									<tbody>
									@foreach ($clientes as $cliente)
										<tr>
											<td>{{$cliente->id}}</td>
											<td>{{validarlista($cliente->clase , 5)}}</td>
											<td>{{$cliente->nombre }} {{$cliente->apellido }} </td>
											@if($cliente->clase==1)
											<td>{{cliente($cliente->id,6)}}</td>
											@else
											<td>{{$cliente->cedula}}</td>
											@endif
											<td>{{$cliente->telefono}} - {{$cliente->celular}}</td>
											<td>{{$cliente->correo}}</td>
											<td>{{$cliente->direccion}}</td>
											<td>{{validarlista($cliente->tipo,3)}}</td>
											<td>{{activo($cliente->credito)}}</td>
											<td><a href="{{url('editarcliente/'.$cliente->id)}}" class="btn btn-primary btn-xs">editar</a></td>
										</tr>

									@endforeach

									</tbody>

									 </table>
								 </div>
								 </div>
							 </div>
</div>

<script type="text/javascript">

$(function () {
	$("#clientes").DataTable({
		"language": {
					"lengthMenu": "Mostrar _MENU_ clientes por pagina",
					"zeroRecords": "No clientes registrados",
					"info": "Pagina _PAGE_ de _PAGES_",
					"infoEmpty": "",
					"infoFiltered": "(Filtrado de _MAX_ clientes )",
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
