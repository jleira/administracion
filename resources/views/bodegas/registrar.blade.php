@extends('layouts.app')

@section('htmlheader_title')
{{ trans('adminlte_lang::message.home') }}
@endsection


@section('main-content')

<div class="row">
	@if(Session::has('flash_message'))
	<div class="alert alert-success alert-dismissable fade in">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Grandioso!</strong> {{Session::get('flash_message')}}
	</div>
	@endif
	<div class="col-md-4">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Registrar Bodega</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->
			<form role="form" enctype="multipart/form-data" method="POST" action="{{ url('registerstorage') }}">
				{{ csrf_field() }}
				<div class="box-body">
					<div class="form-group">
						<input type="hidden" value="0,0" name="posicion" id="posicion">
						<label for="exampleInput">Nombre</label>
						<input type="text" class="form-control" value="{{ old('nombre')}}" name="nombre" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('nombre') )
						<p style="color:red;margin:0px">{{ $errors->first('nombre') }}</p>
						@endif
					</div>
					<div class="form-group">
						<label for="exampleInput">Ciudad</label>
						<input type="text" class="form-control" value="{{ old('ciudad')}}" name="ciudad" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('ciudad') )
						<p style="color:red;margin:0px">{{ $errors->first('ciudad') }}</p>
						@endif
					</div>

					<div class="form-group">
						<label for="exampleInput">Direccion</label>
						<input type="text" class="form-control" name="direccion" value="{{ old('direccion')}}" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('direccion') )
						<p style="color:red;margin:0px">{{ $errors->first('direccion') }}</p>
						@endif
					</div>

					<div class="form-group">
						<label for="exampleInput">Telefono</label>
						<input type="text" class="form-control" name="telefono" value="{{ old('telefono')}}" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('telefono') )
						<p style="color:red;margin:0px">{{ $errors->first('telefono') }}</p>
						@endif
					</div>
					<div class="box-footer" id="registrar">
						@if (validarcertificado())

						@else
						<button type="submit" class="btn btn-primary">Registrar</button>
						@endif
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-7">
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
						</tr>
						@endforeach

					</tbody>
					<tfoot>
						<tr>
							<th>Id</th>
							<th>Nombre</th>
							<th>Ciudad</th>
							<th>Direccion</th>
							<th>Telefono</th>

						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>


</div>
<script type="text/javascript">
	window.onload = function() {

		if (window.location.protocol != "https:") {

		}else{

			if (navigator.geolocation)
			{
				navigator.geolocation.getCurrentPosition(MostrarPosition, mostrarerrores);
			}
			else
			{
				alert("Geolocalizacion no soportada por el navegador");
				document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Registrar</button>';

			}
		}
	}

	function MostrarPosition(position)
	{
		document.getElementById('posicion').value=position.coords.latitude+",	" + position.coords.longitude;
		document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Registrar</button>';

	}
	function mostrarerrores(error) {
		switch(error.code) {
			case error.PERMISSION_DENIED:
			alert("Para poder enviar el formulario debe proporcionar su ubicacion");
			break;
			case error.POSITION_UNAVAILABLE:
			 
			document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Registrar</button>';
			break;
			case error.TIMEOUT:
			 
			document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Registrar</button>';

			break;
			case error.UNKNOWN_ERROR:
			 
			document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Registrar</button>';

			break;
		}
	}
	$(function () {
		$("#bodegas").DataTable({
			"language": {
				"lengthMenu": "Mostrar _MENU_ bodegas por pagina",
				"zeroRecords": "No hay bodegas registrados",
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
