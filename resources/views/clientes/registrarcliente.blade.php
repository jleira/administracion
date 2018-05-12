@extends('layouts.app')

@section('htmlheader_title')
{{ trans('adminlte_lang::message.home') }}
@endsection


@section('main-content')

<div class="row">

	<div class="col-md-4">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Registrar Cliente</h3>
			</div>
			<form role="form" enctype="multipart/form-data" method="POST" action="{{ url('registrarcliente') }}">
				{{ csrf_field() }}
				<div class="box-body">
					<div class="form-group">
						<label for="exampleInput">Clase de Cliente</label>
						<select class="form-control" name="clase" id="clase" onchange="mostrarnit()">
							@foreach($clase as $clase )
							<option value="{{$clase->valor_lista}}">{{$clase->valor_item}}</option>
							@endforeach
						</select>
					</div>
					<input type="hidden" value="0,0" name="posicion" id="posicion">
					<div class="form-group">
						<label for="exampleInput">Nombre</label>
						<input type="text" class="form-control" value="{{ old('nombre')}}" name="nombre" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('nombre') )
						<p style="color:red;margin:0px">{{ $errors->first('nombre') }}</p>
						@endif
					</div>
					<div class="" id="persona" style="display:none">
						<div class="form-group">
							<label for="exampleInput">Apellido</label>
							<input type="text" class="form-control" value="{{ old('apellido')}}" name="apellido" id="exampleInputEmail1" placeholder="">
							@if ($errors->has('apellido') )
							<p style="color:red;margin:0px">{{ $errors->first('apellido') }}</p>
							@endif
						</div>

						<div class="form-group">
							<label for="exampleInput">Cedula</label>
							<input type="number" class="form-control" value="{{ old('cedula')}}" name="cedula" id="exampleInputEmail1" placeholder="">
							@if ($errors->has('cedula') )
							<p style="color:red;margin:0px">{{ $errors->first('cedula') }}</p>
							@endif
						</div>
					</div>
					<div class="" id="nit">
						<div class="form-group">
							<label for="exampleInput">Nit</label>
							<input type="number" class="form-control" value="{{ old('nit')}}" name="nit" id="exampleInputEmail1" placeholder="">
							@if ($errors->has('nit') )
							<p style="color:red;margin:0px">{{ $errors->first('nit') }}</p>
							@endif
						</div>

					</div>


					<div class="form-group">
						<label for="exampleInput">Telefono</label>
						<input type="number" class="form-control" value="{{ old('telefono')}}" name="telefono" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('telefono') )
						<p style="color:red;margin:0px">{{ $errors->first('telefono') }}</p>
						@endif
					</div>

					<div class="form-group">
						<label for="exampleInput">Celular</label>
						<input type="number" class="form-control" value="{{ old('celular')}}" name="celular" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('celular') )
						<p style="color:red;margin:0px">{{ $errors->first('celular') }}</p>
						@endif
					</div>
					<div class="form-group">
						<label><input type="checkbox" name="credito" value="1">
							Cliente dispone de credito
						</label>
					</div>
					<div class="form-group">
						<label for="exampleInput">Correo</label>
						<input type="text" class="form-control" value="{{ old('correo')}}" name="correo" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('correo') )
						<p style="color:red;margin:0px">{{ $errors->first('correo') }}</p>
						@endif
					</div>
					<div class="form-group">
						<label for="exampleInput">Direccion</label>
						<input type="text" class="form-control" value="{{ old('direccion')}}" name="direccion" id="exampleInputEmail1" placeholder="">
						@if ($errors->has('direccion') )
						<p style="color:red;margin:0px">{{ $errors->first('direccion') }}</p>
						@endif
					</div>
					<div class="form-group">
						<label for="exampleInput">Tipo de cliente</label>
						<select class="form-control" name="tipo">
							@foreach($tipos as $tipo )
							<option value="{{$tipo->valor_lista}}">{{$tipo->valor_item}}</option>
							@endforeach
						</select>
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
				<h3 class="box-title">Clientes</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<table id="clientes" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Clase</th>
							<th>Cedula</th>
							<th>Telefono</th>
							<th>Celular</th>
							<th>correo</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($clientes as $cliente)
						<tr>
							<td>{{$cliente->nombre }} {{$cliente->apellido }} </td>
							<td>{{validarlista($cliente->clase , 5)}}</td>
							@if($cliente->clase==1)
											<td>{{cliente($cliente->id,6)}}</td>
											@else
											<td>{{$cliente->cedula}}</td>
											@endif
							<td>{{$cliente->telefono}}</td>
							<td>{{$cliente->celular}}</td>
							<td>{{$cliente->correo}}</td>
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
	function mostrarnit() {
		var categoria=document.getElementById("clase").value;
		if (categoria==2) {
			document.getElementById('persona').style.display = "block";
			document.getElementById('nit').style.display = "none";
		}
		else {
			document.getElementById('persona').style.display = "none";
			document.getElementById('nit').style.display = "block";
		}
	}

	$(function () {
		$("#clientes").DataTable({
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
