@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.home') }}
@endsection


@section('main-content')

  <div class="row">
		@foreach ($productos as $producto)
 <div class="col-lg-6">
       <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Editar Producto</h3>
          </div>
          <form role="form" enctype="multipart/form-data" method="POST" action="{{ url('editarproducto') }}">
          {{ csrf_field() }}
          <input type="hidden" value="0,0" name="posicion" id="posicion">
					<input type="hidden" name="codigoprincipal" value="{{$producto->codigo}}">
          <div class="box-body">
            <div class="form-group">
              <label for="exampleInput">Nombre</label>
              <input type="text" class="form-control" value="{{$producto->nombre}}" name="nombre" id="exampleInputEmail1" placeholder="">
							@if ($errors->has('nombre') )
						   <p style="color:red;margin:0px">{{ $errors->first('nombre') }}</p>
						  @endif
						</div>
						<div class="form-group">
							<label for="exampleInput">Categoria</label>
								<select class="select2 form-control" name="categoria" id="categoria" onchange="obtenercodigo()">
								<option value="{{$producto->categoria}}">{{validarlista($producto->categoria,4)}}</option>
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
							<input type="text" name="codigo" class="form-control" id="codigo" value="{{$producto->codigo}}" disabled="true">
							<input type="hidden" name="Codigo" id="Codigo" value="{{$producto->codigo}}">
							@if ($errors->has('codigo') )
							 <p style="color:red;margin:0px">{{ $errors->first('codigo') }}</p>
							@endif
							@if ($errors->has('Codigo') )
							 <p style="color:red;margin:0px">{{ $errors->first('Codigo') }}</p>
							@endif
						</div>
					<div class="form-group">
				<label for="exampleInput">Descripcion</label>
				<textarea type="text" class="form-control"  rows="4" name="descripcion" id="exampleInputEmail1" placeholder="">{{$producto->descripcion}}</textarea>
			</div>

			<div class="form-group">
				<label for="exampleInput">Valor de Compra</label>
				<input type="number" class="form-control" value="{{$producto->vl_unitario}}" name="vrcompra" id="exampleInputEmail1" placeholder="">
			</div>

			<div class="form-group">
				<label for="exampleInput">Valor de Alquiler Mayorista</label>
				<input type="number" class="form-control" value="{{$producto->vl_mayorista}}" name="vrmayorista" id="exampleInputEmail1" placeholder="">
			</div>

			<div class="form-group">
				<label for="exampleInput">Valor de Alquiler Minorista</label>
				<input type="number" class="form-control" name="vrminorista" value="{{$producto->vl_minorista}}" id="exampleInputEmail1" placeholder="">
			</div>

			<div class="form-group">
				<label class="control-label">Seleccionar imagenes</label>
<input id="input-24" name="imagenes[]" type="file" multiple class="file-loading">
</div>

<div class="box-footer" id="registrar">
                  	@if (validarcertificado())

					@else
					<button type="submit" class="btn btn-primary">Editar</button>
					@endif

                  </div>
                        </form>

      </div>
      </div>
			</div>
			{{eliminarimagenes($producto->imagen,$producto->codigo)}}
			@endforeach



</div>
<script src="{{ asset('public/js/fileinput.min.js')}}"></script>
<script src="{{ asset('public/themes/explorer/theme.js')}}"></script>
<script src="{{ asset('public/plugins/select2/select2.full.min.js')}}"></script>

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
     document.getElementById('posicion').value=position.coords.latitude+"," + position.coords.longitude;
     document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Editar</button>';

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
            
           document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Editar</button>';

            break;
        case error.UNKNOWN_ERROR:
            
           document.getElementById('registrar').innerHTML='	<button type="submit" class="btn btn-primary">Editar</button>';

            break;
    }
}

function obtenercodigo() {
 var categoria=document.getElementById("categoria").value;

	$.get("http://localhost/net-administrativo/public/codigo/producto/"+categoria, function(data){
		document.getElementById("codigo").value=data;
		document.getElementById("Codigo").value=data;
	});
}

function eliminarimagen(ruta) {
	if (confirm("Desea eliminar la imagen permanentemente?")) {
		ubicacion=document.getElementById("posicion").value;
		ubicacion=ubicacion.replace(",","a");
			location.href="{{url('productos/eliminarimagen/')}}"+"/"+ruta+"/"+ubicacion;
	}

}
$(document).on('ready', function() {
    $("#input-24").fileinput({
        maxFileCount: 5,
				maxFileSize: 300,
        allowedFileTypes: ["image"]
    });
});
$(document).ready(function(){

	loadGallery(true, 'a.thumbnail');

	//This function disables buttons when needed
	function disableButtons(counter_max, counter_current){
			$('#show-previous-image, #show-next-image').show();
			if(counter_max == counter_current){
					$('#show-next-image').hide();
			} else if (counter_current == 1){
					$('#show-previous-image').hide();
			}
	}

	/**
	 *
	 * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
	 * @param setClickAttr  Sets the attribute for the click handler.
	 */

	function loadGallery(setIDs, setClickAttr){
			var current_image,
					selector,
					counter = 0;

			$('#show-next-image, #show-previous-image').click(function(){
					if($(this).attr('id') == 'show-previous-image'){
							current_image--;
					} else {
							current_image++;
					}

					selector = $('[data-image-id="' + current_image + '"]');
					updateGallery(selector);
			});

			function updateGallery(selector) {
					var $sel = selector;
					current_image = $sel.data('image-id');
					$('#image-gallery-caption').text($sel.data('caption'));
					$('#image-gallery-title').text($sel.data('title'));
					$('#image-gallery-image').attr('src', $sel.data('image'));
					disableButtons(counter, $sel.data('image-id'));
			}

			if(setIDs == true){
					$('[data-image-id]').each(function(){
							counter++;
							$(this).attr('data-image-id',counter);
					});
			}
			$(setClickAttr).on('click',function(){
					updateGallery($(this));
			});
	}
});


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
