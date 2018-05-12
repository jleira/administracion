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
 <div class="col-lg-6">
       <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Registrar Producto</h3>
          </div>
          <form role="form" enctype="multipart/form-data" method="POST" action="{{ url('registrarproducto') }}">
          {{ csrf_field() }}
          					<input type="hidden" value="0,0" name="posicion" id="posicion">

          <div class="box-body">
            <div class="form-group">
              <label for="exampleInput">Nombre</label>
              <input type="text" class="form-control" value="{{old('nombre') }}" name="nombre" id="exampleInputEmail1" placeholder="">
							@if ($errors->has('nombre') )
						   <p style="color:red;margin:0px">{{ $errors->first('nombre') }}</p>
						  @endif
						</div>
						<div class="form-group">
							<label for="exampleInput">Categoria</label>
							<select class="select2 form-control" name="categoria" id="categoria" onchange="obtenercodigo()">
								<option value="">sin categoria</option>
								@foreach ($categorias as $key)
								<option value="{{$key->valor_lista}}">{{$key->valor_item}}</option>
								@endforeach
								</select>
							@if ($errors->has('categoria') )
							 <p style="color:red;margin:0px">{{ $errors->first('categoria') }}</p>
							@endif
						</div>
						<div id="" class="form-group">
							<label for="exampleInput">Codigo</label>
							<label for=""  class="pull-right" class="">editar</label><input type="checkbox" name="editarcodigo" class="pull-right" id="editarcodigo" value="1">
							<input type="text" name="codigo" class="form-control" id="codigo" disabled="true">
							<input type="hidden" name="Codigo" id="Codigo">
							@if ($errors->has('codigo') )
							 <p style="color:red;margin:0px">{{ $errors->first('codigo') }}</p>
							@endif
							@if ($errors->has('Codigo') )
							 <p style="color:red;margin:0px">{{ $errors->first('Codigo') }}</p>
							@endif
						</div>
					<div class="form-group">
				<label for="exampleInput">Descripcion</label>
				<textarea type="text" class="form-control"  rows="4" name="descripcion" id="exampleInputEmail1" placeholder="">{{old('descripcion') }}</textarea>
			</div>
			<div class="form-group">
				<label for="exampleInput">Valor de Compra</label>
				<div class="input-group">
				 <div class="input-group-addon">
					 <i class="fa fa-usd"></i>
				 </div>
				<input type="text" onkeyup="format(this)" onchange="format(this)" class="form-control" value="{{old('vrmayorista') }}" name="vrunitario" id="exampleInputEmail1" placeholder="">
			</div>
		</div>

			<div class="form-group">
				<label for="exampleInput">Valor Alquiler a Mayorista</label>
				<div class="input-group">
				 <div class="input-group-addon">
					 <i class="fa fa-usd"></i>
				 </div>
				 <input type="text" onkeyup="format(this)" onchange="format(this)" class="form-control" value="{{old('vrmayorista') }}" name="vrmayorista" id="exampleInputEmail1" placeholder="">
				</div>
			</div>

			<div class="form-group">
				<label for="exampleInput">Valor Alquiler a Minoristas</label>
				<div class="input-group">
				 <div class="input-group-addon">
					 <i class="fa fa-usd"></i>
				 </div>
				<input type="text" onkeyup="format(this)" onchange="format(this)" class="form-control" name="vrminorista" value="{{old('vrminorista') }}" id="exampleInputEmail1" placeholder="">
			</div>
		</div>

			<div class="form-group">
				<label class="control-label">Seleccionar imagenes</label>
<input id="input-24" name="imagenes[]" type="file" multiple class="file-loading">
<p class="help-block"></p>
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

						<div class="col-lg-6">
							<div class="box box-primary">
							            <div class="box-header">
							              <h3 class="box-title">productos</h3>
							            </div>
							            <!-- /.box-header -->
							            <div class="box-body">
							              <table id="productos" class="table table-bordered table-striped">
							                <thead>
							                <tr>
							                  <th>Nombre</th>
							                  <th>Codigo</th>
																<th>Descripcion</th>
							                  <th>Valor Minorista</th>
							                  <th>Valor Mayorista</th>
							                </tr>
							                </thead>
							                <tbody>
															@foreach ($productos as $producto)
																<tr>
																	<td>{{$producto->nombre}}</td>
																	<td>{{$producto->codigo}}</td>
																	<td>{{$producto->descripcion}}</td>
																	<td>{{$producto->vl_minorista}}</td>
																	<td>{{$producto->vl_minorista}}</td>
																</tr>
															@endforeach

															</tbody>
														  <tfoot>
														  <tr>
																<th>Nombre</th>
 															 <th>Codigo</th>
 															 <th>Descripcion</th>
 															 <th>Valor Minorista</th>
 															 <th>Valor Mayorista</th>
															 </tr>
															 </tfoot>
															 </table>
														 </div>
														 </div>
													 </div>


</div>
<script src="{{ asset('public/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{ asset('public/js/fileinput.min.js')}}"></script>
<script src="{{ asset('public/themes/explorer/theme.js')}}"></script>
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

function get_loc() {
       if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(coordenadas);
       }else{
          alert('Este navegador es algo antiguo, actualiza para usar el API de localización');                  }
}
function coordenadas(position) {
      var lat = position.coords.latitude;
      var lon = position.coords.longitude;
      var map = document.getElementById("mapa");
      map.src = "http://maps.google.com/maps/api/staticmap?center=" + lat + "," + lon + "&amp;zoom=15&amp;size=600x480&amp;markers=color:red|label:A|" + lat + "," + lon + "&amp;sensor=false";
}


$(document).on('ready', function() {
    $("#input-24").fileinput({
        maxFileCount: 5,
				maxFileSize: 300,
        allowedFileTypes: ["image"]
    });
});

function obtenercodigo() {
 var categoria=document.getElementById("categoria").value;

	$.get("{{url('codigo/producto')}}"+'/'+categoria, function(data){
		document.getElementById("codigo").value=data;
		document.getElementById("Codigo").value=data;
	});
}
document.getElementById('editarcodigo').onclick =function() {
	check = document.getElementById("editarcodigo");
	if (check.checked) {
		document.getElementById('codigo').disabled = false;
	}else {
		document.getElementById('codigo').disabled = true;
	}

}

$(function () {
$(".select2").select2({
		placeholder: "Seleccione una categoria",
		allowClear: true,
		 language: "es"
	});
	$("#productos").DataTable({
		"language": {
					"lengthMenu": "Mostrar _MENU_ productos por pagina",
					"zeroRecords": "No hay productos registrados",
					"info": "Pagina _PAGE_ de _PAGES_",
					"infoEmpty": "",
					"infoFiltered": "(Filtrado de _MAX_ productos )",
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
function format(input)
{
var num = input.value.replace(/\./g,'');
if(!isNaN(num)){
num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
num = num.split('').reverse().join('').replace(/^[\.]/,'');
input.value = num;
}
else{ alert('Solo se permiten numeros');
input.value = input.value.replace(/[^\d\.]*/g,'');
}
}
</script>
@endsection
