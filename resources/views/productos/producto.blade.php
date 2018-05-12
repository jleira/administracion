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
	@foreach ($productos as $producto)
	<div class="col-lg-5">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">producto {{$producto->codigo}}</h3>
				<input type="hidden" name="" id="id" value="{{$producto->codigo}}">
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<div class="table-responsive">
					<table class="table  table-hover table-bordered table-striped">
						<thead>
							<tr>
								<th>item</th>
								<th>valor</th>
							</tr>
						</thead>
						<tbody>

							<tr>
								<td>Nombre</td>
								<td>{{$producto->nombre}}</td>
							</tr>
							<tr>
								<td>Codigo</td>
								<td>{{$producto->codigo}}</td>
							</tr>
							<tr>
								<td>Categoria</td>
								<td>{{validarlista($producto->categoria,4)}}</td>
							</tr>
							<tr>
								<td>descripcion</td>
								<td>{{$producto->descripcion}}</td>
							</tr>

							<tr>
								<td>Valor Unitario</td>
								<td>{{formatoprecio($producto->vl_unitario)}}</td>
							</tr>

							<tr>
								<td>Valor Mayorista</td>
								<td>{{formatoprecio($producto->vl_mayorista)}}</td>
							</tr>

							<tr>
								<td>Valor Minorista</td>
								<td>{{formatoprecio($producto->vl_minorista)}}</td>
							</tr>
							<tr>
								<td>Fecha de Compra</td>
								<td>{{$producto->fecha_registro}}</td>
							</tr>
							<tr>


						</tbody>
					</table>

				</div>
			</div>
		</div>
		<a href="{{url('editarproducto/'.$producto->codigo)}}" class="btn btn-success">editar</a>		
	</div>
	<div class="col-lg-7">
		@if(Auth::user()->id_perfil==1)
						<div class="form-group">
						<label>Buscar historico en este rango de tiempo</label>

						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input type="text" name="tiempo" value="{{old('tiempo')}}" class="form-control pull-right" id="reservation">
						</div>
						@if ($errors->has('tiempo') )
						<p style="color:red;margin:0px">{{ $errors->first('tiempo') }}</p>
						@endif
					<a href="javascript:buscar()" class="btn btn-success">Historico</a>
					</div>
@endif
	<div id="disponibles"></div>

	</div>


	{{buscarimagenes($producto->imagen,$producto->codigo)}}

	@endforeach
</div>

<script type="text/javascript">

	function buscar()
	{
		fecha=document.getElementById('reservation').value;
		producto=document.getElementById('id').value;
		fecha=fecha.replace(" - ", "-");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		fecha=fecha.replace(" ", "");
		var url='historicoproducto?productid='+producto+"&tiempo="+fecha;
		if (fecha=="") {
			alert('seleccione un rango de fecha');
		}else {
			$("#disponibles").load("{!!url('"+url+"')!!}");
		}
	}
$(document).ready(function(){

		$('#reservation').daterangepicker({
			format: 'DD/MM/YYYY hh:mm A',
			timePickerIncrement: 30,
			timePicker: true,
			locale: {
				"separator": " - ",
				"applyLabel": "Aceptar",
				"cancelLabel": "Cancelar",
				"fromLabel": "Desde",
				"toLabel": "Hasta",
				"customRangeLabel": "Custom",
				"daysOfWeek": [
				"Do",
				"Lu",
				"Ma",
				"Mi",
				"Ju",
				"Vi",
				"Sa"
				],
				"monthNames": [
				"Enero",
				"Febrero",
				"Marzo",
				"Abril",
				"Mayo",
				"Junio",
				"Julio",
				"Agusto",
				"Septiembre",
				"Octubre",
				"Noviembre",
				"Diciembre"        ],

			}
		});
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
</script>

@endsection
